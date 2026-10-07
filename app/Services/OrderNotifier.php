<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\NewOrderMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderNotifier
{
    /**
     * Отправляет администраторам письмо о новом заказе. Ошибки отправки не прерывают оформление.
     */
    public function notifyNewOrder(Order $order, ?string $productUrl = null): void
    {
        $adminEmails = (array) config('mail.admin_emails', []);

        if ($adminEmails === []) {
            return;
        }

        try {
            Mail::to($adminEmails)->send(new NewOrderMail($order, $productUrl ?: url('/')));
        } catch (\Throwable $e) {
            Log::error('Не удалось отправить письмо о новом заказе', [
                'order_id' => $order->id,
                'recipients' => $adminEmails,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
