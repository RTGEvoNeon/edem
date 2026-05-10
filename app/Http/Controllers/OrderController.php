<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\VKService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected VKService $vkService;

    public function __construct(VKService $vkService)
    {
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
            'product_url' => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();
        if ($user) {
            $validated['user_id'] = $user->id;
        }

        $productUrl = $validated['product_url'] ?? $request->input('product_url');
        $validated['product_url'] = is_string($productUrl) && $productUrl !== '' ? $productUrl : url('/');

        $order = new Order($validated);
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.',
        ]);
    }
}
