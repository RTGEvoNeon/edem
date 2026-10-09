<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Log;
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
        return $this->paymentUnavailableReason($user) === null;
    }

    /**
     * Причина, по которой оплата недоступна посетителю (null — оплата доступна).
     */
    public function paymentUnavailableReason(?User $user): ?string
    {
        if (! (bool) Setting::get('pay_enabled', false)) {
            return 'pay_enabled выключен в настройках';
        }

        if ((string) Setting::get('yookassa_shop_id', '') === '') {
            return 'не задан yookassa_shop_id';
        }

        if ((string) config('payments.yookassa.secret_key') === '') {
            return 'не задан YOOKASSA_SECRET_KEY в .env (или кеш конфига устарел)';
        }

        if ((bool) Setting::get('pay_test_only', false) && ! (bool) $user?->is_admin) {
            return $user
                ? 'pay_test_only: пользователь не администратор'
                : 'pay_test_only: пользователь не авторизован';
        }

        return null;
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
            'receipt' => $this->buildReceipt($order),
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

        Log::info('Платёж ЮKassa создан', [
            'order_id' => $order->id,
            'yookassa_payment_id' => $response->getId(),
            'status' => $response->getStatus(),
            'amount' => $order->total_amount,
            'has_confirmation_url' => (bool) $confirmationUrl,
        ]);

        if (! $confirmationUrl) {
            throw new RuntimeException('ЮKassa не вернула ссылку для оплаты.');
        }

        return $confirmationUrl;
    }

    /**
     * Чек по 54-ФЗ: патент (tax_system_code 6), без НДС (vat_code 1).
     *
     * @return array<string, mixed>
     */
    public function buildReceipt(Order $order): array
    {
        $customer = ['full_name' => $order->customer_name];

        if ($order->customer_email) {
            $customer['email'] = $order->customer_email;
        } else {
            $customer['phone'] = $this->normalizePhone($order->customer_phone);
        }

        $items = $order->orderItems()->with('product')->get()->map(fn ($item) => [
            'description' => Str::limit($item->product->name, 128, ''),
            'quantity' => (string) $item->quantity,
            'amount' => [
                'value' => number_format((float) $item->price, 2, '.', ''),
                'currency' => 'RUB',
            ],
            'vat_code' => 1,
            'payment_mode' => 'full_payment',
            'payment_subject' => 'commodity',
        ])->all();

        return [
            'customer' => $customer,
            'items' => $items,
            'tax_system_code' => 6,
        ];
    }

    /**
     * Телефон в формате E.164 без «+»: 79991234567.
     */
    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone) ?? '';

        if (strlen($digits) === 11 && $digits[0] === '8') {
            $digits = '7'.substr($digits, 1);
        }

        return $digits;
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

        Log::info('Webhook ЮKassa получен', [
            'event' => $notification->getEvent(),
            'yookassa_payment_id' => $notification->getObject()->getId(),
        ]);

        if (! $isPaymentEvent) {
            return;
        }

        $payment = Payment::query()
            ->where('yookassa_payment_id', $notification->getObject()->getId())
            ->first();

        if (! $payment) {
            Log::warning('Webhook ЮKassa: платёж не найден в базе', [
                'yookassa_payment_id' => $notification->getObject()->getId(),
            ]);

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

        Log::info('Синхронизация платежа ЮKassa', [
            'order_id' => $payment->order_id,
            'yookassa_payment_id' => $payment->yookassa_payment_id,
            'old_status' => $payment->status,
            'new_status' => $actual->getStatus(),
        ]);

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
