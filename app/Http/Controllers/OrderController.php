<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\VKService;
class OrderController extends Controller
{
    protected TelegramService $telegramService;
    protected VKService $vkService;

    public function __construct(TelegramService $telegramService, VKService $vkService)
    {
        $this->telegramService = $telegramService;
        $this->vkService = $vkService;
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'total_amount' => 'required|numeric|min:0',
        ]);
        
        $user = Auth::user();
        if ($user) {
            $validated['user_id'] = $user->id;
        }

        $order = new Order($validated);
        $order->save();

        $productUrl = $request->input('product_url', url('/'));

        $this->telegramService->sendOrderMessage($order, $productUrl);
        $this->vkService->sendOrderMessage($order, $productUrl);

        return response()->json([
            'success' => true,
            'message' => 'Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.',
        ]);
    }
}
