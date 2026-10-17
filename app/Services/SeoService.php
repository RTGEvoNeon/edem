<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\URL;

/**
 * SEO Service для управления мета-тегами, Open Graph, Schema.org разметкой
 */
class SeoService
{
    protected array $meta = [];

    protected array $openGraph = [];

    protected array $twitter = [];

    protected array $schema = [];

    protected ?string $canonical = null;

    /**
     * Установить базовые SEO данные
     */
    public function __construct()
    {
        // Базовые настройки по умолчанию
        $this->setDefaults();
    }

    /**
     * Установить значения по умолчанию
     */
    protected function setDefaults(): void
    {
        $this->meta = [
            'title' => config('app.name', 'Эдемский сад').' — Цветочная мастерская',
            'description' => 'Свежие букеты с доставкой по Брянску. Цветочная мастерская Эдемский сад создаёт уникальные композиции для особенных моментов.',
            'keywords' => 'цветы Брянск, букеты Брянск, доставка цветов Брянск, купить цветы, заказать букет',
            'robots' => 'index, follow',
            'author' => 'Эдемский сад',
        ];

        $this->openGraph = [
            'og:type' => 'website',
            'og:site_name' => 'Эдемский сад',
            'og:locale' => 'ru_RU',
        ];

        $this->twitter = [
            'twitter:card' => 'summary_large_image',
        ];

        $this->setImage('/images/logo.jpg', 'Эдемский сад — Цветочная мастерская');
    }

    /**
     * Установить title страницы
     */
    public function setTitle(string $title, bool $appendSiteName = true): self
    {
        $fullTitle = $appendSiteName
            ? $title.' — Эдемский сад'
            : $title;

        $this->meta['title'] = $fullTitle;
        $this->openGraph['og:title'] = $title; // OG title без site name
        $this->twitter['twitter:title'] = $title;

        return $this;
    }

    /**
     * Установить description
     */
    public function setDescription(string $description): self
    {
        // Обрезаем до оптимальной длины для SEO (155-160 символов)
        $optimizedDescription = mb_strlen($description) > 160
            ? mb_substr($description, 0, 157).'...'
            : $description;

        $this->meta['description'] = $optimizedDescription;
        $this->openGraph['og:description'] = $optimizedDescription;
        $this->twitter['twitter:description'] = $optimizedDescription;

        return $this;
    }

    /**
     * Установить keywords
     */
    public function setKeywords(string|array $keywords): self
    {
        $this->meta['keywords'] = is_array($keywords)
            ? implode(', ', $keywords)
            : $keywords;

        return $this;
    }

    /**
     * Установить canonical URL
     */
    public function setCanonical(?string $url = null): self
    {
        $this->canonical = $url ?? URL::current();
        $this->openGraph['og:url'] = $this->canonical;

        return $this;
    }

    /**
     * Установить изображение для Open Graph и Twitter
     */
    public function setImage(string $imageUrl, ?string $alt = null): self
    {
        // Делаем абсолютный URL если передан относительный
        $absoluteUrl = str_starts_with($imageUrl, 'http')
            ? $imageUrl
            : url($imageUrl);

        $this->openGraph['og:image'] = $absoluteUrl;
        $this->openGraph['og:image:secure_url'] = $absoluteUrl;

        if ($alt) {
            $this->openGraph['og:image:alt'] = $alt;
        }

        $this->twitter['twitter:image'] = $absoluteUrl;
        if ($alt) {
            $this->twitter['twitter:image:alt'] = $alt;
        }

        return $this;
    }

    /**
     * Установить тип Open Graph
     */
    public function setType(string $type): self
    {
        $this->openGraph['og:type'] = $type;

        return $this;
    }

    /**
     * Установить robots meta
     */
    public function setRobots(string $robots): self
    {
        $this->meta['robots'] = $robots;

        return $this;
    }

    /**
     * Добавить Schema.org разметку
     */
    public function addSchema(array $schema): self
    {
        $this->schema[] = $schema;

        return $this;
    }

    /**
     * Создать Schema.org разметку для товара
     */
    public function setProductSchema(
        string $name,
        string $description,
        float $price,
        string $currency = 'RUB',
        ?string $image = null,
        ?string $url = null,
        ?string $availability = 'InStock'
    ): self {
        $schema = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $name,
            'description' => $description,
            'offers' => [
                '@type' => 'Offer',
                'price' => $price,
                'priceCurrency' => $currency,
                'availability' => "https://schema.org/{$availability}",
                'url' => $url ?? $this->canonical ?? URL::current(),
            ],
        ];

        if ($image) {
            $schema['image'] = str_starts_with($image, 'http') ? $image : url($image);
        }

        $this->addSchema($schema);

        return $this;
    }

    /**
     * Создать Schema.org разметку для LocalBusiness
     */
    public function setLocalBusinessSchema(array $options = []): self
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Florist',
            'name' => $options['name'] ?? 'Эдемский сад',
            'image' => $options['image'] ?? url('/images/logo.jpg'),
            'description' => $options['description'] ?? 'Цветочная мастерская для особенных моментов. Свежие букеты с доставкой по Брянску',
            'telephone' => $options['telephone'] ?? '+79532929246',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $options['street'] ?? 'ул. Академика Сахарова, 5',
                'addressLocality' => $options['city'] ?? 'Брянск',
                'addressRegion' => $options['region'] ?? 'Брянская область',
                'addressCountry' => 'RU',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $options['latitude'] ?? null,
                'longitude' => $options['longitude'] ?? null,
            ],
            'url' => $options['url'] ?? url('/'),
            'priceRange' => $options['priceRange'] ?? '₽₽',
            'openingHours' => $options['openingHours'] ?? 'Mo-Su 09:00-23:59',
        ];

        // Удаляем null-координаты из geo и сам блок, если координат не осталось
        $schema['geo'] = array_filter($schema['geo'], fn ($value) => ! is_null($value));
        if (count($schema['geo']) <= 1) {
            unset($schema['geo']);
        }

        $this->addSchema($schema);

        return $this;
    }

    /**
     * Создать Schema.org разметку для BreadcrumbList
     */
    public function setBreadcrumbSchema(array $breadcrumbs): self
    {
        $itemListElement = [];

        foreach ($breadcrumbs as $position => $breadcrumb) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url'] ?? null,
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement,
        ];

        $this->addSchema($schema);

        return $this;
    }

    /**
     * Получить все мета-теги как HTML
     */
    public function renderMetaTags(): string
    {
        $html = [];

        // Title
        if (isset($this->meta['title'])) {
            $html[] = '<title>'.e($this->meta['title']).'</title>';
        }

        // Meta tags
        foreach ($this->meta as $name => $content) {
            // keywords игнорируют и Яндекс, и Google
            if ($name === 'title' || $name === 'keywords') {
                continue;
            }
            $html[] = sprintf('<meta name="%s" content="%s">', e($name), e($content));
        }

        // Canonical
        if ($this->canonical) {
            $html[] = sprintf('<link rel="canonical" href="%s">', e($this->canonical));
        }

        // Open Graph
        foreach ($this->openGraph as $property => $content) {
            $html[] = sprintf('<meta property="%s" content="%s">', e($property), e($content));
        }

        // Twitter
        foreach ($this->twitter as $name => $content) {
            $html[] = sprintf('<meta name="%s" content="%s">', e($name), e($content));
        }

        return implode("\n    ", $html);
    }

    /**
     * Получить Schema.org разметку как JSON-LD
     */
    public function renderSchema(): string
    {
        if (empty($this->schema)) {
            return '';
        }

        $scripts = [];
        foreach ($this->schema as $schema) {
            $scripts[] = sprintf(
                '<script type="application/ld+json">%s</script>',
                json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            );
        }

        return implode("\n    ", $scripts);
    }

    /**
     * Получить все SEO данные для использования в view
     */
    public function getData(): array
    {
        return [
            'meta' => $this->meta,
            'openGraph' => $this->openGraph,
            'twitter' => $this->twitter,
            'canonical' => $this->canonical,
            'schema' => $this->schema,
        ];
    }
}
