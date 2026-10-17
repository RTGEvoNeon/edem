<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class YandexFeedTest extends TestCase
{
    use RefreshDatabase;

    private function productWithPhoto(array $attributes = []): Product
    {
        $product = Product::factory()->create($attributes + ['is_available' => true, 'price' => 2500]);
        $product->addMedia(UploadedFile::fake()->image('photo.jpg', 600, 600))
            ->toMediaCollection(Product::PHOTOS_COLLECTION);

        return $product->refresh();
    }

    /**
     * @return array<int, \SimpleXMLElement>
     */
    private function offers(string $content): array
    {
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, 'Фид должен быть валидным XML');

        return $xml->xpath('//offer') ?: [];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_feed_returns_valid_yml_with_available_product_with_photo(): void
    {
        $category = Category::factory()->create(['name' => 'Розы & пионы']);
        $product = $this->productWithPhoto(['name' => 'Букет <Нежность>', 'description' => 'Нежный букет & зелень']);
        $product->categories()->attach($category->id);

        $response = $this->get('/feed/yandex.yml');

        $response->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex');
        $this->assertStringContainsString('application/xml', (string) $response->headers->get('Content-Type'));

        $offers = $this->offers($response->getContent());
        $this->assertCount(1, $offers);

        $offer = $offers[0];
        $this->assertSame((string) $product->id, (string) $offer['id']);
        $this->assertSame('Букет <Нежность>', (string) $offer->name);
        $this->assertSame('2500', (string) $offer->price);
        $this->assertSame('RUR', (string) $offer->currencyId);
        $this->assertSame((string) $category->id, (string) $offer->categoryId);
        $this->assertSame(route('products.show', $product->slug), (string) $offer->url);
        $this->assertSame('Нежный букет & зелень', (string) $offer->description);
    }

    public function test_picture_urls_use_current_domain_not_localhost(): void
    {
        $this->productWithPhoto();
        config(['app.url' => 'http://localhost']);

        $content = $this->get('http://shop.example.ru/feed/yandex.yml')->getContent();

        $picture = (string) $this->offers($content)[0]->picture;
        $this->assertStringStartsWith('http://shop.example.ru/storage/', $picture);
        $this->assertStringNotContainsString('localhost', $picture);
    }

    public function test_products_without_photo_or_unavailable_are_excluded(): void
    {
        $this->productWithPhoto(['name' => 'С фото']);
        Product::factory()->create(['name' => 'Без фото', 'is_available' => true]);
        $this->productWithPhoto(['name' => 'Недоступный', 'is_available' => false]);

        $names = array_map(fn ($offer) => (string) $offer->name, $this->offers($this->get('/feed/yandex.yml')->getContent()));

        $this->assertSame(['С фото'], $names);
    }

    public function test_product_without_category_gets_fallback_category(): void
    {
        $this->productWithPhoto();

        $content = $this->get('/feed/yandex.yml')->getContent();
        $xml = simplexml_load_string($content);

        $this->assertSame('100000', (string) $this->offers($content)[0]->categoryId);
        $this->assertCount(1, $xml->xpath('//category[@id="100000"]'));
    }
}
