<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use App\Models\WholesaleProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoImprovementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_wholesale_pages_describe_tulips_location_and_price_threshold(): void
    {
        $product = WholesaleProduct::factory()->create(['name' => 'Outlook']);

        $this->get('/opt')
            ->assertOk()
            ->assertSee('<title>Тюльпаны оптом в Брянске — прайс и каталог сортов — Эдемский сад</title>', false)
            ->assertSee('Прайс на тюльпаны оптом')
            ->assertSee('Как купить тюльпаны оптом');

        $response = $this->get('/opt/'.$product->slug)
            ->assertOk()
            ->assertSee('<title>Тюльпаны Outlook оптом в Брянске — цены — Эдемский сад</title>', false)
            ->assertSee('Тюльпаны Outlook оптом');

        $schema = $this->productSchema($response->getContent());
        $this->assertSame('opt-'.$product->id, $schema['sku']);
        $this->assertSame(54, $schema['offers']['price']);
        $this->assertSame(100020, $schema['offers']['eligibleQuantity']['minValue']);
        $this->assertArrayNotHasKey('brand', $schema);
    }

    public function test_retail_product_schema_has_sku_brand_and_condition(): void
    {
        $product = Product::factory()->create(['is_available' => true]);

        $response = $this->get('/product/'.$product->slug)->assertOk();
        $schema = $this->productSchema($response->getContent());

        $this->assertSame((string) $product->id, $schema['sku']);
        $this->assertSame('Эдемский сад', $schema['brand']['name']);
        $this->assertSame('https://schema.org/NewCondition', $schema['offers']['itemCondition']);
        $this->assertArrayNotHasKey('eligibleQuantity', $schema['offers']);
    }

    public function test_catalog_trailing_slashes_redirect_and_preserve_tracking_parameters(): void
    {
        foreach (['products', 'bukety/wedding', 'product/test-bouquet', 'opt', 'opt/outlook'] as $path) {
            $this->get('http://localhost/'.$path.'/?utm_source=yandex&page=2')
                ->assertStatus(301)
                ->assertRedirect(url('/'.$path).'?utm_source=yandex&page=2');
        }

        $this->head('http://localhost/opt/?check=1')->assertStatus(301)->assertRedirect(url('/opt').'?check=1');
        $this->get('/')->assertOk();
    }

    public function test_cart_is_not_indexable(): void
    {
        $this->get('/cart')->assertOk()->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_wholesale_draft_with_zero_price_is_not_published_or_in_sitemap(): void
    {
        $product = WholesaleProduct::factory()->create([
            'name' => 'Черновик прайса',
            'slug' => 'price-draft',
            'price_tier_6' => 0,
        ]);

        $this->get('/opt')->assertOk()->assertDontSee($product->name);
        $this->get('/opt/'.$product->slug)->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()->assertDontSee('/opt/'.$product->slug, false);
    }

    private function productSchema(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        foreach ($matches[1] as $json) {
            $schema = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
            if (($schema['@type'] ?? null) === 'Product') {
                return $schema;
            }
        }

        $this->fail('Product schema is missing.');
    }
}
