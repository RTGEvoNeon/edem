@extends('layouts.app')

<x-seo.meta />

@section('content')
<!-- Hero Section - Оптовый каталог -->
<section class="relative overflow-hidden bg-gradient-to-b from-white via-sage-50 to-white py-8 sm:py-16 lg:py-24">
    <!-- Декоративные элементы -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-sage-200/20 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-gold-200/20 rounded-full blur-3xl animate-float" style="animation-delay: -2s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-4 sm:space-y-6 mb-6 sm:mb-12">
            <div class="inline-block animate-fade-in-up">
                <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/90 backdrop-blur-sm border border-sage-200 shadow-sm">
                    <span class="w-2 h-2 bg-sage-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-sage-700">Оптовая продажа тюльпанов</span>
                </span>
            </div>

            <h1 class="font-display text-3xl sm:text-5xl lg:text-7xl font-bold text-gray-900 leading-tight text-balance animate-fade-in-up stagger-1">
                Тюльпаны
                <span class="relative inline-block">
                    <span class="relative z-10 bg-gradient-to-r from-sage-600 via-sage-500 to-gold-600 bg-clip-text text-transparent">оптом</span>
                    <svg class="absolute -bottom-2 left-0 w-full h-4 text-sage-300/50" viewBox="0 0 300 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 9C50 3 100 1 150 2C200 3 250 5 298 9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </span>
                от производителя
            </h1>

            <p class="text-base sm:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed animate-fade-in-up stagger-2">
                Минимальный заказ от 1000 шт. Выгодные цены при больших объёмах.
            </p>

            <!-- Телефоны для связи -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 pt-4 sm:pt-6 animate-fade-in-up stagger-2">
                <a href="tel:+79102333111" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-sage-700 hover:text-sage-600 active:text-sage-800 transition-colors tracking-tight tabular-nums">
                    +7 (910) 233-31-11
                </a>
                <a href="tel:+79532929246" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-sage-700 hover:text-sage-600 active:text-sage-800 transition-colors tracking-tight tabular-nums">
                    +7 (953) 292-92-46
                </a>
            </div>
        </div>

    </div>
</section>

<!-- Прайс на тюльпаны -->
@php
    $priceTiers = [
        ['label' => 'от 100 020 шт.', 'price' => 54],
        ['label' => '50 020 – 100 000 шт.', 'price' => 55],
        ['label' => '30 020 – 50 000 шт.', 'price' => 56],
        ['label' => '10 020 – 30 000 шт.', 'price' => 57],
        ['label' => '5 020 – 10 000 шт.', 'price' => 58],
        ['label' => 'до 5 000 шт.', 'price' => 59],
    ];
@endphp
<section class="relative py-8 sm:py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gold-100 text-gold-700 font-medium text-sm">
                🌷 Актуальный прайс
            </span>
            <h2 class="font-display text-3xl sm:text-5xl font-bold text-gray-900 mt-4">
                Цена на тюльпаны
            </h2>
            <p class="text-base sm:text-xl text-gray-500 mt-3">Чем больше объём — тем выгоднее цена за штуку</p>
        </div>

        <ul class="mt-8 sm:mt-12 border-t border-sage-200">
            @foreach($priceTiers as $tier)
                <li class="flex items-baseline justify-between gap-4 sm:gap-6 py-4 sm:py-6 border-b border-sage-200">
                    <span class="text-lg sm:text-3xl text-gray-700 tabular-nums">{{ $tier['label'] }}</span>
                    <span class="flex-1 border-b-2 border-dotted border-sage-300 translate-y-[-6px]"></span>
                    <span class="font-sans text-2xl sm:text-5xl font-bold tabular-nums bg-gradient-to-r from-sage-600 to-sage-500 bg-clip-text text-transparent">
                        {{ $tier['price'] }}<span class="price-currency">₽</span>
                    </span>
                </li>
            @endforeach
        </ul>

        <div class="mt-8 sm:mt-10 flex items-center justify-center gap-3 rounded-full bg-gradient-to-r from-sage-600 via-sage-500 to-gold-600 px-6 py-4 sm:py-5 text-white font-semibold text-sm sm:text-xl tracking-wide">
            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h11v9H3zM14 10h4l3 3v3h-7zM7 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM17 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
            </svg>
            ВОЗМОЖНА ДОСТАВКА
        </div>
    </div>
</section>

<!-- Каталог оптовых товаров -->
<section class="py-8 sm:py-16 bg-white relative">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div id="products-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @forelse($products as $product)
                <article class="product-card group flex flex-col bg-white rounded-2xl sm:rounded-3xl overflow-hidden border border-sage-200/50 shadow-lg hover-lift transition-all duration-500"
                         style="opacity: 0; animation: fadeInUp 0.6s ease-out forwards; animation-delay: {{ ($loop->index % 9) * 0.1 }}s;">

                    <!-- Изображение товара -->
                    <div class="relative h-80 sm:h-96 overflow-hidden bg-gradient-to-br from-sage-200 via-sage-300 to-sage-400">
                        @if($product->main_image && $product->main_image !== '/images/placeholder.jpg')
                            <img src="{{ $product->main_image }}"
                                 alt="{{ $product->name }} — оптовая продажа"
                                 loading="lazy"
                                 class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110"
                                 style="object-position: center 35%;">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-7xl opacity-40">🌷</span>
                            </div>
                        @endif

                        <!-- Оверлей при наведении -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Бейдж "от X₽/шт" -->
                        <div class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-white/95 backdrop-blur-sm px-3 py-2 sm:px-4 sm:py-2 rounded-full shadow-lg">
                            <div class="flex flex-col items-center">
                                <span class="text-xs text-gray-500">от</span>
                                <span class="font-sans text-lg sm:text-2xl font-bold tabular-nums bg-gradient-to-r from-sage-600 to-sage-500 bg-clip-text text-transparent">
                                    {{ number_format($product->min_price, 0) }}<span class="price-currency">₽</span>/шт
                                </span>
                            </div>
                        </div>

                    </div>

                    <!-- Контент карточки -->
                    <div class="flex flex-col flex-1 p-4 sm:p-6 space-y-3 sm:space-y-4">
                        <h3 class="font-display text-lg sm:text-2xl font-semibold text-gray-900 group-hover:text-sage-600 transition-colors line-clamp-2">
                            {{ $product->name }}
                        </h3>

                        <!-- Таблица цен -->
                        <div class="bg-sage-50 rounded-xl p-3 sm:p-4 space-y-2">
                            <div class="text-xs sm:text-sm font-semibold text-sage-700 mb-2">Ценовые уровни:</div>
                            @foreach($product->getTierInfo() as $tier)
                                <div class="flex justify-between items-center text-xs sm:text-sm">
                                    <span class="text-gray-600">{{ $tier['label'] }}</span>
                                    <span class="font-bold text-sage-700">{{ number_format($tier['price'], 0) }}₽</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Действия -->
                        <div class="flex gap-3 pt-2 mt-auto">
                            <a href="/opt/{{ $product->slug }}"
                               class="flex-1 group/btn bg-gradient-to-r from-sage-600 to-sage-500 text-white px-4 py-2.5 sm:px-5 sm:py-3 rounded-full font-semibold text-sm sm:text-base text-center transition-all hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                <span>Заказать</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-sage-100 rounded-full mb-6">
                        <span class="text-5xl opacity-50">🌷</span>
                    </div>
                    <h3 class="font-display text-3xl font-semibold text-gray-900 mb-4">Товары не найдены</h3>
                    <p class="text-xl text-gray-600 mb-8">Мы работаем над пополнением ассортимента</p>
                </div>
            @endforelse
        </div>

        <!-- Пагинация -->
        @if($products->hasPages())
            <div class="mt-16 flex justify-center">
                <div class="flex items-center gap-2">
                    @if ($products->onFirstPage())
                        <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 rounded-full bg-white border-2 border-sage-300 text-gray-900 hover:border-sage-400 hover:bg-sage-50 transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    @endif

                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <span class="px-5 py-2 rounded-full bg-gradient-to-r from-sage-600 to-sage-500 text-white font-semibold shadow-lg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-5 py-2 rounded-full bg-white border-2 border-sage-300 text-gray-900 hover:border-sage-400 hover:bg-sage-50 transition-all font-medium shadow-sm">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 rounded-full bg-white border-2 border-sage-300 text-gray-900 hover:border-sage-400 hover:bg-sage-50 transition-all shadow-sm">
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

<!-- Карта — где мы находимся -->
<section class="py-16 sm:py-24 bg-gradient-to-b from-white to-sage-50/50 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-sage-300 to-transparent"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-12">
            <div class="inline-block mb-4">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-sage-100 text-sage-700 font-medium text-sm">
                    📍 Мы здесь
                </span>
            </div>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                Как нас найти
            </h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">
                г. Брянск, ул. Академика Сахарова, 5 — оптовые заказы и выдача
            </p>
        </div>
        <div class="overflow-hidden rounded-3xl border-2 border-sage-200/50 shadow-xl">
            <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A44c15fbbc06a1918b30ef4008bef6dfc6e94b209ee5468ed2444ae0f343d7c39&amp;width=100%25&amp;height=450&amp;lang=ru_RU&amp;scroll=true"></script>
        </div>
        <p class="text-center mt-6">
            <a href="{{ route('contacts') }}" class="text-sage-600 hover:text-sage-700 font-semibold inline-flex items-center gap-2">
                <span>Контакты и часы работы</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </p>
    </div>
</section>

<!-- Призыв к действию -->
<section class="relative py-24 overflow-hidden bg-gradient-to-br from-sage-600 via-sage-500 to-gold-600">
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
                    💼 Специальные условия для бизнеса
                </span>
            </div>

            <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight text-balance">
                Нужна консультация?
            </h2>

            <p class="text-xl md:text-2xl text-white/90 max-w-2xl mx-auto leading-relaxed text-balance">
                Свяжитесь с нами для обсуждения индивидуальных условий и специальных цен
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
                <a href="/contacts" class="group inline-flex items-center gap-3 px-8 py-4 bg-white text-sage-700 rounded-full font-semibold shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
                    <span>Связаться с нами</span>
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
    /* Стили для фильтров с акцентом на sage */
    .filter-btn {
        background: white;
        color: #6c757d;
        border: 2px solid #cad3ca;
    }

    .filter-btn:hover {
        border-color: #5f7560;
        color: #5f7560;
        background: #f6f7f6;
    }

    .filter-btn.active-sage {
        background: linear-gradient(135deg, #5f7560, #4a5d4b);
        color: white;
        border-color: transparent;
        box-shadow: 0 8px 25px rgba(95, 117, 96, 0.3);
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
