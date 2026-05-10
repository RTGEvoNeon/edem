<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Throwable;

class VKService
{
    protected array $phones;

    public function __construct()
   
    }

    public function sendOrderMessage(Order $order, string $productUrl): void
    {
        try {
            $message = "🌸 <b>Новый заказ!</b>\n\n";
            $message .= '<b>Букет:</b> '.$productUrl."\n\n";
            $message .= '👤 <b>Имя:</b> '.$order->customer_name."\n";
            $message .= '📱 <b>Телефон:</b> '.$order->customer_phone."\n";
            $message .= '📍 <b>Адрес:</b> '.$order->delivery_address."\n";
            $message .= '💬 <b>Комментарий:</b> '.$order->notes."\n";
            $message .= '💰 <b>Сумма заказа:</b> '.number_format((float) $order->total_amount, 0, ',', ' ')." руб.\n";
            send_message($message);
        } catch (Throwable $e) {
            Log::error('Не удалось отправить SMS менеджеру', [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}