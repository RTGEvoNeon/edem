@extends('layouts.app')

<x-seo.meta />

@section('content')
<style>
    /* Organic Art Nouveau Design - Расширенные стили для страницы товара */

    /* Декоративные элементы в стиле модерн */
    .art-nouveau-border {
        position: relative;
    }

    .art-nouveau-border::before,
    .art-nouveau-border::after {
        content: '';
        position: absolute;
        background: linear-gradient(90deg,
            transparent 0%,
            #fbe196 20%,
            #f8bd91 50%,
            #fbe196 80%,
            transparent 100%
        );
        height: 2px;
        left: 0;
        right: 0;
    }

    .art-nouveau-border::before {
        top: 0;
        opacity: 0.3;
    }

    .art-nouveau-border::after {
        bottom: 0;
        opacity: 0.3;
    }

    /* Органические формы для галереи */
    .organic-shape {
        border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
        position: relative;
        overflow: hidden;
    }

    .organic-shape-alt {
        border-radius: 48% 52% 39% 61% / 62% 45% 55% 38%;
    }

    /* Анимация плавающих лепестков */
    @keyframes petalFloat {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg);
            opacity: 0.15;
        }
        25% {
            transform: translate(10px, -20px) rotate(5deg);
            opacity: 0.25;
        }
        50% {
            transform: translate(-5px, -35px) rotate(-3deg);
            opacity: 0.2;
        }
        75% {
            transform: translate(15px, -25px) rotate(7deg);
            opacity: 0.18;
        }
    }

    @keyframes petalFloat2 {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg) scale(1);
            opacity: 0.1;
        }
        33% {
            transform: translate(-15px, -30px) rotate(-8deg) scale(1.1);
            opacity: 0.2;
        }
        66% {
            transform: translate(20px, -40px) rotate(10deg) scale(0.95);
            opacity: 0.15;
        }
    }

    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }

    .petal-float {
        position: absolute;
        width: 120px;
        height: 120px;
        background: radial-gradient(ellipse at 30% 30%,
            #fbd9be,
            #f8bd91 40%,
            transparent 70%
        );
        border-radius: 50% 0% 50% 50%;
        animation: petalFloat 12s ease-in-out infinite;
        pointer-events: none;
        z-index: 0;
    }

    .petal-float-2 {
        animation: petalFloat2 15s ease-in-out infinite;
        background: radial-gradient(ellipse at 70% 40%,
            #fdf0c4,
            #cad3ca 50%,
            transparent 75%
        );
        animation-delay: -5s;
    }

    /* Кастомный курсор для hover областей */
    .custom-cursor-zoom {
        cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="%23e96d3f" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>'), zoom-in;
    }

    /* Эффект свечения для активных элементов */
    .glow-primary {
        box-shadow:
            0 0 20px rgba(233, 109, 63, 0.15),
            0 0 40px rgba(233, 109, 63, 0.1),
            0 8px 32px rgba(0, 0, 0, 0.12);
    }

    .glow-gold {
        box-shadow:
            0 0 30px rgba(244, 179, 41, 0.2),
            0 0 60px rgba(244, 179, 41, 0.1);
    }

    /* Градиент с анимацией */
    .animated-gradient {
        background: linear-gradient(
            120deg,
            #e96d3f,
            #f4b329,
            #d95132,
            #e0971e
        );
        background-size: 200% 200%;
        animation: shimmer 3s ease-in-out infinite;
    }

    /* Текстура холста */
    .canvas-texture {
        background-image:
            url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' /%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
    }

    /* Стеклянный эффект для карточек */
    .glass-morphism {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    /* Плавные переходы для изображений */
    .image-reveal {
        position: relative;
        overflow: hidden;
    }

    .image-reveal img {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                    filter 0.4s ease;
    }

    .image-reveal:hover img {
        transform: scale(1.08);
        filter: brightness(1.05) contrast(1.05);
    }

    /* Декоративная рамка */
    .decorative-frame {
        position: relative;
        padding: 2rem;
    }

    .decorative-frame::before {
        content: '';
        position: absolute;
        inset: 0;
        border: 2px solid transparent;
        background: linear-gradient(135deg,
            #fbe196,
            #f8bd91,
            #a5b5a5
        ) border-box;
        -webkit-mask:
            linear-gradient(#fff 0 0) padding-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask:
            linear-gradient(#fff 0 0) padding-box,
            linear-gradient(#fff 0 0);
        mask-composite: exclude;
        border-radius: 2rem;
        opacity: 0.5;
    }

    /* Типографика с особым характером */
    .display-xl {
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .price-display {
        font-size: clamp(2rem, 4vw, 3.5rem);
        font-weight: 700;
        background: linear-gradient(135deg,
            #e96d3f,
            #f4b329
        );
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-variant-numeric: oldstyle-nums;
    }

    /* Кнопки с органическими формами */
    .btn-organic {
        border-radius: 3rem 1rem 3rem 1rem;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-organic::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg,
            transparent,
            rgba(255, 255, 255, 0.3),
            transparent
        );
        transform: translateX(-100%);
        transition: transform 0.6s;
    }

    .btn-organic:hover::before {
        transform: translateX(100%);
    }

    .btn-organic:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .btn-organic:active {
        transform: translateY(-2px) scale(0.98);
    }

    /* Особенности товара с иконками */
    .feature-badge {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.9),
            rgba(248, 247, 244, 0.8)
        );
        border: 1px solid #ddd8c8;
        border-radius: 1.5rem 0.5rem 1.5rem 0.5rem;
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
    }

    .feature-badge:hover {
        background: white;
        border-color: #f8bd91;
        transform: translateX(8px);
    }

    .feature-badge svg {
        transition: transform 0.3s ease;
    }

    .feature-badge:hover svg {
        transform: rotate(12deg) scale(1.1);
    }

    /* Миниатюры галереи */
    .thumbnail-organic {
        border-radius: 1.5rem 0.5rem 1.5rem 0.5rem;
        border: 3px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .thumbnail-organic::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg,
            rgba(233, 109, 63, 0.3),
            rgba(244, 179, 41, 0.3)
        );
        opacity: 0;
        transition: opacity 0.3s;
    }

    .thumbnail-organic:hover::after {
        opacity: 1;
    }

    .thumbnail-organic.active {
        border-color: #f49162;
        box-shadow: 0 0 0 3px #fdeee0;
    }

    /* Счетчик изображений */
    .image-counter {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        border-radius: 2rem;
        padding: 0.5rem 1.25rem;
        font-family: 'Playfair Display', serif;
        font-weight: 500;
        letter-spacing: 0.05em;
    }

    /* Zoom overlay */
    .zoom-overlay {
        backdrop-filter: blur(20px);
        background: rgba(0, 0, 0, 0.95);
    }

    /* Прелоадер изображений */
    .image-loading {
        position: relative;
        background: linear-gradient(
            90deg,
            #efede5 0%,
            #ddd8c8 50%,
            #efede5 100%
        );
        background-size: 200% 100%;
        animation: loading 1.5s ease-in-out infinite;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .image-loading::after {
        content: '';
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-loaded {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Улучшенные переходы */
    * {
        -webkit-tap-highlight-color: transparent;
    }

    .smooth-scroll {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    /* Адаптивность */
    @media (max-width: 1024px) {
        .animate-fade-in-up.stagger-2 {
            margin-top: 3rem;
        }

        .decorative-frame {
            padding: 1.5rem;
        }

        .petal-float {
            width: 80px;
            height: 80px;
        }

        .display-xl {
            font-size: clamp(2rem, 6vw, 3.5rem);
        }

        .price-display {
            font-size: clamp(1.75rem, 5vw, 3rem);
        }
    }

    @media (max-width: 768px) {
        .art-nouveau-border::before,
        .art-nouveau-border::after {
            display: none;
        }

        .animate-fade-in-up.stagger-2 {
            margin-top: 2rem;
        }

        .decorative-frame {
            padding: 1rem;
        }

        .decorative-frame::before {
            border-radius: 1rem;
            opacity: 0.3;
        }

        .btn-organic {
            border-radius: 2rem 0.75rem 2rem 0.75rem;
            font-size: 1rem;
            padding: 0.875rem 1.5rem;
        }

        .organic-shape {
            border-radius: 2rem;
        }

        .feature-badge {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .feature-badge:hover {
            transform: translateX(4px);
        }

        /* Убираем плавающие лепестки на мобильных */
        .petal-float {
            display: none;
        }

        /* Оптимизация галереи на мобильных */
        .thumbnail-organic {
            border-width: 2px;
            width: 70px;
            height: 70px;
            min-width: 70px;
        }

        .thumbnail-organic img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        /* Контейнер главного изображения */
        .image-reveal .aspect-\[4\/5\] {
            aspect-ratio: auto;
            max-height: 60vh;
        }

        .image-reveal img {
            object-fit: contain;
            max-height: 60vh;
        }

        /* Sticky галерея только на desktop */
        .sticky {
            position: relative;
        }
    }

    @media (max-width: 640px) {
        .animate-fade-in-up.stagger-2 {
            margin-top: 2rem;
        }

        .decorative-frame {
            padding: 0.5rem;
        }

        .decorative-frame::before {
            display: none;
        }

        .art-nouveau-border {
            padding: 1.5rem 0;
        }

        .feature-badge svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Еще меньшие миниатюры на совсем маленьких экранах */
        .thumbnail-organic {
            width: 60px;
            height: 60px;
            min-width: 60px;
            margin: 0 2px;
        }

        /* Главное изображение на маленьких экранах */
        .image-reveal .aspect-\[4\/5\] {
            max-height: 50vh;
        }

        .image-reveal img {
            max-height: 50vh;
        }
    }

    /* Улучшение производительности анимаций */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }

        .petal-float {
            display: none;
        }
    }
</style>

<!-- Плавающие декоративные элементы -->
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0" aria-hidden="true">
    <div class="petal-float" style="top: 10%; left: 5%;"></div>
    <div class="petal-float petal-float-2" style="top: 60%; right: 8%;"></div>
    <div class="petal-float" style="bottom: 15%; left: 15%; animation-delay: -8s;"></div>
</div>

<!-- Основной контент -->
<div class="relative z-10 min-h-screen py-8 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Хлебные крошки -->
        <nav class="mb-8 animate-fade-in-up">
            <ol class="flex items-center space-x-2 text-sm text-accent-600">
                <li><a href="/" class="hover:text-primary-600 transition-colors">Главная</a></li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="/products" class="hover:text-primary-600 transition-colors">Каталог</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-900 font-medium">{{ $product->name }}</span>
                </li>
            </ol>
        </nav>

        <!-- Основная сетка товара -->
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-start">

            <!-- Галерея изображений -->
            <div class="animate-fade-in-up stagger-1">
                <div class="sticky top-24">
                    <!-- Главное изображение -->
                    <div class="relative mb-6 image-reveal organic-shape canvas-texture bg-gradient-to-br from-accent-50 via-primary-50 to-sage-50 group">
                        <div class="aspect-[4/5] relative overflow-hidden">
                            @php
                                $images = $product->image_urls;
                                $hasImages = !empty($images);
                                $imageCount = count($images);
                            @endphp

                            @if($imageCount > 1)
                                <div class="absolute top-4 right-4 z-20 image-counter text-white text-sm">
                                    <span id="current-image">1</span> / {{ $imageCount }}
                                </div>
                            @endif

                            @if($hasImages)
                                <img
                                    src="{{ $images[0] }}"
                                    alt="{{ $product->name }} — фото букета, купить в Брянске"
                                    class="w-full h-full object-cover custom-cursor-zoom"
                                    id="main-image"
                                    onclick="openZoom(this.src)"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-32 h-32 text-accent-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            <!-- Навигационные стрелки -->
                            @if($imageCount > 1)
                                <button
                                    onclick="changeImage(-1)"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full glass-morphism flex items-center justify-center text-primary-600 hover:bg-white hover:shadow-lg transition-all opacity-0 group-hover:opacity-100"
                                    aria-label="Предыдущее изображение"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <button
                                    onclick="changeImage(1)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full glass-morphism flex items-center justify-center text-primary-600 hover:bg-white hover:shadow-lg transition-all opacity-0 group-hover:opacity-100"
                                    aria-label="Следующее изображение"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Миниатюры -->
                    @if($imageCount > 0)
                        <div class="flex gap-3 overflow-x-auto pb-2 mb-3 scrollbar-thin scrollbar-thumb-primary-300 scrollbar-track-accent-100">
                            @foreach($images as $index => $imageUrl)
                                <button
                                    onclick="setMainImage('{{ $imageUrl }}', '{{ $product->name }}', {{ $index }})"
                                    class="thumbnail-organic flex-shrink-0 w-20 h-20 lg:w-24 lg:h-24 overflow-hidden {{ $index === 0 ? 'active' : '' }}"
                                    aria-label="Показать изображение {{ $index + 1 }}"
                                >
                                    <img
                                        src="{{ $imageUrl }}"
                                        alt="{{ $product->name }} — вид {{ $index + 1 }}"
                                        loading="lazy"
                                        class="w-full h-full object-cover"
                                        style="object-position: center;"
                                    >
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Информация о товаре -->
            <div class="animate-fade-in-up stagger-2">
                <div class="decorative-frame">
                <!-- Категория -->
                    <div class="mb-4">
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-primary-100 to-gold-100 text-primary-700 border border-primary-200">
                            {{ ucfirst($product->category) }}
                        </span>
                    </div>

                    <!-- Название -->
                    <h1 class="display-xl font-display font-semibold text-gray-900 mb-6 text-balance">
                        {{ $product->name }}
                    </h1>

                    <!-- Цена -->
                    <div class="mb-8">
                        <div class="price-display">
                            {{ number_format($product->price, 0, ',', ' ') }}<span class="text-2xl price-currency"> ₽</span>
                        </div>
                    </div>

                    <!-- Описание -->
                    @if($product->description)
                        <div class="mb-10 pb-10 text-lg text-gray-700 leading-relaxed art-nouveau-border py-8">
                            {{ $product->description }}
                        </div>
                    @else
                        <div class="mb-10 text-lg text-gray-600 leading-relaxed italic art-nouveau-border py-8">
                            Прекрасный букет, созданный с любовью и вниманием к деталям нашими флористами. Каждый цветок подобран вручную, чтобы создать гармоничную композицию, которая будет радовать вас своей красотой.
                        </div>
                    @endif

                    <!-- Действия -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-12">
                        <button
                            onclick="openOrderModal()"
                            class="btn-organic flex-1 py-4 animated-gradient text-white font-semibold text-lg hover:glow-primary focus:outline-none focus:ring-4 focus:ring-primary-200 text-center"
                        >
                            <span class="flex items-center justify-center gap-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Оформить заказ
                            </span>
                        </button>
                    </div>

                    <!-- Особенности -->
                    <div class="space-y-3">
                        <h2 class="text-xl font-display font-semibold text-gray-900 mb-6">Особенности букета</h2>

                        <div class="feature-badge">
                            <div class="flex items-center gap-4">
                                <svg class="w-6 h-6 text-sage-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Свежие цветы высшего качества</span>
                            </div>
                        </div>

                        <div class="feature-badge">
                            <div class="flex items-center gap-4">
                                <svg class="w-6 h-6 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Доставка в течение дня</span>
                            </div>
                        </div>

                        <div class="feature-badge">
                            <div class="flex items-center gap-4">
                                <svg class="w-6 h-6 text-gold-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Элегантная упаковка в подарок</span>
                            </div>
                        </div>

                        <div class="feature-badge">
                            <div class="flex items-center gap-4">
                                <svg class="w-6 h-6 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Собрано с любовью и заботой</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="p-8 animate-fade-in-up stagger-3">
            <div class="max-w-4xl mx-auto">
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-white to-accent-50 border-2 border-gray-200">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-primary-400 to-gold-400 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold text-gray-900 mb-2">Гарантия свежести</h3>
                        <p class="text-sm text-gray-600">Только свежие цветы прямо от поставщиков</p>
                    </div>

                    <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-white to-accent-50 border-2 border-gray-200">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-sage-400 to-primary-400 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold text-gray-900 mb-2">Быстрая доставка</h3>
                        <p class="text-sm text-gray-600">Доставим в удобное для вас время</p>
                    </div>

                    <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-white to-accent-50 border-2 border-gray-200">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-gold-400 to-primary-400 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold text-gray-900 mb-2">Индивидуальный подход</h3>
                        <p class="text-sm text-gray-600">Учтем все ваши пожелания</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Zoom overlay -->
<div id="zoom-overlay" class="zoom-overlay fixed inset-0 z-50 hidden items-center justify-center p-4" onclick="closeZoom()">
    <img id="zoom-image" src="" alt="Zoom" class="max-w-full max-h-full object-contain rounded-3xl shadow-2xl">
</div>

<!-- Модальное окно заказа -->
<div id="order-modal" class="zoom-overlay fixed inset-0 z-50 hidden items-center justify-center p-4" onclick="closeOrderModal(event)">
    <div class="glass-morphism max-w-md w-full rounded-3xl p-8 shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-display font-semibold text-gray-900">Оформить заказ</h3>
            <button onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="order-form" class="space-y-4">
            <input type="hidden" name="total_amount" value="{{ $product->price }}">
            <input type="hidden" name="product_url" value="{{ url('/product/' . $product->slug) }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ваше имя *</label>
                <input
                    type="text"
                    name="customer_name"
                    required
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 transition-all"
                    placeholder="Как к вам обращаться?"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Телефон *</label>
                <input
                    type="tel"
                    id="customer_phone"
                    name="customer_phone"
                    required
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 transition-all"
                    placeholder="+7 (___) ___-__-__"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Адрес доставки</label>
                <input
                    type="text"
                    name="delivery_address"
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 transition-all"
                    placeholder="Улица, дом, квартира"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Комментарий</label>
                <textarea
                    name="notes"
                    rows="3"
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 transition-all"
                    placeholder="Пожелания к букету, время доставки..."
                ></textarea>
            </div>

            <div class="bg-accent-50 rounded-xl p-4 mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium">{{ $product->name }}</span>
                    <span class="text-primary-600 font-semibold text-lg">{{ number_format($product->price, 0, ',', ' ') }} <span class="price-currency">₽</span></span>
                </div>
            </div>

            <div class="pt-4">
                <button
                    type="submit"
                    class="btn-organic w-full py-4 animated-gradient text-white font-semibold text-lg hover:glow-primary focus:outline-none focus:ring-4 focus:ring-primary-200"
                >
                    Отправить заявку
                </button>
            </div>
        </form>

        <div id="order-success" class="hidden text-center py-8">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h4 class="text-xl font-semibold text-gray-900 mb-2">Заявка принята!</h4>
            <p class="text-gray-600">Мы свяжемся с вами в ближайшее время</p>
        </div>

        <div id="order-error" class="hidden bg-red-50 border-2 border-red-200 rounded-xl p-4 text-red-700 text-sm"></div>
    </div>
</div>

<script>
    // Галерея изображений
    const images = @json($product->image_urls);
    const productName = @json($product->name);
    let currentImageIndex = 0;

    // Функция переключения изображения
    function setMainImage(src, alt, index) {
        const mainImage = document.getElementById('main-image');
        const currentCounter = document.getElementById('current-image');

        mainImage.src = src;
        mainImage.alt = alt;

        if (currentCounter) {
            currentCounter.textContent = index + 1;
        }

        // Обновляем активную миниатюру
        document.querySelectorAll('.thumbnail-organic').forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });

        currentImageIndex = index;
    }

    // Функция навигации (стрелки)
    function changeImage(direction) {
        if (images.length === 0) return;

        currentImageIndex += direction;

        if (currentImageIndex >= images.length) {
            currentImageIndex = 0;
        } else if (currentImageIndex < 0) {
            currentImageIndex = images.length - 1;
        }

        setMainImage(images[currentImageIndex], productName, currentImageIndex);
    }

    // Функция открытия zoom
    function openZoom(src) {
        const overlay = document.getElementById('zoom-overlay');
        const zoomImage = document.getElementById('zoom-image');

        zoomImage.src = src;
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    // Функция закрытия zoom
    function closeZoom() {
        const overlay = document.getElementById('zoom-overlay');
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Клавиатурная навигация
    document.addEventListener('keydown', function(e) {
        switch(e.key) {
            case 'ArrowLeft':
                if (images.length > 0) changeImage(-1);
                break;
            case 'ArrowRight':
                if (images.length > 0) changeImage(1);
                break;
            case 'Escape':
                closeZoom();
                break;
        }
    });

    // Свайп для мобильных устройств
    let startX = null;
    const mainImageContainer = document.querySelector('.image-reveal');

    if (mainImageContainer) {
        mainImageContainer.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });

        mainImageContainer.addEventListener('touchend', function(e) {
            if (!startX || images.length === 0) return;

            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;

            if (Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    changeImage(1);
                } else {
                    changeImage(-1);
                }
            }

            startX = null;
        });
    }

    // CSS стили для scrollbar
    const style = document.createElement('style');
    style.textContent = `
        .scrollbar-thin::-webkit-scrollbar {
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #efede5;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #f8bd91;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #f49162;
        }
    `;
    document.head.appendChild(style);

    // Модальное окно заказа
    function openOrderModal() {
        const modal = document.getElementById('order-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Яндекс.Метрика: клик по кнопке "Оформить заказ"
        if (typeof ym !== 'undefined') {
            ym(104582209, 'reachGoal', 'click_order_button');
        }
    }

    function closeOrderModal(event) {
        if (event && event.target !== event.currentTarget) return;

        const modal = document.getElementById('order-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';

        // Сброс формы
        document.getElementById('order-form').reset();
        document.getElementById('order-form').classList.remove('hidden');
        document.getElementById('order-success').classList.add('hidden');
        document.getElementById('order-error').classList.add('hidden');
    }

    // Маска для телефона
    function initPhoneMask() {
        const phoneInput = document.getElementById('customer_phone');
        if (!phoneInput) return;

        let mask = '';

        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            // Если первая цифра 8, заменяем на 7
            if (value.startsWith('8')) {
                value = '7' + value.slice(1);
            }

            // Если первая цифра не 7, добавляем 7
            if (value.length > 0 && !value.startsWith('7')) {
                value = '7' + value;
            }

            let formattedValue = '';

            if (value.length > 0) {
                formattedValue = '+7';

                if (value.length > 1) {
                    formattedValue += ' (' + value.substring(1, 4);
                }
                if (value.length >= 5) {
                    formattedValue += ') ' + value.substring(4, 7);
                }
                if (value.length >= 8) {
                    formattedValue += '-' + value.substring(7, 9);
                }
                if (value.length >= 10) {
                    formattedValue += '-' + value.substring(9, 11);
                }
            }

            e.target.value = formattedValue;
        });

        // Устанавливаем начальное значение при фокусе
        phoneInput.addEventListener('focus', function(e) {
            if (e.target.value === '') {
                e.target.value = '+7 (';
            }
        });

        // Очищаем при потере фокуса, если введено только +7 (
        phoneInput.addEventListener('blur', function(e) {
            if (e.target.value === '+7 (' || e.target.value === '+7') {
                e.target.value = '';
            }
        });
    }

    // Инициализируем маску при открытии модального окна
    document.addEventListener('DOMContentLoaded', function() {
        initPhoneMask();
    });

    // Обработка отправки формы
    document.getElementById('order-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const errorDiv = document.getElementById('order-error');

        submitButton.disabled = true;
        submitButton.textContent = 'Отправка...';
        errorDiv.classList.add('hidden');

        try {
            const response = await fetch('/order/submit', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Яндекс.Метрика: успешная отправка заказа
                if (typeof ym !== 'undefined') {
                    ym(104582209, 'reachGoal', 'order_submitted');
                }

                if (data.payment_url) {
                    window.location.href = data.payment_url;
                    return;
                }

                document.getElementById('order-form').classList.add('hidden');
                document.getElementById('order-success').classList.remove('hidden');

                setTimeout(() => {
                    closeOrderModal();
                }, 3000);
            } else {
                throw new Error(data.message || 'Произошла ошибка');
            }
        } catch (error) {
            errorDiv.textContent = error.message;
            errorDiv.classList.remove('hidden');
            submitButton.disabled = false;
            submitButton.textContent = 'Отправить заявку';
        }
    });
</script>
@endsection
