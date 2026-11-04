<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\Seo;
use App\Models\WholesaleProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WholesaleController extends Controller
{
    /**
     * Отображение каталога оптовых тюльпанов.
     */
    public function index(Request $request): View
    {
        $page = max(1, (int) $request->query('page', 1));

        $this->setSeoForCatalog($page);

        $products = $this->getFilteredProducts();

        return view('wholesale.index', [
            'products' => $products,
        ]);
    }

    /**
     * Отображение страницы оптового товара.
     */
    public function show(string $slug): View
    {
        $product = WholesaleProduct::where('slug', $slug)
            ->available()
            ->firstOrFail();

        // SEO для страницы товара
        $productName = str_contains(mb_strtolower($product->name), 'тюльпан')
            ? $product->name
            : 'Тюльпаны '.$product->name;
        $title = $productName.' оптом в Брянске — цены';
        $minPrice = number_format($product->min_price, 0, '', ' ');

        $description = "{$productName} оптом в Брянске: прайс по объёму, фото и расчёт стоимости. От {$minPrice}₽/шт при заказе от 100 020 шт. Минимальная партия: {$product->min_quantity} шт.";

        Seo::setTitle($title)
            ->setDescription($description)
            ->setKeywords([
                $product->name.' опт',
                'оптовая продажа тюльпанов',
                'тюльпаны опт Брянск',
                'купить тюльпаны оптом',
            ])
            ->setCanonical(route('wholesale.show', $product->slug))
            ->setType('product')
            ->setBreadcrumbSchema([
                ['name' => 'Главная', 'url' => url('/')],
                ['name' => 'Оптовые продажи', 'url' => route('wholesale.index')],
                ['name' => $product->name, 'url' => route('wholesale.show', $product->slug)],
            ])
            ->setProductSchema(
                name: $productName,
                description: "Оптовая продажа тюльпанов {$product->name}",
                price: $product->min_price,
                currency: 'RUB',
                image: $product->main_image,
                url: route('wholesale.show', $product->slug),
                availability: $product->is_available ? 'InStock' : 'OutOfStock',
                sku: 'opt-'.$product->id,
                minimumQuantity: max($product->min_quantity, 100020)
            );

        // Устанавливаем изображение для OG
        if ($product->main_image && $product->main_image !== '/images/placeholder.jpg') {
            Seo::setImage($product->main_image, $product->name);
        }

        return view('wholesale.show', compact('product', 'productName'));
    }

    /**
     * Получить отфильтрованные товары с пагинацией.
     */
    private function getFilteredProducts(): LengthAwarePaginator
    {
        return WholesaleProduct::available()
            ->orderBy('name')
            ->paginate(18)
            ->withQueryString();
    }

    /**
     * Установить SEO-данные для каталога.
     */
    private function setSeoForCatalog(int $page): void
    {
        $title = 'Тюльпаны оптом в Брянске — прайс и каталог сортов';

        if ($page > 1) {
            $title .= " — страница {$page}";
        }

        $description = 'Тюльпаны оптом в Брянске: каталог сортов с фото, прайс от 54 до 59₽/шт по объёму заказа. Минимальная партия от 1000 шт.; 54₽/шт — от 100 020 шт. Возможна доставка.';

        $canonicalUrl = $page > 1
            ? route('wholesale.index', ['page' => $page])
            : route('wholesale.index');

        Seo::setTitle($title)
            ->setDescription($description)
            ->setKeywords(['тюльпаны опт', 'оптовая продажа тюльпанов', 'тюльпаны оптом Брянск', 'купить тюльпаны оптом'])
            ->setCanonical($canonicalUrl)
            ->setBreadcrumbSchema([
                ['name' => 'Главная', 'url' => url('/')],
                ['name' => 'Оптовые продажи', 'url' => route('wholesale.index')],
            ]);

        if ($page > 1) {
            Seo::setRobots('noindex, follow');
        }
    }
}
