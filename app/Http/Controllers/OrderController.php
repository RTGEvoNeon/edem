<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderNotifier;
use App\Services\YooKassaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        private readonly YooKassaService $yooKassa,
        private readonly OrderNotifier $notifier,
    ) {}

    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'delivery_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'product_url' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1|max:50',
            'items.*.product_id' => 'required|integer|distinct',
            'items.*.quantity' => 'required|integer|min:1|max:99',
        ]);

        $quantities = collect($validated['items'])->pluck('quantity', 'product_id');

        // Цены берём из базы, а не от клиента.
        $products = Product::query()->available()->whereIn('id', $quantities->keys())->get();

        if ($products->count() !== $quantities->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Некоторые товары недоступны для заказа. Обновите корзину и попробуйте снова.',
            ], 422);
        }

        $productUrl = ($validated['product_url'] ?? '') !== '' ? $validated['product_url'] : url('/');

        $order = DB::transaction(function () use ($validated, $products, $quantities) {
            $order = new Order([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'delivery_address' => $validated['delivery_address'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'user_id' => Auth::id(),
                'total_amount' => $products->sum(fn (Product $p) => $p->price * $quantities[$p->id]),
            ]);
            $order->save();

            foreach ($products as $product) {
                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantities[$product->id],
                    'price' => $product->price,
                ]);
            }

            return $order;
        });

        if (! $this->yooKassa->isPaymentAvailableFor($order->customer_phone, $order->customer_email)) {
            $this->notifier->notifyNewOrder($order, $productUrl);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.',
            ]);
        }

        // Письмо администратору уходит только после успешной оплаты (см. YooKassaService::syncPayment).
        try {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'payment_url' => $this->yooKassa->createPayment($order),
            ]);
        } catch (\Throwable $e) {
            Log::error('Не удалось создать платёж ЮKassa', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            $order->update(['status' => 'cancelled']);

            return response()->json([
                'success' => false,
                'message' => 'Не удалось перейти к оплате. Попробуйте ещё раз или свяжитесь с нами.',
            ], 502);
        }
    }
}
