@extends('layouts.app')

<x-seo.meta />

@section('content')
<!-- Hero Section - Каталог -->
<section class="relative overflow-hidden bg-gradient-to-b from-white via-accent-50 to-white py-8 sm:py-16 lg:py-24">
    <!-- Декоративные элементы -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-primary-200/20 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-gold-200/20 rounded-full blur-3xl animate-float" style="animation-delay: -2s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-4 sm:space-y-6 mb-6 sm:mb-12">
            <div class="inline-block animate-fade-in-up">
                <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/90 backdrop-blur-sm border border-accent-200 shadow-sm">
                    <span class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-accent-700">Полный каталог букетов</span>
                </span>
            </div>

            <h1 class="font-display text-3xl sm:text-5xl lg:text-7xl font-bold text-gray-900 leading-tight text-balance animate-fade-in-up stagger-1">
                Выберите
                <span class="relative inline-block">
                    <span class="relative z-10 bg-gradient-to-r from-primary-600 via-primary-500 to-gold-600 bg-clip-text text-transparent">идеальный</span>
                    <svg class="absolute -bottom-2 left-0 w-full h-4 text-primary-300/50" viewBox="0 0 300 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 9C50 3 100 1 150 2C200 3 250 5 298 9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </span>
                букет
            </h1>

            <p class="text-base sm:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed animate-fade-in-up stagger-2">
                Каждый букет создан с любовью и вниманием к деталям
            </p>
        </div>

        <!-- Фильтры -->
        <nav class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-6 sm:mb-16 animate-fade-in-up stagger-3" aria-label="Фильтры по категориям">
            <a href="{{ route('products.index') }}"
               class="filter-btn {{ $currentCategory === 'all' ? 'active' : '' }} px-3 py-2 sm:px-6 sm:py-3 rounded-full font-medium text-xs sm:text-sm transition-all hover:scale-105 shadow-sm"
               @if($currentCategory === 'all') aria-current="page" @endif>
                Все букеты
            </a>
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->key]) }}"
                   class="filter-btn {{ $currentCategory === $category->key ? 'active' : '' }} px-3 py-2 sm:px-6 sm:py-3 rounded-full font-medium text-xs sm:text-sm transition-all hover:scale-105 shadow-sm"
                   @if($currentCategory === $category->key) aria-current="page" @endif>
                    {{ $category->name }}
                </a>
            @endforeach
        </nav>
    </div>
</section>

<!-- Каталог товаров -->
<section class="py-8 sm:py-16 bg-white relative">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div id="products-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-8">
            @forelse($products as $product)
                @php
                    $colorIndex = $loop->index % 3;
                    $bgGradient = match($colorIndex) {
                        0 => 'from-primary-200 via-primary-300 to-primary-400',
                        1 => 'from-gold-200 via-gold-300 to-gold-400',
                        default => 'from-sage-200 via-sage-300 to-sage-400',
                    };
                    $priceGradient = match($colorIndex) {
                        0 => 'from-primary-600 to-primary-500',
                        1 => 'from-gold-600 to-gold-500',
                        default => 'from-sage-600 to-sage-500',
                    };
                    $buttonGradient = match($colorIndex) {
                        0 => 'from-primary-600 to-primary-500',
                        1 => 'from-gold-600 to-gold-500',
                        default => 'from-sage-600 to-sage-500',
                    };
                @endphp
                <article class="product-card group flex flex-col bg-white rounded-2xl sm:rounded-3xl overflow-hidden border border-accent-200/50 shadow-lg hover-lift transition-all duration-500"
                         style="opacity: 0; animation: fadeInUp 0.6s ease-out forwards; animation-delay: {{ ($loop->index % 9) * 0.1 }}s;">

                    <!-- Изображение товара -->
                    <div class="relative h-44 sm:h-80 overflow-hidden bg-gradient-to-br {{ $bgGradient }}">
                        @if($product->main_image && $product->main_image !== '/images/placeholder.jpg')
                            <x-product-image
                                :src="$product->main_image"
                                :alt="$product->name . ' — свежие цветы с доставкой'"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-5xl sm:text-7xl opacity-40">
                                    {{ ['🌸', '🌻', '🌺', '🌹', '🌷', '💐'][$loop->index % 6] }}
                                </span>
                            </div>
                        @endif

                        <!-- Оверлей при наведении -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Бейдж с ценой -->
                        <div class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-white/95 backdrop-blur-sm px-2 py-1 sm:px-4 sm:py-2 rounded-full shadow-lg transform transition-transform group-hover:scale-110">
                            <span class="font-sans text-sm sm:text-2xl font-bold tabular-nums bg-gradient-to-r {{ $priceGradient }} bg-clip-text text-transparent inline-flex items-baseline gap-0.5">
                                <span>{{ number_format($product->price, 0) }}</span>
                                <span class="price-currency" aria-hidden="true">₽</span>
                            </span>
                        </div>

                        <!-- Категория -->
                        @if($product->categories->isNotEmpty())
                        <div class="absolute bottom-2 left-2 sm:bottom-4 sm:left-4 bg-white/95 backdrop-blur-sm px-2 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium text-gray-700 shadow-md">
                            {{ $product->categories->first()->name }}
                        </div>
                        @endif

                        <!-- Кнопка быстрого просмотра (только на десктопе) -->
                        <a href="/product/{{ $product->slug }}"
                           class="hidden sm:flex absolute inset-0 items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="bg-white/95 backdrop-blur-sm px-6 py-3 rounded-full font-semibold text-gray-900 shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Подробнее
                            </span>
                        </a>
                    </div>

                    <!-- Контент карточки -->
                    <div class="flex flex-col flex-1 p-3 sm:p-6 space-y-1 sm:space-y-4">
                        <h3 class="font-display text-sm sm:text-2xl font-semibold text-gray-900 group-hover:text-primary-600 transition-colors line-clamp-2 min-h-[2.5em] sm:min-h-[2.4em]">
                            {{ $product->name }}
                        </h3>

                        <!-- Описание (только десктоп) -->
                        <p class="hidden md:block md:line-clamp-2 text-gray-600 leading-relaxed flex-1 min-h-0">
                            {{ $product->description }}
                        </p>

                        <!-- Действия -->
                        <div class="flex gap-3 pt-1 sm:pt-2 mt-auto">
                            <a href="/product/{{ $product->slug }}"
                               class="flex-1 group/btn bg-gradient-to-r {{ $buttonGradient }} text-white px-3 py-2 sm:px-5 sm:py-3 rounded-full font-semibold text-xs sm:text-base text-center transition-all hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-1 sm:gap-2"
                               onclick="if(typeof ym !== 'undefined') ym(104582209, 'reachGoal', 'view_product_catalog');">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Подробнее</span>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-accent-100 rounded-full mb-6">
                        <span class="text-5xl opacity-50">🌸</span>
                    </div>
                    <h3 class="font-display text-3xl font-semibold text-gray-900 mb-4">Букеты не найдены</h3>
                    <p class="text-xl text-gray-600 mb-8">Мы работаем над пополнением ассортимента</p>
                    <a href="/custom-bouquet" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all hover:scale-105">
                        <span>Создать свой букет</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Пагинация -->
        @if($products->hasPages())
            <div class="mt-16 flex justify-center">
                <div class="flex items-center gap-2">
                    {{-- Предыдущая страница --}}
                    @if ($products->onFirstPage())
                        <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 rounded-full bg-white border-2 border-accent-300 text-gray-900 hover:border-primary-400 hover:bg-primary-50 transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    @endif

                    {{-- Номера страниц --}}
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <span class="px-5 py-2 rounded-full bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold shadow-lg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-5 py-2 rounded-full bg-white border-2 border-accent-300 text-gray-900 hover:border-primary-400 hover:bg-primary-50 transition-all font-medium shadow-sm">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Следующая страница --}}
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 rounded-full bg-white border-2 border-accent-300 text-gray-900 hover:border-primary-400 hover:bg-primary-50 transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>

<!-- SEO-текст под каталогом -->
@php($seoBlock = config('seo_texts.'.$currentCategory) ?? config('seo_texts.all'))
@if($seoBlock)
<section class="py-12 sm:py-16 bg-accent-50/40">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
            <h2 class="font-display text-2xl sm:text-3xl font-semibold text-gray-900 mb-6">
                {{ $seoBlock['heading'] }}
            </h2>
            @foreach($seoBlock['body'] as $paragraph)
                <p class="mb-4">{{ $paragraph }}</p>
            @endforeach
        </article>
    </div>
</section>
@endif

<!-- Призыв к действию -->
<section class="relative py-24 overflow-hidden bg-gradient-to-br from-primary-600 via-primary-500 to-gold-600">
    <!-- Декоративные паттерны -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-72 h-72">
            <svg viewBox="0 0 200 200" class="w-full h-full animate-spin" style="animation-duration: 60s;">
                <circle cx="100" cy="100" r="80" fill="none" stroke="white" stroke-width="0.5"/>
                <circle cx="100" cy="100" r="60" fill="none" stroke="white" stroke-width="0.5"/>
                <circle cx="100" cy="100" r="40" fill="none" stroke="white" stroke-width="0.5"/>
            </svg>
        </div>
    </div>

    <div class="relative max-w-4xl mx-auto px-6 lg:px-8 text-center">
        <div class="space-y-8">
            <div class="inline-block">
                <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/20 backdrop-blur-sm text-white font-medium text-sm border border-white/30">
                    ✨ Индивидуальный подход
                </span>
            </div>

            <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight text-balance">
                Не нашли то, что искали?
            </h2>

            <p class="text-xl md:text-2xl text-white/90 max-w-2xl mx-auto leading-relaxed text-balance">
                Создайте уникальный букет по вашему вкусу вместе с нашим флористом
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
                <a href="/custom-bouquet" class="group inline-flex items-center gap-3 px-8 py-4 bg-white text-primary-700 rounded-full font-semibold shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
                    <span>Собрать свой букет</span>
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>

                <a href="/" class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-full font-semibold border-2 border-white/30 hover:bg-white/20 transition-all">
                    <span>На главную</span>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    /* Стили для фильтров */
    .filter-btn {
        background: white;
        color: #6c757d;
        border: 2px solid #ddd8c8;
    }

    .filter-btn:hover {
        border-color: #f49162;
        color: #e96d3f;
        background: #fef8f3;
    }

    .filter-btn.active {
        background: linear-gradient(135deg, #e96d3f, #d95132);
        color: white;
        border-color: transparent;
        box-shadow: 0 8px 25px rgba(233, 109, 63, 0.3);
    }

    /* Анимация появления карточек */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Ограничение количества строк текста */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>


@endsection
