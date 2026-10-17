@php
    $footerCategories = \App\Models\Category::query()
        ->whereHas('products', fn ($query) => $query->available())
        ->orderBy('sort_order')
        ->get(['id', 'key', 'name']);
@endphp
<!-- Footer -->
<footer class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 right-20 w-96 h-96 bg-primary-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-20 w-80 h-80 bg-gold-500 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20">
        <!-- Logo and tagline -->
        <div class="mb-16 text-center lg:text-left">
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-4 mb-6">
                <div class="w-16 h-16 rounded-full overflow-hidden">
                    <img src="/images/logo.jpg" alt="Эдемский сад" class="w-full h-full object-cover" width="64" height="64" loading="lazy" decoding="async">
                </div>
                <div>
                    <h3 class="font-display text-3xl font-bold mb-2">Эдемский сад</h3>
                    <p class="text-gray-400 text-lg">Цветочная мастерская для особенных моментов</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">

            <!-- Контакты -->
            <div>
                <h3 class="font-display text-xl font-semibold mb-6 text-white">Контакты</h3>
                <div class="space-y-4">
                    <a href="tel:+79532929246" class="group flex items-center hover:text-primary-400 transition-colors" onclick="if(typeof ym !== 'undefined') ym(104582209, 'reachGoal', 'click_phone');">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center mr-3 group-hover:bg-primary-500/20 transition-colors">
                            <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-300">+7 (953) 292-92-46</span>
                    </a>

                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-xl bg-gold-500/10 flex items-center justify-center mr-3 shrink-0">
                            <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-300">г. Брянск, ул. Академика Сахарова, 5<br>доставка по всему городу</span>
                    </div>

                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-xl bg-sage-500/10 flex items-center justify-center mr-3 shrink-0">
                            <svg class="w-5 h-5 text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-300">Выдача букетов:<br>Пн-Сб 8:00 - 00:00</span>
                    </div>
                </div>
            </div>
            
            <!-- Каталог -->
            <div>
                <h3 class="font-display text-xl font-semibold mb-6 text-white">Каталог</h3>
                <ul class="space-y-3">
                    <li><a href="/products" class="text-gray-300 hover:text-primary-400 transition-colors inline-flex items-center group">
                        <span class="w-1.5 h-1.5 bg-primary-400 rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Все букеты
                    </a></li>
                    @foreach($footerCategories as $footerCategory)
                    <li><a href="{{ route('products.category', ['category' => $footerCategory->key]) }}" class="text-gray-300 hover:text-primary-400 transition-colors inline-flex items-center group">
                        <span class="w-1.5 h-1.5 bg-primary-400 rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        {{ $footerCategory->name }}
                    </a></li>
                    @endforeach
                    <!-- TODO: Страница "Собери свой букет" временно отключена -->
                    <!-- <li><a href="/custom-bouquet" class="text-gray-300 hover:text-primary-400 transition-colors inline-flex items-center group">
                        <span class="w-1.5 h-1.5 bg-primary-400 rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Собери свой букет
                    </a></li> -->
                </ul>
            </div>

            <!-- Информация -->
            <div>
                <h3 class="font-display text-xl font-semibold mb-6 text-white">Информация</h3>
                <ul class="space-y-3">
                    <li><a href="/about" class="text-gray-300 hover:text-primary-400 transition-colors inline-flex items-center group">
                        <span class="w-1.5 h-1.5 bg-primary-400 rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        О нас
                    </a></li>
                    <li><a href="/delivery" class="text-gray-300 hover:text-primary-400 transition-colors inline-flex items-center group">
                        <span class="w-1.5 h-1.5 bg-primary-400 rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Доставка и оплата
                    </a></li>
                    <!-- TODO: Страница "Уход за цветами" временно отключена -->
                    <!-- <li><a href="/care" class="text-gray-300 hover:text-primary-400 transition-colors inline-flex items-center group">
                        <span class="w-1.5 h-1.5 bg-primary-400 rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Уход за цветами
                    </a></li> -->
                    <li><a href="/contacts" class="text-gray-300 hover:text-primary-400 transition-colors inline-flex items-center group">
                        <span class="w-1.5 h-1.5 bg-primary-400 rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        Контакты
                    </a></li>
                </ul>
            </div>

            <!-- Социальные сети -->
            <div>
                <h3 class="font-display text-xl font-semibold mb-6 text-white">Мы в соцсетях</h3>
                <div class="mb-6">
                    <a href="https://t.me/FlowersMindale" class="group inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 rounded-full hover:shadow-lg hover:shadow-primary-500/30 transition-all hover:scale-105" target="_blank" rel="noopener noreferrer" onclick="if(typeof ym !== 'undefined') ym(104582209, 'reachGoal', 'click_telegram');">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.18 1.897-.962 6.502-1.359 8.627-.168.9-.5 1.201-.82 1.23-.697.064-1.226-.461-1.901-.903-1.056-.692-1.653-1.123-2.678-1.799-1.185-.781-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.139-5.062 3.345-.479.329-.913.489-1.302.481-.428-.008-1.252-.241-1.865-.44-.752-.244-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.831-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635.099-.002.321.023.465.141.121.099.155.232.171.325.016.093.036.306.02.472z"/>
                        </svg>
                        <span class="font-semibold">Telegram</span>
                    </a>
                </div>

                <div class="text-sm text-gray-400 leading-relaxed">
                    <p class="mb-2">Подпишитесь на нас!</p>
                    <p>Новости, акции и красивые букеты в вашей ленте</p>
                </div>
            </div>
        </div>
        
        <!-- Нижняя часть -->
        <div class="border-t border-gray-800/50 mt-16 pt-12">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                <div class="text-center lg:text-left">
                    <p class="text-gray-400 mb-2">
                        © 2026 Эдемский сад. Все права защищены.
                    </p>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        ИП Редин Дмитрий Витальевич · ИНН 323200777288 · ОГРНИП 304325504400052
                    </p>
                </div>

                <div class="flex flex-wrap justify-center gap-6">
                    <a href="/privacy" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">
                        Политика конфиденциальности
                    </a>
                    <a href="/oferta" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">
                        Публичная оферта
                    </a>
                </div>
            </div>

            <!-- Made with love badge -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500 flex items-center justify-center gap-2">
                    <span>Создано с</span>
                    <span class="text-primary-400 animate-pulse">❤</span>
                    <span>для любителей цветов</span>
                </p>
            </div>
        </div>
    </div>
</footer>
