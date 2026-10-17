<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\NewOrderMail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    private function items(int $price = 1500, int $quantity = 1): array
    {
        $product = Product::factory()->create(['price' => $price, 'is_available' => true]);

        return [['product_id' => $product->id, 'quantity' => $quantity]];
    }

    public function test_submit_creates_order_and_sends_email_to_admin(): void
    {
        Mail::fake();
        config(['mail.admin_emails' => ['admin@example.com']]);

        $response = $this->postJson('/order/submit', [
            'customer_name' => 'Иван Иванов',
            'customer_phone' => '+7 (999) 123-45-67',
            'delivery_address' => 'г. Москва, ул. Тестовая, д. 1',
            'notes' => 'Позвонить после 18:00',
            'items' => $this->items(),
            'product_url' => 'https://эдемский-сад.рф/product/test',
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Иван Иванов',
            'customer_phone' => '+7 (999) 123-45-67',
        ]);

        $order = Order::firstOrFail();

        Mail::assertSent(NewOrderMail::class, function (NewOrderMail $mail) use ($order) {
            return $mail->order->is($order)
                && $mail->productUrl === 'https://эдемский-сад.рф/product/test'
                && $mail->hasTo('admin@example.com');
        });
    }

    public function test_submit_sends_email_to_multiple_admins(): void
    {
        Mail::fake();
        config(['mail.admin_emails' => [
            'manager1@example.com',
            'manager2@example.com',
            'manager3@example.com',
        ]]);

        $this->postJson('/order/submit', [
            'customer_name' => 'Иван',
            'customer_phone' => '+7 999 000 00 00',
            'items' => $this->items(),
        ])->assertOk();

        Mail::assertSent(NewOrderMail::class, function (NewOrderMail $mail) {
            return $mail->hasTo('manager1@example.com')
                && $mail->hasTo('manager2@example.com')
                && $mail->hasTo('manager3@example.com');
        });
    }

    public function test_submit_uses_site_root_when_product_url_is_empty(): void
    {
        Mail::fake();
        config(['mail.admin_emails' => ['admin@example.com']]);

        $this->postJson('/order/submit', [
            'customer_name' => 'Иван',
            'customer_phone' => '+7 999 000 00 00',
            'items' => $this->items(),
        ])->assertOk();

        Mail::assertSent(NewOrderMail::class, function (NewOrderMail $mail) {
            return $mail->productUrl === url('/');
        });
    }

    public function test_submit_does_not_send_email_when_admin_email_is_empty(): void
    {
        Mail::fake();
        config(['mail.admin_emails' => []]);

        $response = $this->postJson('/order/submit', [
            'customer_name' => 'Иван',
            'customer_phone' => '+7 999 000 00 00',
            'items' => $this->items(),
        ]);

        $response->assertOk();
        $this->assertDatabaseCount('orders', 1);

        Mail::assertNothingSent();
    }

    public function test_submit_returns_success_even_if_mail_sending_fails(): void
    {
        config(['mail.admin_emails' => ['admin@example.com']]);

        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('SMTP недоступен'));

        $response = $this->postJson('/order/submit', [
            'customer_name' => 'Иван',
            'customer_phone' => '+7 999 000 00 00',
            'items' => $this->items(),
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseCount('orders', 1);
    }

    public function test_submit_validates_required_fields(): void
    {
        $response = $this->postJson('/order/submit', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_name', 'customer_phone', 'items']);
    }

    public function test_new_order_mail_renders_with_order_data(): void
    {
        $order = new Order([
            'customer_name' => 'Пётр Петров',
            'customer_phone' => '+7 (911) 222-33-44',
            'delivery_address' => 'СПб, Невский 1',
            'notes' => 'Хрупкое',
            'total_amount' => '2500.00',
        ]);
        $order->id = 42;
        $order->created_at = now();

        $mailable = new NewOrderMail($order, 'https://эдемский-сад.рф/product/x');

        $mailable->assertHasSubject('Новая заявка с сайта №42');
        $mailable->assertSeeInHtml('Пётр Петров');
        $mailable->assertSeeInHtml('+7 (911) 222-33-44');
        $mailable->assertSeeInHtml('СПб, Невский 1');
        $mailable->assertSeeInHtml('Хрупкое');
        $mailable->assertSeeInHtml('2500.00');
        $mailable->assertSeeInHtml('https://эдемский-сад.рф/product/x');
    }

    public function test_submit_calculates_total_on_server_and_saves_items(): void
    {
        Mail::fake();

        $a = Product::factory()->create(['price' => 1000, 'is_available' => true]);
        $b = Product::factory()->create(['price' => 250, 'is_available' => true]);

        $this->postJson('/order/submit', [
            'customer_name' => 'Иван',
            'customer_phone' => '+7 999 000 00 00',
            'customer_email' => 'ivan@example.com',
            'total_amount' => 1,
            'items' => [
                ['product_id' => $a->id, 'quantity' => 2],
                ['product_id' => $b->id, 'quantity' => 1],
            ],
        ])->assertOk();

        $order = Order::firstOrFail();
        $this->assertSame('2250.00', $order->total_amount);
        $this->assertSame('ivan@example.com', $order->customer_email);
        $this->assertDatabaseCount('order_items', 2);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'product_id' => $a->id, 'quantity' => 2, 'price' => 1000]);
    }

    public function test_submit_rejects_unavailable_or_unknown_products(): void
    {
        $product = Product::factory()->create(['is_available' => false]);

        $this->postJson('/order/submit', [
            'customer_name' => 'Иван',
            'customer_phone' => '+7 999 000 00 00',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ])->assertStatus(422)->assertJson(['success' => false]);

        $this->assertDatabaseCount('orders', 0);
    }
}
