<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\Seo;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Старые ссылки вида /products?category=wedding → постоянный редирект на посадочную страницу
        $legacyCategory = $request->query('category');
        if (is_string($legacyCategory) && $legacyCategory !== 'all'
            && Category::query()->where('key', $legacyCategory)->exists()) {
            return redirect()->route('products.category', array_filter([
                'category' => $legacyCategory,
                'page' => $request->query('page'),
            ]), 301);
        }

        $this->setSeoForCatalog(null, $this->currentPage($request));

        return view('products.index', [
            'products' => $this->getFilteredProducts(null),
            'categories' => $this->getAvailableCategories(),
            'currentCategory' => 'all',
            'activeCategory' => null,
            'seoBlock' => config('seo_texts.all'),
        ]);
    }

    /**
     * Посадочная страница категории: /bukety/{key}.
     */
    public function category(Request $request, Category $category): View
    {
        $products = $this->getFilteredProducts($category);

        $this->setSeoForCatalog($category, $this->currentPage($request));

        // Пустую категорию не индексируем (тонкая страница), но URL оставляем рабочим
        if ($products->isEmpty()) {
            Seo::setRobots('noindex, follow');
        }

        return view('products.index', [
            'products' => $products,
            'categories' => $this->getAvailableCategories(),
            'currentCategory' => $category->key,
            'activeCategory' => $category,
            'seoBlock' => config('seo_texts.'.$category->key),
        ]);
    }

    private function currentPage(Request $request): int
    {
        return max(1, (int) $request->query('page', 1));
    }

    /**
     * Категории, в которых есть хотя бы один доступный товар.
     *
     * @return Collection<int, Category>
     */
    private function getAvailableCategories(): Collection
    {
        return Category::query()
            ->whereHas('products', function (Builder $query): Builder {
                /** @var Builder<Product> $query */
                return $query->available();
            })
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Получить товары каталога (всего или одной категории) с пагинацией.
     */
    private function getFilteredProducts(?Category $category): LengthAwarePaginator
    {
        $query = Product::available()->withImages()->with('categories');

        if ($category !== null) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $category->id));
        }

        return $query->paginate(18)->withQueryString();
    }

    /**
     * Установить SEO-данные для каталога или посадочной страницы категории.
     */
    private function setSeoForCatalog(?Category $category, int $page): void
    {
        /** @var array<string, mixed> $texts */
        $texts = $category !== null ? config('seo_texts.'.$category->key, []) : [];

        $title = $category !== null
            ? ($texts['title'] ?? "{$category->name} — купить в Брянске")
            : 'Каталог букетов';

        if ($page > 1) {
            $title .= " — страница {$page}";
        }

        $description = $category !== null
            ? ($texts['description'] ?? "{$category->name} от цветочной мастерской Эдемский сад. Доставка по Брянску бесплатно.")
            : 'Каталог свежих букетов от цветочной мастерской Эдемский сад. Монобукеты и миксы от 2000₽. Доставка по Брянску бесплатно. Более 50 вариантов букетов.';

        $canonicalParams = $page > 1 ? ['page' => $page] : [];
        $canonicalUrl = $category !== null
            ? route('products.category', ['category' => $category->key] + $canonicalParams)
            : route('products.index', $canonicalParams);

        $breadcrumbs = [
            ['name' => 'Главная', 'url' => url('/')],
            ['name' => 'Каталог', 'url' => route('products.index')],
        ];
        if ($category !== null) {
            $breadcrumbs[] = [
                'name' => $category->name,
                'url' => route('products.category', ['category' => $category->key]),
            ];
        }

        Seo::setTitle($title)
            ->setDescription($description)
            ->setKeywords(['каталог цветов', 'купить букет', 'цены на букеты Брянск', 'свежие цветы'])
            ->setCanonical($canonicalUrl)
            ->setBreadcrumbSchema($breadcrumbs);

        if ($page > 1) {
            Seo::setRobots('noindex, follow');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->available()->firstOrFail();

        // SEO для страницы товара
        $title = $product->name.' — купить в Брянске';
        $description = $product->description
            ? mb_substr($product->description, 0, 140).'... Цена: '.number_format((float) $product->price, 0, '', ' ').'₽. Доставка по Брянску бесплатно.'
            : "Букет {$product->name} от цветочной мастерской Эдемский сад. Цена: ".number_format((float) $product->price, 0, '', ' ').'₽. Свежие цветы, бесплатная доставка по Брянску.';

        $firstCategory = $product->categories->first();
        $categoryKeyword = $firstCategory instanceof Category ? $firstCategory->name : 'букет';

        Seo::setTitle($title)
            ->setDescription($description)
            ->setKeywords([
                $product->name,
                "купить {$product->name}",
                "{$product->name} Брянск",
                "букет {$product->name}",
                $categoryKeyword,
            ])
            ->setCanonical(route('products.show', $product->slug))
            ->setType('product')
            ->setBreadcrumbSchema(array_values(array_filter([
                ['name' => 'Главная', 'url' => url('/')],
                ['name' => 'Каталог', 'url' => route('products.index')],
                $firstCategory instanceof Category ? [
                    'name' => $firstCategory->name,
                    'url' => route('products.category', ['category' => $firstCategory->key]),
                ] : null,
                ['name' => $product->name, 'url' => route('products.show', $product->slug)],
            ])))
            ->setProductSchema(
                name: $product->name,
                description: $product->description ?? "Прекрасный букет {$product->name} от цветочной мастерской Эдемский сад",
                price: $product->price,
                currency: 'RUB',
                image: $product->main_image,
                url: route('products.show', $product->slug),
                availability: $product->is_available ? 'InStock' : 'OutOfStock'
            );

        // Устанавливаем изображение для OG, если есть
        if ($product->main_image) {
            Seo::setImage($product->main_image, $product->name);
        }

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
