<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\WholesaleProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    /**
     * Базовый URL в Punycode для международного домена
     * эдемский-сад.рф = xn----8sbkccshgr4ce9k.xn--p1ai
     */
    private const BASE_URL = 'https://xn----8sbkccshgr4ce9k.xn--p1ai';

    /**
     * Дата последнего содержательного изменения статических страниц
     * (delivery, about, contacts, privacy). Обновлять вручную при правке текста.
     */
    private const STATIC_PAGES_LASTMOD = '2026-08-23T00:00:00+00:00';

    /**
     * Генерирует URL в Punycode формате
     */
    private function punycodeUrl(string $path = ''): string
    {
        return self::BASE_URL.$path;
    }

    public function index()
    {
        // Получаем только доступные товары (розница и опт)
        $products = Product::available()->get();
        $wholesaleProducts = WholesaleProduct::available()->get();

        $catalogLastmod = $products->max('updated_at') ?? now();

        // Статические страницы сайта
        $staticPages = [
            [
                'url' => $this->punycodeUrl('/'),
                'lastmod' => $catalogLastmod->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'url' => $this->punycodeUrl('/products'),
                'lastmod' => $catalogLastmod->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'url' => $this->punycodeUrl('/opt'),
                'lastmod' => ($wholesaleProducts->max('updated_at') ?? now())->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.85',
            ],
            [
                'url' => $this->punycodeUrl('/delivery'),
                'lastmod' => self::STATIC_PAGES_LASTMOD,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'url' => $this->punycodeUrl('/about'),
                'lastmod' => self::STATIC_PAGES_LASTMOD,
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'url' => $this->punycodeUrl('/contacts'),
                'lastmod' => self::STATIC_PAGES_LASTMOD,
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ],
            [
                'url' => $this->punycodeUrl('/privacy'),
                'lastmod' => self::STATIC_PAGES_LASTMOD,
                'changefreq' => 'yearly',
                'priority' => '0.3',
            ],
        ];

        // Генерируем XML
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" '
            .'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

        // Добавляем статические страницы
        foreach ($staticPages as $page) {
            $sitemap .= "  <url>\n";
            $sitemap .= "    <loc>{$page['url']}</loc>\n";
            $sitemap .= "    <lastmod>{$page['lastmod']}</lastmod>\n";
            $sitemap .= "    <changefreq>{$page['changefreq']}</changefreq>\n";
            $sitemap .= "    <priority>{$page['priority']}</priority>\n";
            $sitemap .= "  </url>\n";
        }

        // Посадочные страницы категорий (только с доступными товарами)
        $categories = Category::query()
            ->whereHas('products', function (Builder $query): Builder {
                /** @var Builder<Product> $query */
                return $query->available();
            })
            ->orderBy('sort_order')
            ->get();

        foreach ($categories as $category) {
            $lastmod = Product::available()
                ->whereHas('categories', fn ($query) => $query->where('categories.id', $category->id))
                ->max('updated_at') ?? $catalogLastmod;

            $sitemap .= "  <url>\n";
            $sitemap .= '    <loc>'.$this->punycodeUrl('/bukety/'.rawurlencode($category->key))."</loc>\n";
            $sitemap .= '    <lastmod>'.Carbon::parse($lastmod)->toAtomString()."</lastmod>\n";
            $sitemap .= "    <changefreq>weekly</changefreq>\n";
            $sitemap .= "    <priority>0.9</priority>\n";
            $sitemap .= "  </url>\n";
        }

        // Добавляем товары (розница)
        foreach ($products as $product) {
            $sitemap .= "  <url>\n";
            $sitemap .= '    <loc>'.$this->punycodeUrl('/product/'.$product->slug)."</loc>\n";
            $sitemap .= '    <lastmod>'.$product->updated_at->toAtomString()."</lastmod>\n";
            $sitemap .= "    <changefreq>weekly</changefreq>\n";
            $sitemap .= "    <priority>0.8</priority>\n";

            foreach ($product->image_urls as $imageUrl) {
                $sitemap .= "    <image:image>\n";
                $sitemap .= '      <image:loc>'.e($imageUrl)."</image:loc>\n";
                $sitemap .= '      <image:title>'.e($product->name)."</image:title>\n";
                $sitemap .= "    </image:image>\n";
            }

            $sitemap .= "  </url>\n";
        }

        // Добавляем оптовые товары
        foreach ($wholesaleProducts as $product) {
            $sitemap .= "  <url>\n";
            $sitemap .= '    <loc>'.$this->punycodeUrl('/opt/'.$product->slug)."</loc>\n";
            $sitemap .= '    <lastmod>'.$product->updated_at->toAtomString()."</lastmod>\n";
            $sitemap .= "    <changefreq>weekly</changefreq>\n";
            $sitemap .= "    <priority>0.8</priority>\n";
            $sitemap .= "  </url>\n";
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
}
