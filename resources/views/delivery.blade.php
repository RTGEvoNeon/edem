@extends('layouts.app')

<x-seo.meta />

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-primary-50 via-white to-accent-50">
    <!-- Background Gradient Mesh -->
    <div class="absolute inset-0 gradient-mesh opacity-50"></div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 right-10 w-72 h-72 bg-primary-200/20 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-gold-200/20 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20 lg:py-28">
        <div class="text-center space-y-6 animate-fade-in-up">
            <div class="inline-block">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-accent-200 shadow-sm">
                    <span class="text-2xl">🚚</span>
                    <span class="text-sm font-medium text-accent-700">Доставка цветов</span>
                </span>
            </div>

            <h1 class="font-display text-5xl lg:text-7xl font-bold text-gray-900 leading-tight">
                Доставим свежие букеты<br/>
                <span class="relative inline-block">
                    <span class="relative z-10 bg-gradient-to-r from-primary-600 via-primary-500 to-gold-600 bg-clip-text text-transparent">с заботой и любовью</span>
                    <svg class="absolute -bottom-2 left-0 w-full h-4 text-primary-300/50" viewBox="0 0 300 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 9C50 3 100 1 150 2C200 3 250 5 298 9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </span>
            </h1>

            <p class="text-xl lg:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Быстрая и бережная доставка цветов в любую точку города. Ваши букеты в надежных руках!
            </p>
        </div>
    </div>
</section>

<!-- Бесплатная доставка по Брянску -->
<section class="relative py-16 overflow-hidden">
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

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl border-2 border-white/30 p-8 lg:p-12">
            <div class="text-center space-y-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl border border-white/30">
                    <span class="text-5xl">🎉</span>
                </div>

                <h2 class="font-display text-4xl lg:text-5xl font-bold text-white">
                    Бесплатная доставка по Брянску!
                </h2>

                <p class="text-xl lg:text-2xl text-white/90 max-w-3xl mx-auto">
                    Мы ценим каждого клиента и дарим вам <span class="font-semibold text-white">бесплатную доставку</span> по всему городу Брянску при любой сумме заказа
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto pt-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                        <div class="text-4xl mb-3">⚡</div>
                        <div class="font-display text-2xl font-bold text-white mb-2">Быстро</div>
                        <div class="text-white/80">Доставка в день заказа</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                        <div class="text-4xl mb-3">💝</div>
                        <div class="font-display text-2xl font-bold text-white mb-2">Бережно</div>
                        <div class="text-white/80">Сохраним свежесть букета</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                        <div class="text-4xl mb-3">✨</div>
                        <div class="font-display text-2xl font-bold text-white mb-2">Бесплатно</div>
                        <div class="text-white/80">0 ₽ за доставку</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Зоны доставки -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16 space-y-4">
            <div class="inline-block">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-100 text-primary-700 font-medium text-sm">
                    📍 География доставки
                </span>
            </div>
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-gray-900">
                Зоны доставки
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Доставка по Брянску -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-gold-100 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-8 lg:p-10 rounded-3xl border-2 border-primary-200 bg-white hover:border-primary-300 transition-all space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20 flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-display text-2xl lg:text-3xl font-bold text-gray-900 mb-3">Брянск</h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-lg text-gray-700">
                                    <span class="text-2xl">💚</span>
                                    <span><span class="font-semibold text-primary-600">Бесплатная доставка</span> по всему городу</span>
                                </div>
                                <div class="flex items-center gap-3 text-lg text-gray-700">
                                    <span class="text-2xl">🕐</span>
                                    <span>Доставка в день заказа</span>
                                </div>
                                <div class="flex items-center gap-3 text-lg text-gray-700">
                                    <span class="text-2xl">📦</span>
                                    <span>При любой сумме заказа</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-accent-200">
                        <p class="text-gray-600 leading-relaxed">
                            Доставляем цветы по всем районам Брянска: Бежицкий, Советский, Фокинский и Володарский районы
                        </p>
                    </div>
                </div>
            </div>

            <!-- Доставка по области -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-br from-sage-100 to-primary-100 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-8 lg:p-10 rounded-3xl border-2 border-accent-200 bg-white hover:border-sage-300 transition-all space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-sage-500 to-sage-600 rounded-2xl flex items-center justify-center shadow-lg shadow-sage-500/20 flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-display text-2xl lg:text-3xl font-bold text-gray-900 mb-3">Брянская область</h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-lg text-gray-700">
                                    <span class="text-2xl">🚗</span>
                                    <span>Доставка по договоренности</span>
                                </div>
                                <div class="flex items-center gap-3 text-lg text-gray-700">
                                    <span class="text-2xl">💬</span>
                                    <span>Уточните стоимость у менеджера</span>
                                </div>
                                <div class="flex items-center gap-3 text-lg text-gray-700">
                                    <span class="text-2xl">📞</span>
                                    <span>Звоните для расчета</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-accent-200">
                        <p class="text-gray-600 leading-relaxed">
                            Доставляем букеты в населенные пункты Брянской области. Стоимость рассчитывается индивидуально
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Как работает доставка -->
<section class="py-20 bg-gradient-to-b from-white to-accent-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16 space-y-4">
            <div class="inline-block">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-100 text-primary-700 font-medium text-sm">
                    ⚙️ Процесс доставки
                </span>
            </div>
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-gray-900">
                Как это работает?
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Простой и понятный процесс от заказа до получения букета
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Шаг 1 -->
            <div class="relative group animate-fade-in-up stagger-1">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-gold-100 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-8 rounded-3xl border border-accent-200/50 bg-white/50 backdrop-blur-sm hover:border-primary-300 transition-all space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20">
                            <span class="text-white font-bold text-xl">1</span>
                        </div>
                        <svg class="hidden lg:block absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-semibold text-gray-900">Выберите букет</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Выберите готовый букет из каталога или создайте свой уникальный
                    </p>
                </div>
            </div>

            <!-- Шаг 2 -->
            <div class="relative group animate-fade-in-up stagger-2">
                <div class="absolute inset-0 bg-gradient-to-br from-sage-100 to-primary-100 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-8 rounded-3xl border border-accent-200/50 bg-white/50 backdrop-blur-sm hover:border-primary-300 transition-all space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-sage-500 to-sage-600 rounded-2xl flex items-center justify-center shadow-lg shadow-sage-500/20">
                            <span class="text-white font-bold text-xl">2</span>
                        </div>
                        <svg class="hidden lg:block absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-semibold text-gray-900">Оформите заказ</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Укажите адрес доставки, время и дополнительные пожелания
                    </p>
                </div>
            </div>

            <!-- Шаг 3 -->
            <div class="relative group animate-fade-in-up stagger-3">
                <div class="absolute inset-0 bg-gradient-to-br from-gold-100 to-primary-100 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-8 rounded-3xl border border-accent-200/50 bg-white/50 backdrop-blur-sm hover:border-primary-300 transition-all space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-gold-500 to-gold-600 rounded-2xl flex items-center justify-center shadow-lg shadow-gold-500/20">
                            <span class="text-white font-bold text-xl">3</span>
                        </div>
                        <svg class="hidden lg:block absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-semibold text-gray-900">Собираем букет</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Наши флористы создают ваш букет из свежих цветов
                    </p>
                </div>
            </div>

            <!-- Шаг 4 -->
            <div class="relative group animate-fade-in-up stagger-4">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-sage-100 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative p-8 rounded-3xl border border-accent-200/50 bg-white/50 backdrop-blur-sm hover:border-primary-300 transition-all space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-gold-500 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20">
                            <span class="text-white font-bold text-xl">4</span>
                        </div>
                    </div>
                    <h3 class="font-display text-xl font-semibold text-gray-900">Доставляем</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Курьер бережно доставит букет по указанному адресу
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Условия доставки -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16 space-y-4">
            <div class="inline-block">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-100 text-primary-700 font-medium text-sm">
                    📋 Важная информация
                </span>
            </div>
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-gray-900">
                Условия доставки
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
            <!-- Время доставки -->
            <div class="p-8 rounded-3xl border border-accent-200 bg-white shadow-sm hover:shadow-md transition-all space-y-4">
                <div class="w-14 h-14 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-semibold text-gray-900">Время доставки</h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-3">
                        <span class="text-primary-500 text-xl flex-shrink-0">•</span>
                        <span>Доставка осуществляется с <span class="font-semibold">9:00 до 00:00</span></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-primary-500 text-xl flex-shrink-0">•</span>
                        <span>Возможна доставка ко времени (уточняйте у менеджера)</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-primary-500 text-xl flex-shrink-0">•</span>
                        <span>При заказе до 18:00 доставка в этот же день от 1 часа</span>
                    </li>
                </ul>
            </div>

            <!-- Оплата -->
            <div class="p-8 rounded-3xl border border-accent-200 bg-white shadow-sm hover:shadow-md transition-all space-y-4">
                <div class="w-14 h-14 bg-gradient-to-br from-sage-100 to-sage-200 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-semibold text-gray-900">Способы оплаты</h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-3">
                        <span class="text-sage-500 text-xl flex-shrink-0">•</span>
                        <span><span class="font-semibold">При самовывозе:</span> наличные или перевод</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-sage-500 text-xl flex-shrink-0">•</span>
                        <span><span class="font-semibold">При доставке:</span> перевод по номеру телефона</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-sage-500 text-xl flex-shrink-0">•</span>
                        <span><span class="font-semibold text-primary-600">Важно:</span> для оформления заказа требуется полная предоплата</span>
                    </li>
                </ul>
            </div>

            <!-- Доставка получателю -->
            <div class="p-8 rounded-3xl border border-accent-200 bg-white shadow-sm hover:shadow-md transition-all space-y-4">
                <div class="w-14 h-14 bg-gradient-to-br from-gold-100 to-gold-200 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-semibold text-gray-900">Доставка получателю</h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-3">
                        <span class="text-gold-500 text-xl flex-shrink-0">•</span>
                        <span>Можем доставить букет как сюрприз</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-gold-500 text-xl flex-shrink-0">•</span>
                        <span>Не сообщаем получателю имя отправителя (по желанию)</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-gold-500 text-xl flex-shrink-0">•</span>
                        <span>Открытка с вашими пожеланиями (по желанию)</span>
                    </li>
                </ul>
            </div>

            <!-- Качество -->
            <div class="p-8 rounded-3xl border border-accent-200 bg-white shadow-sm hover:shadow-md transition-all space-y-4">
                <div class="w-14 h-14 bg-gradient-to-br from-primary-100 to-sage-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-semibold text-gray-900">Гарантия качества</h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-3">
                        <span class="text-primary-500 text-xl flex-shrink-0">•</span>
                        <span>Используем только свежие цветы</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-primary-500 text-xl flex-shrink-0">•</span>
                        <span>Бережная транспортировка букетов</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-primary-500 text-xl flex-shrink-0">•</span>
                        <span>Фото букета перед доставкой (по запросу)</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-gradient-to-b from-white to-accent-50">
    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center space-y-8">
        <div class="inline-block">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-100 text-primary-700 font-medium text-sm">
                💐 Готовы заказать?
            </span>
        </div>

        <h2 class="font-display text-4xl lg:text-5xl font-bold text-gray-900 leading-tight">
            Доставим ваш букет<br/>с заботой и вниманием
        </h2>

        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Выберите букет из каталога и мы доставим его бесплатно по всему Брянску
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4">
            <a href="/products" class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-full font-semibold shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all hover:scale-105">
                <span>Смотреть каталог</span>
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>

            <a href="/" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-gray-700 rounded-full font-semibold border-2 border-accent-200 hover:border-primary-300 hover:bg-accent-50 transition-all">
                <span>На главную</span>
            </a>
        </div>

        <!-- Контакты -->
        <div class="pt-8 space-y-4">
            <p class="text-gray-600 font-medium">Остались вопросы? Свяжитесь с нами:</p>
            <div class="flex flex-wrap justify-center gap-6 text-lg">
                <a href="tel:+79102333111" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>+7 (910) 233-31-11</span>
                </a>
                <a href="https://t.me/FlowersMindale" class="inline-flex items-center gap-2 text-sage-600 hover:text-sage-700 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.18 1.897-.962 6.502-1.359 8.627-.168.9-.5 1.201-.82 1.23-.697.064-1.226-.461-1.901-.903-1.056-.692-1.653-1.123-2.678-1.799-1.185-.781-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.139-5.062 3.345-.479.329-.913.489-1.302.481-.428-.008-1.252-.241-1.865-.44-.752-.244-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.831-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635.099-.002.321.023.465.141.121.099.155.232.171.325.016.093.036.306.02.472z"/>
                    </svg>
                    <span>Telegram</span>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
