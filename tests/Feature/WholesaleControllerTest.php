<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\WholesaleOrderMail;
use App\Models\WholesaleProduct;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WholesaleControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_wholesale_index_page_displays_products(): void
    {
        // Arrange
        WholesaleProduct::factory()->count(5)->create();

        // Act
        $response = $this->get('/opt');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('wholesale.index');
        $response->assertViewHas('products');
    }

    public function test_wholesale_show_page_displays_product(): void
    {
        // Arrange
        $product = WholesaleProduct::factory()->create([
            'name' => 'Тюльпаны красные',
            'slug' => 'tyulpany-krasnye',
            'is_available' => true,
        ]);

        // Act
        $response = $this->get('/opt/'.$product->slug);

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('wholesale.show');
        $response->assertViewHas('product');
        $response->assertSee('Тюльпаны красные');
    }

    public function test_wholesale_show_returns_404_for_unavailable_product(): void
    {
        // Arrange
        $product = WholesaleProduct::factory()->create([
            'slug' => 'unavailable-product',
            'is_available' => false,
        ]);

        // Act
        $response = $this->get('/opt/'.$product->slug);

        // Assert
        $response->assertStatus(404);
    }

    public function test_product_price_calculator_returns_correct_tier(): void
    {
        // Arrange
        $product = WholesaleProduct::factory()->create([
            'price_tier_1' => 59,
            'price_tier_2' => 58,
            'price_tier_3' => 57,
            'price_tier_4' => 56,
            'price_tier_5' => 55,
            'price_tier_6' => 54,
        ]);

        // Act & Assert
        $this->assertEquals(59, $product->getPriceForQuantity(1000));
        $this->assertEquals(59, $product->getPriceForQuantity(5000));
        $this->assertEquals(58, $product->getPriceForQuantity(5020));
        $this->assertEquals(58, $product->getPriceForQuantity(10000));
        $this->assertEquals(57, $product->getPriceForQuantity(10020));
        $this->assertEquals(57, $product->getPriceForQuantity(30000));
        $this->assertEquals(56, $product->getPriceForQuantity(30020));
        $this->assertEquals(56, $product->getPriceForQuantity(50000));
        $this->assertEquals(55, $product->getPriceForQuantity(50020));
        $this->assertEquals(55, $product->getPriceForQuantity(100000));
        $this->assertEquals(54, $product->getPriceForQuantity(100020));
        $this->assertEquals(54, $product->min_price);
    }

    public function test_wholesale_order_validation_requires_minimum_quantity(): void
    {
        // Arrange
        $product = WholesaleProduct::factory()->create(['slug' => 'test-product']);

        // Act
        $response = $this->postJson('/opt/order/submit', [
            'product_slug' => 'test-product',
            'quantity' => 500, // Less than minimum (1000)
            'customer_name' => 'Test Customer',
            'customer_phone' => '+7 999 123-45-67',
        ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['quantity']);
    }

    public function test_wholesale_order_submission_succeeds_with_valid_data(): void
    {
        // Arrange
        $product = WholesaleProduct::factory()->create([
            'slug' => 'test-product',
            'is_available' => true,
        ]);

        // Act
        $response = $this->postJson('/opt/order/submit', [
            'product_slug' => 'test-product',
            'quantity' => 5000,
            'customer_name' => 'Test Customer',
            'customer_phone' => '+7 999 123-45-67',
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_wholesale_order_sends_email_to_admins(): void
    {
        // Arrange
        Mail::fake();
        config(['mail.admin_emails' => ['admin@example.com']]);
        WholesaleProduct::factory()->create(['slug' => 'test-product', 'name' => 'Outlook']);

        // Act
        $this->postJson('/opt/order/submit', [
            'product_slug' => 'test-product',
            'quantity' => 6000,
            'customer_name' => 'Test Customer',
            'customer_phone' => '+7 999 123-45-67',
        ])->assertOk();

        // Assert
        Mail::assertSent(WholesaleOrderMail::class, function (WholesaleOrderMail $mail) {
            return $mail->hasTo('admin@example.com')
                && $mail->quantity === 6000
                && $mail->pricePerUnit === 58.0
                && $mail->total === 348000.0;
        });
    }
}
