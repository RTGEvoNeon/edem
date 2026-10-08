<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\NewOrderMail;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Services\YooKassaService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Mockery\MockInterface;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use DatabaseTransactions;

    private function payload(): array
    {
        $product = Product::factory()->create(['price' => 100, 'is_available' => true]);

        return [
            'customer_name' => 'Иван',
            'customer_phone' => '+7 999 000 00 00',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ];
    }

    private function enablePayments(): void
    {
        Setting::set('pay_enabled', true);
        Setting::set('yookassa_shop_id', 'test-shop-id');
        config(['payments.yookassa.secret_key' => 'secret']);
    }

    public function test_payment_is_disabled_without_shop_id_or_secret_key(): void
    {
        Setting::set('pay_enabled', true);
        $service = app(YooKassaService::class);

        $this->assertFalse($service->isPaymentEnabled());

        Setting::set('yookassa_shop_id', 'shop');
        config(['payments.yookassa.secret_key' => '']);
        $this->assertFalse($service->isPaymentEnabled());

        config(['payments.yookassa.secret_key' => 'secret']);
        $this->assertTrue($service->isPaymentEnabled());
    }

    public function test_test_mode_allows_payment_only_for_admins(): void
    {
        $this->enablePayments();
        Setting::set('pay_test_only', true);
        $service = app(YooKassaService::class);

        $this->assertTrue($service->isPaymentAvailableFor(User::factory()->create(['is_admin' => true])));
        $this->assertFalse($service->isPaymentAvailableFor(User::factory()->create(['is_admin' => false])));
        $this->assertFalse($service->isPaymentAvailableFor(null));

        Setting::set('pay_test_only', false);
        $this->assertTrue($service->isPaymentAvailableFor(null));
    }

    public function test_order_submit_does_not_create_payment_when_pay_enabled_is_false(): void
    {
        Mail::fake();
        config(['mail.admin_emails' => ['admin@example.com']]);
        Setting::set('pay_enabled', false);

        $response = $this->postJson('/order/submit', $this->payload());

        $response->assertOk()->assertJson(['success' => true]);
        $response->assertJsonMissing(['payment_url']);

        $this->assertDatabaseCount('payments', 0);
        Mail::assertSent(NewOrderMail::class);
    }

    public function test_order_submit_returns_confirmation_url_and_defers_admin_email_until_paid(): void
    {
        Mail::fake();
        config(['mail.admin_emails' => ['admin@example.com']]);

        $this->mock(YooKassaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('isPaymentAvailableFor')->andReturn(true);
            $mock->shouldReceive('createPayment')
                ->once()
                ->andReturn('https://yookassa.ru/checkout/payments/v2/contract?orderId=test');
        });

        $this->postJson('/order/submit', $this->payload())->assertOk()->assertJson([
            'success' => true,
            'payment_url' => 'https://yookassa.ru/checkout/payments/v2/contract?orderId=test',
        ]);

        Mail::assertNothingSent();
    }

    public function test_order_submit_returns_error_and_cancels_order_when_payment_creation_fails(): void
    {
        Mail::fake();

        $this->mock(YooKassaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('isPaymentAvailableFor')->andReturn(true);
            $mock->shouldReceive('createPayment')
                ->once()
                ->andThrow(new \RuntimeException('ЮKassa недоступна'));
        });

        $response = $this->postJson('/order/submit', $this->payload());

        $response->assertStatus(502)->assertJson(['success' => false]);
        $response->assertJsonMissing(['payment_url']);
        $this->assertSame('cancelled', Order::firstOrFail()->status);
        Mail::assertNothingSent();
    }

    public function test_return_page_requires_signed_url(): void
    {
        $order = Order::factory()->create();

        $this->get('/payment/return/'.$order->id)->assertForbidden();
    }

    public function test_return_page_shows_paid_state_for_confirmed_order(): void
    {
        $order = Order::factory()->create(['status' => 'confirmed']);

        $this->get(URL::signedRoute('payment.return', ['order' => $order->id]))
            ->assertOk()
            ->assertSee('оплачен');
    }

    public function test_return_page_syncs_pending_payment_with_yookassa(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $payment = Payment::create([
            'order_id' => $order->id,
            'yookassa_payment_id' => 'pay-1',
            'idempotence_key' => 'key-1',
            'status' => 'pending',
            'amount' => $order->total_amount,
        ]);

        $this->mock(YooKassaService::class, function (MockInterface $mock) use ($order, $payment) {
            $mock->shouldReceive('syncPayment')->once()->andReturnUsing(function () use ($order, $payment) {
                $order->update(['status' => 'confirmed']);
                $payment->update(['status' => 'succeeded']);

                return $payment->refresh();
            });
        });

        $this->get(URL::signedRoute('payment.return', ['order' => $order->id]))
            ->assertOk()
            ->assertSee('оплачен');
    }

    public function test_return_page_offers_retry_when_payment_canceled(): void
    {
        $order = Order::factory()->create(['status' => 'cancelled']);
        Payment::create([
            'order_id' => $order->id,
            'yookassa_payment_id' => 'pay-2',
            'idempotence_key' => 'key-2',
            'status' => 'canceled',
            'amount' => $order->total_amount,
        ]);

        $this->get(URL::signedRoute('payment.return', ['order' => $order->id]))
            ->assertOk()
            ->assertSee('Оплата не прошла')
            ->assertSee('Оплатить ещё раз');
    }

    public function test_retry_creates_new_payment_and_redirects(): void
    {
        $order = Order::factory()->create(['status' => 'cancelled']);

        $this->mock(YooKassaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('createPayment')->once()->andReturn('https://yookassa.ru/pay/retry');
        });

        $this->get(URL::signedRoute('payment.retry', ['order' => $order->id]))
            ->assertRedirect('https://yookassa.ru/pay/retry');

        $this->assertSame('pending', $order->refresh()->status);
    }

    public function test_cart_page_and_products_endpoint(): void
    {
        $product = Product::factory()->create(['is_available' => true, 'price' => 500]);
        $hidden = Product::factory()->create(['is_available' => false]);

        $this->get('/cart')->assertOk();

        $this->getJson('/cart/products?ids='.$product->id.','.$hidden->id)
            ->assertOk()
            ->assertJsonCount(1, 'products')
            ->assertJsonPath('products.0.id', $product->id);
    }
}
