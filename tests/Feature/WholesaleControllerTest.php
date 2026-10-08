<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\WholesaleProduct;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
            'price_tier_1' => 50,
            'price_tier_2' => 45,
            'price_tier_3' => 40,
        ]);

        // Act & Assert
        $this->assertEquals(50, $product->getPriceForQuantity(1000));
        $this->assertEquals(50, $product->getPriceForQuantity(4999));
        $this->assertEquals(45, $product->getPriceForQuantity(5000));
        $this->assertEquals(45, $product->getPriceForQuantity(9999));
        $this->assertEquals(40, $product->getPriceForQuantity(10000));
        $this->assertEquals(40, $product->getPriceForQuantity(50000));
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
}
