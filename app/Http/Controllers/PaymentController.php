<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\YooKassaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly YooKassaService $yooKassa) {}

    /**
     * Страница возврата из ЮKassa (ссылка подписана, доступна только по ссылке из платежа).
     */
    public function return(Order $order): View
    {
        $payment = $order->payment;

        Log::info('Возврат из ЮKassa', [
            'order_id' => $order->id,
            'order_status' => $order->status,
            'payment_status' => $payment?->status,
        ]);

        // Не ждём вебхук: если платёж ещё в обработке, уточняем статус у ЮKassa.
        if ($payment && in_array($payment->status, ['pending', 'waiting_for_capture'], true)) {
            try {
                $payment = $this->yooKassa->syncPayment($payment);
                $order->refresh();
            } catch (\Throwable $e) {
                Log::warning('Не удалось уточнить статус платежа ЮKassa', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $state = match (true) {
            $order->status !== 'pending' && $order->status !== 'cancelled' => 'succeeded',
            $payment?->status === 'canceled' || $order->status === 'cancelled' => 'canceled',
            default => 'pending',
        };

        return view('payment.return', [
            'order' => $order,
            'state' => $state,
            'retryUrl' => URL::signedRoute('payment.retry', ['order' => $order->id]),
        ]);
    }

    /**
     * Повторная попытка оплаты неоплаченного заказа.
     */
    public function retry(Order $order): RedirectResponse
    {
        Log::info('Повторная попытка оплаты', ['order_id' => $order->id, 'order_status' => $order->status]);

        if ($order->status !== 'pending' && $order->status !== 'cancelled') {
            return redirect(URL::signedRoute('payment.return', ['order' => $order->id]));
        }

        try {
            $order->update(['status' => 'pending']);

            return redirect()->away($this->yooKassa->createPayment($order));
        } catch (\Throwable $e) {
            Log::error('Не удалось повторно создать платёж ЮKassa', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            $order->update(['status' => 'cancelled']);

            return redirect(URL::signedRoute('payment.return', ['order' => $order->id]))
                ->with('error', 'Не удалось перейти к оплате. Попробуйте ещё раз позже.');
        }
    }
}
