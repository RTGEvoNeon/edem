@extends('layouts.app')

@push('head')
    <link rel="preload" href="{{ asset('images/main1.jpg') }}" as="image">
@endpush

<x-seo.meta />

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden">
    <!-- Background Gradient Mesh -->
    <div class="absolute inset-0 gradient-mesh"></div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 right-10 w-72 h-72 bg-primary-200/30 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-gold-200/30 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-24 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Left Column - Text Content -->
            <div class="space-y-8 animate-fade-in-up">
                <div class="inline-block">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-accent-200 shadow-sm">
                        <span class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></span>
                        <span class="text-sm font-medium text-accent-700">Свежие букеты каждый день</span>
                    </span>
                </div>

                <h1 class="font-display text-6xl lg:text-7xl xl:text-8xl font-bold text-gray-900 leading-[0.95] text-balance">
                    Говорим<br/>
                    <span class="relative inline-block">
                        <span class="relative z-10 bg-gradient-to-r from-primary-600 via-primary-500 to-gold-600 bg-clip-text text-transparent">цветами</span>
                        <svg class="absolute -bottom-2 left-0 w-full h-4 text-primary-300/50" viewBox="0 0 300 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 9C50 3 100 1 150 2C200 3 250 5 298 9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>

                <p class="text-xl lg:text-2xl text-gray-700 leading-relaxed max-w-xl text-balance">
                    Каждый наш букет — искреннее послание, созданное для того, чтобы принести радость, уют и&nbsp;особенные воспоминания
                </p>

                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="#catalog" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-full font-semibold shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all hover:scale-105" onclick="if(typeof ym !== 'undefined') ym(104582209, 'reachGoal', 'click_find_bouquet');">
                        <span>Найти букет</span>
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right Column - Image Grid -->
<div class="relative animate-fade-in-up stagger-2">
    <!-- Decorative frame -->
    <div class="absolute -top-6 -right-6 w-full h-full border-2 border-gold-400/30 rounded-3xl"></div>

    <div class="relative grid grid-cols-2 gap-4">
        <!-- Large image (LCP) -->
        <div class="col-span-2 h-80 rounded-3xl shadow-2xl overflow-hidden hover-lift">
            <img
                src="{{ asset('images/main1.jpg') }}"
                alt="Основной букет"
                class="w-full h-full object-cover"
                width="800"
                height="400"
                fetchpriority="high"
                decoding="async"
            >
        </div>

        <!-- Small images -->
        <div class="h-48 rounded-2xl shadow-lg overflow-hidden hover-lift">
            <img
                src="{{ asset('images/main2.jpg') }}"
                alt="Букет роз — свежие цветы от Эдемский сад"
                loading="lazy"
                class="w-full h-full object-cover"
            >
        </div>

        <div class="h-48 rounded-2xl shadow-lg overflow-hidden hover-lift">
            <img
                src="{{ asset('images/main3.jpg') }}"
                alt="Цветочная композиция — красивые букеты на заказ"
                loading="lazy"
                class="w-full h-full object-cover"
            >
        </div>
    </div>

    <!-- Floating badge -->
    <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl p-4 backdrop-blur-sm border border-accent-200 animate-float">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-gold-500 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                ★
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">4.9</div>
                <div class="text-xs text-gray-600">Более 500 отзывов</div>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</section>

<!-- Quick Info -->
<section class="py-20 bg-white relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group relative animate-fade-in-up stagger-1">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-gold-100 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-8 rounded-3xl border border-accent-200/50 bg-white/50 backdrop-blur-sm hover:border-primary-300 transition-all">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-primary-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-semibold mb-3 text-gray-900">Быстрая доставка</h3>
                    <p class="text-gray-600 leading-relaxed">Доставляем свежие букеты по всему городу в день заказа</p>
                </div>
            </div>

            <div class="group relative animate-fade-in-up stagger-2">
                <div class="absolute inset-0 bg-gradient-to-br from-sage-100 to-primary-100 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-8 rounded-3xl border border-accent-200/50 bg-white/50 backdrop-blur-sm hover:border-primary-300 transition-all">
                    <div class="w-14 h-14 bg-gradient-to-br from-sage-500 to-sage-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-sage-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-semibold mb-3 text-gray-900">Свежие цветы</h3>
                    <p class="text-gray-600 leading-relaxed">Работаем только со свежими цветами от проверенных поставщиков</p>
                </div>
            </div>

            <div class="group relative animate-fade-in-up stagger-3">
                <div class="absolute inset-0 bg-gradient-to-br from-gold-100 to-primary-100 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-8 rounded-3xl border border-accent-200/50 bg-white/50 backdrop-blur-sm hover:border-primary-300 transition-all">
                    <div class="w-14 h-14 bg-gradient-to-br from-gold-500 to-gold-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-gold-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-semibold mb-3 text-gray-900">Доступные цены</h3>
                    <p class="text-gray-600 leading-relaxed">Цены на наши прекрасные букеты начинаются от <span class="font-semibold text-primary-600">2 000 ₽</span></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Блоки по категориям -->
<section id="catalog" class="py-24 bg-gradient-to-b from-white to-accent-50 relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-20 w-64 h-64 bg-primary-400 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-gold-400 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 space-y-20">
        @foreach($categoryBlocks as $block)
        @php($category = $block['category'])
        <div>
            <!-- Section Header -->
            <div class="text-center mb-16 space-y-4">
                <h2 class="font-display text-4xl lg:text-5xl font-bold text-gray-900 text-balance">
                    {{ $category->name }}
                </h2>
            </div>

            <div class="category-carousel overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($block['products'] as $product)
                    <!-- Карточка букета -->
                    <div class="swiper-slide h-auto">
                        <article class="group hover-lift bg-white rounded-2xl sm:rounded-3xl overflow-hidden border border-accent-200/50 shadow-lg flex flex-col h-full">
                            <a href="{{ route('products.show', $product->slug) }}" class="block relative h-44 sm:h-80 shrink-0 bg-gradient-to-br from-gray-200 to-gray-300 overflow-hidden">
                                @if($product->main_image)
                                    <x-product-image
                                        :src="$product->main_image"
                                        :alt="$product->name . ' — купить букет в Брянске'"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-gray-400 text-5xl sm:text-7xl">🌸</div>
                                @endif
                                <!-- Price badge -->
                                <div class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-white/95 backdrop-blur-sm px-2 py-1 sm:px-4 sm:py-2 rounded-full shadow-lg">
                                    <span class="text-sm sm:text-2xl font-bold tabular-nums bg-gradient-to-r from-primary-600 to-primary-500 bg-clip-text text-transparent inline-flex items-baseline gap-0.5">
                                        <span>{{ number_format($product->price, 0, ',', ' ') }}</span>
                                        <span class="price-currency" aria-hidden="true">₽</span>
                                    </span>
                                </div>
                            </a>
                            <div class="p-3 sm:p-6 flex flex-col flex-1 min-h-0">
                                <h3 class="font-display text-sm sm:text-2xl font-semibold text-gray-900 mb-1 sm:mb-2 line-clamp-2 min-h-[2.5em] sm:min-h-[2.4em]">{{ $product->name }}</h3>
                                <p class="hidden sm:block text-gray-600 leading-relaxed line-clamp-2 flex-1 min-h-0">{{ $product->description ?? 'Прекрасный букет для особого случая' }}</p>
                                <a href="{{ route('products.show', $product->slug) }}" class="block w-full mt-2 sm:mt-4 group/btn relative overflow-hidden bg-gradient-to-r from-primary-600 to-primary-500 text-white px-3 py-2 sm:px-6 sm:py-3.5 rounded-full font-semibold text-xs sm:text-base shadow-lg hover:shadow-xl transition-all hover:scale-[1.02]" onclick="if(typeof ym !== 'undefined') ym(104582209, 'reachGoal', 'click_product_card');">
                                    <span class="relative z-10 flex items-center justify-center gap-1 sm:gap-2">
                                        <span>Заказать букет</span>
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('products.index', ['category' => $category->key]) }}" class="group inline-flex items-center gap-3 px-8 py-4 border-2 border-primary-400 text-primary-700 rounded-full font-semibold hover:bg-primary-50 transition-all hover:scale-105 shadow-sm">
                    <span>Смотреть все: {{ $category->name }}</span>
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Карта — где мы находимся -->
<section class="py-24 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-block mb-4">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-100 text-primary-700 font-medium text-sm">
                    📍 Мы здесь
                </span>
            </div>
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                Как нас найти
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                г. Брянск, ул. Академика Сахарова, 5 — доставляем букеты по всему городу
            </p>
        </div>
        <div class="overflow-hidden rounded-3xl border-2 border-accent-200/50 shadow-xl">
            <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A44c15fbbc06a1918b30ef4008bef6dfc6e94b209ee5468ed2444ae0f343d7c39&amp;width=100%25&amp;height=450&amp;lang=ru_RU&amp;scroll=true"></script>
        </div>
        <p class="text-center mt-6">
            <a href="{{ route('contacts') }}" class="text-primary-600 hover:text-primary-700 font-semibold inline-flex items-center gap-2">
                <span>Контакты и часы работы</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </p>
    </div>
</section>

<!-- SEO-текст -->
@php($seoBlock = config('seo_texts.home'))
@if($seoBlock)
<section class="py-16 sm:py-20 bg-accent-50/40">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
            <h2 class="font-display text-2xl sm:text-3xl lg:text-4xl font-semibold text-gray-900 mb-6">
                {{ $seoBlock['heading'] }}
            </h2>
            @foreach($seoBlock['body'] as $paragraph)
                <p class="mb-4">{{ $paragraph }}</p>
            @endforeach
        </article>
    </div>
</section>
@endif

<!-- TODO: Call to Action для кастомного букета (временно отключена) -->
<!-- <section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-primary-500 to-gold-600"></div>
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96">
            <svg viewBox="0 0 200 200" class="w-full h-full">
                <circle cx="100" cy="100" r="80" fill="none" stroke="white" stroke-width="0.5"/>
                <circle cx="100" cy="100" r="60" fill="none" stroke="white" stroke-width="0.5"/>
                <circle cx="100" cy="100" r="40" fill="none" stroke="white" stroke-width="0.5"/>
            </svg>
        </div>
    </div>
    <div class="relative max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <div class="space-y-8">
            <div class="inline-block">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm text-white font-medium text-sm border border-white/30">
                    ✨ Индивидуальный подход
                </span>
            </div>
            <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight text-balance">
                Не нашли идеальный букет?
            </h2>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed text-balance">
                Создайте уникальный букет вместе с нашим флористом. Расскажите о ваших пожеланиях, и мы воплотим их в жизнь!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
                <a href="/custom-bouquet" class="group inline-flex items-center gap-3 px-8 py-4 bg-white text-primary-700 rounded-full font-semibold shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
                    <span>Собрать свой букет</span>
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                <a href="/products" class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-full font-semibold border-2 border-white/30 hover:bg-white/20 hover:border-white/50 transition-all">
                    <span>Посмотреть каталог</span>
                </a>
            </div>
            <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto pt-12">
                <div class="text-white/90">
                    <div class="font-display text-3xl font-bold">500+</div>
                    <div class="text-sm text-white/70">Счастливых клиентов</div>
                </div>
                <div class="text-white/90">
                    <div class="font-display text-3xl font-bold">1000+</div>
                    <div class="text-sm text-white/70">Букетов доставлено</div>
                </div>
                <div class="text-white/90">
                    <div class="font-display text-3xl font-bold">4.9</div>
                    <div class="text-sm text-white/70">Средняя оценка</div>
                </div>
            </div>
        </div>
    </div>
</section> -->

@endsection
