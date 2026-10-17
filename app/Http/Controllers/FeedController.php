<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;
use XMLWriter;

class FeedController extends Controller
{
    /** Категория для товаров, у которых категория не задана */
    private const FALLBACK_CATEGORY_ID = 100000;

    private const FALLBACK_CATEGORY_NAME = 'Букеты';

    /** Сколько фото одного товара отдавать в фид */
    private const MAX_PICTURES = 5;

    /**
     * YML-фид товаров для Яндекс Бизнеса и товарных кампаний Директа.
     */
    public function yandex(): Response
    {
        $products = Product::query()
            ->available()
            ->withImages()
            ->with('categories')
            ->orderBy('id')
            ->get();

        $xml = new XMLWriter;
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');

        $xml->startElement('yml_catalog');
        $xml->writeAttribute('date', now()->format('Y-m-d H:i'));
        $xml->startElement('shop');
        $xml->writeElement('name', 'Эдемский сад');
        $xml->writeElement('company', 'Эдемский сад');
        $xml->writeElement('url', url('/'));

        $xml->startElement('currencies');
        $xml->startElement('currency');
        $xml->writeAttribute('id', 'RUR');
        $xml->writeAttribute('rate', '1');
        $xml->endElement();
        $xml->endElement();

        $this->writeCategories($xml);

        $xml->startElement('offers');
        foreach ($products as $product) {
            $this->writeOffer($xml, $product);
        }
        $xml->endElement();

        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();

        return response($xml->outputMemory(), 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=600',
            // Фид нужен только Яндексу, в поиске ему делать нечего
            'X-Robots-Tag' => 'noindex',
        ]);
    }

    private function writeCategories(XMLWriter $xml): void
    {
        $xml->startElement('categories');

        foreach (Category::query()->orderBy('sort_order')->get() as $category) {
            $xml->startElement('category');
            $xml->writeAttribute('id', (string) $category->id);
            $xml->text($category->name);
            $xml->endElement();
        }

        $xml->startElement('category');
        $xml->writeAttribute('id', (string) self::FALLBACK_CATEGORY_ID);
        $xml->text(self::FALLBACK_CATEGORY_NAME);
        $xml->endElement();

        $xml->endElement();
    }

    private function writeOffer(XMLWriter $xml, Product $product): void
    {
        $pictures = $this->pictureUrls($product);

        // Яндекс не принимает товары без фото — такие в фид не попадают
        if ($pictures === []) {
            return;
        }

        $firstCategory = $product->categories->first();

        $xml->startElement('offer');
        $xml->writeAttribute('id', (string) $product->id);
        $xml->writeAttribute('available', 'true');

        $xml->writeElement('name', $product->name);
        $xml->writeElement('url', route('products.show', $product->slug));
        $xml->writeElement('price', (string) (int) $product->price);
        $xml->writeElement('currencyId', 'RUR');
        $xml->writeElement(
            'categoryId',
            (string) ($firstCategory instanceof Category ? $firstCategory->id : self::FALLBACK_CATEGORY_ID)
        );

        foreach ($pictures as $picture) {
            $xml->writeElement('picture', $picture);
        }

        if (filled($product->description)) {
            $xml->writeElement('description', trim(strip_tags($product->description)));
        }

        $xml->endElement();
    }

    /**
     * Абсолютные URL фото на текущем домене.
     * Берём только путь файла: URL из медиатеки зависят от APP_URL
     * и на локальной машине указывали бы на http://localhost.
     *
     * @return list<string>
     */
    private function pictureUrls(Product $product): array
    {
        $urls = [];

        foreach (array_slice($product->image_urls, 0, self::MAX_PICTURES) as $imageUrl) {
            $path = parse_url($imageUrl, PHP_URL_PATH);

            if (is_string($path) && $path !== '') {
                $urls[] = url($path);
            }
        }

        return $urls;
    }
}
