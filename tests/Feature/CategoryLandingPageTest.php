<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryLandingPageTest extends TestCase
{
    use RefreshDatabase;

    private function categoryWithProduct(string $key, string $name, string $productName = 'Тестовый букет'): Category
    {
        // Часть ключей (wedding, mono…) уже создана data-migration — берём существующую запись
        $category = Category::query()->firstOrCreate(['key' => $key], ['name' => $name]);
        $product = Product::factory()->create(['name' => $productName, 'is_available' => true]);
        $product->categories()->attach($category->id);

        return $category;
    }

    public function test_landing_page_shows_products_h1_and_canonical(): void
    {
        $this->categoryWithProduct('wedding', 'Свадебные', 'Букет невесты Нежность');

        $response = $this->get('/bukety/wedding');

        $response->assertOk()
            ->assertSee('Букет невесты Нежность')
            ->assertSee('Свадебные букеты и букеты невесты в Брянске')
            ->assertSee('<link rel="canonical" href="'.url('/bukety/wedding').'">', false)
            ->assertSee('<title>Свадебные букеты в Брянске — букет невесты — Эдемский сад</title>', false)
            ->assertDontSee('content="noindex', false);
    }

    public function test_unknown_category_returns_404(): void
    {
        $this->get('/bukety/does-not-exist')->assertNotFound();
    }

    public function test_category_without_seo_texts_gets_default_title_and_no_seo_block(): void
    {
        $this->categoryWithProduct('no-texts-test', 'Особая подборка');

        $response = $this->get('/bukety/no-texts-test');

        $response->assertOk()
            ->assertSee('<title>Особая подборка — купить в Брянске — Эдемский сад</title>', false)
            ->assertDontSee('Каталог букетов с доставкой по Брянску');
    }

    public function test_empty_category_is_not_indexed(): void
    {
        Category::factory()->create(['key' => 'empty-test', 'name' => 'Пустая']);

        $this->get('/bukety/empty-test')
            ->assertOk()
            ->assertSee('content="noindex, follow"', false);
    }

    public function test_legacy_query_url_redirects_permanently_to_landing_page(): void
    {
        Category::factory()->create(['key' => 'legacy-test', 'name' => 'Старая']);

        $this->get('/products?category=legacy-test')
            ->assertStatus(301)
            ->assertRedirect(url('/bukety/legacy-test'));

        $this->get('/products?category=legacy-test&page=2')
            ->assertStatus(301)
            ->assertRedirect(url('/bukety/legacy-test?page=2'));
    }

    public function test_main_catalog_does_not_redirect_and_all_param_is_ignored(): void
    {
        $this->get('/products')->assertOk();
        $this->get('/products?category=all')->assertOk();
    }

    public function test_sitemap_contains_only_categories_with_available_products(): void
    {
        $this->categoryWithProduct('in-sitemap-test', 'Есть товары');
        Category::factory()->create(['key' => 'not-in-sitemap-test', 'name' => 'Нет товаров']);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertSee('/bukety/in-sitemap-test', false)
            ->assertDontSee('/bukety/not-in-sitemap-test', false);
    }

    public function test_product_page_breadcrumbs_include_category(): void
    {
        $category = $this->categoryWithProduct('crumb-test', 'Крошки', 'Букет для крошек');
        $product = Product::query()->where('name', 'Букет для крошек')->firstOrFail();

        $this->get('/product/'.$product->slug)
            ->assertOk()
            ->assertSee(url('/bukety/'.$category->key), false);
    }
}
