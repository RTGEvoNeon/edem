<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\YooKassaService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class YooKassaReceiptTest extends TestCase
{
    use DatabaseTransactions;

    private function orderWith(array $attributes): Order
    {
        $product = Product::factory()->create(['name' => 'Букет Нежность', 'price' => 1500, 'is_available' => true]);
        $order = Order::factory()->create($attributes + ['total_amount' => 3000]);
        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 1500,
        ]);

        return $order;
    }

    public function test_receipt_uses_email_when_present(): void
    {
        $order = $this->orderWith(['customer_name' => 'Илья', 'customer_email' => 'a@b.ru', 'customer_phone' => '+7 (996) 449-36-57']);

        $receipt = app(YooKassaService::class)->buildReceipt($order);

        $this->assertSame(['full_name' => 'Илья', 'email' => 'a@b.ru'], $receipt['customer']);
        $this->assertSame(6, $receipt['tax_system_code']);
        $this->assertSame('Букет Нежность', $receipt['items'][0]['description']);
        $this->assertSame('2', $receipt['items'][0]['quantity']);
        $this->assertSame('1500.00', $receipt['items'][0]['amount']['value']);
        $this->assertSame(1, $receipt['items'][0]['vat_code']);
    }

    public function test_receipt_falls_back_to_normalized_phone(): void
    {
        $order = $this->orderWith(['customer_email' => null, 'customer_phone' => '8 (996) 449-36-57']);

        $receipt = app(YooKassaService::class)->buildReceipt($order);

        $this->assertSame('79964493657', $receipt['customer']['phone']);
        $this->assertArrayNotHasKey('email', $receipt['customer']);
    }
}
