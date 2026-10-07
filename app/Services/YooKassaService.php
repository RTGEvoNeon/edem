<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use RuntimeException;
use YooKassa\Client;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Notification\NotificationFactory;
use YooKassa\Model\Payment\Payment as YooKassaPayment;
use YooKassa\Model\Payment\PaymentStatus;

class YooKassaService
{
    public function __construct(private readonly OrderNotifier $notifier) {}

    /**
     * Оплата настроена: включена в настройках, заданы shop_id и секретный ключ.
     */
    public function isPaymentEnabled(): bool
    {
        return (bool) Setting::get('pay_enabled', false)
            && (string) Setting::get('yookassa_shop_id', '') !== ''
            && (string) config('payments.yookassa.secret_key') !== '';
    }

    /**
     * Оплата доступна конкретному посетителю. В тестовом режиме — только администраторам.
     */
    public function isPaymentAvailableFor(?User $user): bool
    {
        if (! $this->isPaymentEnabled()) {
            return false;
        }

        if (! (bool) Setting::get('pay_test_only', false)) {
            return true;
        }

        return (bool) $user?->is_admin;
    }

    /**
     * Создаёт платёж в ЮKassa и возвращает URL, на который нужно перенаправить покупателя.
     */
    public function createPayment(Order $order): string
    {
        if (! $this->isPaymentEnabled()) {
            throw new RuntimeException('Онлайн-оплата отключена в настройках сайта.');
        }

        $idempotenceKey = (string) Str::uuid();

        $response = $this->client()->createPayment([
            'amount' => [
                'value' => number_format((float) $order->total_amount, 2, '.', ''),
                'currency' => 'RUB',
            ],
            'confirmation' => [
                'type' => 'redirect',
                'locale' => 'ru_RU',
                'return_url' => URL::signedRoute('payment.return', ['order' => $order->id]),
            ],
            'capture' => true,
            'description' => "Заказ №{$order->id}",
            'metadata' => [
                'order_id' => $order->id,
            ],
        ], $idempotenceKey);

        $order->payments()->create([
            'yookassa_payment_id' => $response->getId(),
            'idempotence_key' => $idempotenceKey,
            'status' => $response->getStatus(),
            'amount' => $order->total_amount,
            'raw_response' => $response instanceof YooKassaPayment ? $response->jsonSerialize() : [],
        ]);

        $confirmationUrl = $response->getConfirmation()?->getConfirmationUrl();

        if (! $confirmationUrl) {
            throw new RuntimeException('ЮKassa не вернула ссылку для оплаты.');
        }

        return $confirmationUrl;
    }

    public function handleWebhookNotification(array $payload): void
    {
        $factory = new NotificationFactory;
        $notification = $factory->factory($payload);

        $isPaymentEvent = in_array($notification->getEvent(), [
            NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE,
            NotificationEventType::PAYMENT_SUCCEEDED,
            NotificationEventType::PAYMENT_CANCELED,
        ], true);

        if (! $isPaymentEvent) {
            return;
        }

        $payment = Payment::query()
            ->where('yookassa_payment_id', $notification->getObject()->getId())
            ->first();

        if (! $payment) {
            return;
        }

        $this->syncPayment($payment);
    }

    /**
     * Не доверяем статусу из уведомления или из браузера — перепроверяем платёж напрямую через API
     * и обновляем платёж и заказ.
     */
    public function syncPayment(Payment $payment): Payment
    {
        $actual = $this->client()->getPaymentInfo($payment->yookassa_payment_id);

        $payment->update([
            'status' => $actual->getStatus(),
            'raw_response' => $actual instanceof YooKassaPayment ? $actual->jsonSerialize() : [],
        ]);

        $order = $payment->order;

        if ($actual->getStatus() === PaymentStatus::SUCCEEDED && $order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
            $this->notifier->notifyNewOrder($order);
        }

        // Отмена одной попытки не отменяет заказ, если есть другой успешный платёж.
        if ($actual->getStatus() === PaymentStatus::CANCELED
            && $order->status === 'pending'
            && ! $order->payments()->where('status', PaymentStatus::SUCCEEDED)->exists()) {
            $order->update(['status' => 'cancelled']);
        }

        return $payment->refresh();
    }

    public function isNotificationIpTrusted(string $ip): bool
    {
        return $this->client()->isNotificationIPTrusted($ip);
    }

    private function client(): Client
    {
        $client = new Client;
        $client->setAuth(
            (int) Setting::get('yookassa_shop_id', 0),
            (string) config('payments.yookassa.secret_key')
        );

        return $client;
    }
}
