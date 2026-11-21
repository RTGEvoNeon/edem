@extends('layouts.app')

<x-seo.meta />

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden">
    <!-- Background Gradient Mesh -->
    <div class="absolute inset-0 gradient-mesh"></div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 right-10 w-72 h-72 bg-primary-200/30 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-gold-200/30 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-28">
        <div class="text-center space-y-4 sm:space-y-6 animate-fade-in-up">
            <div class="inline-block">
                <span class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-accent-200 shadow-sm">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-medium text-accent-700">Условия оформления заказа</span>
                </span>
            </div>

            <h1 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-gray-900 leading-[0.95]">
                Публичная<br/>
                <span class="relative inline-block">
                    <span class="relative z-10 bg-gradient-to-r from-primary-600 via-primary-500 to-gold-600 bg-clip-text text-transparent">оферта</span>
                    <svg class="absolute -bottom-1 sm:-bottom-2 left-0 w-full h-3 sm:h-4 text-primary-300/50" viewBox="0 0 300 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 9C50 3 100 1 150 2C200 3 250 5 298 9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </span>
            </h1>

            <p class="text-base sm:text-lg text-gray-600 px-4">
                Условия продажи и доставки цветочной продукции, оформленные в соответствии со ст. 437 ГК РФ
            </p>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="relative py-12 sm:py-16 lg:py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section 1: Общие положения -->
        <div class="mb-12 sm:mb-16 animate-fade-in-up">
            <div class="flex items-start gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20">
                    <span class="text-white font-display text-lg sm:text-xl md:text-2xl font-bold">1</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-display text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Общие положения</h2>
                </div>
            </div>
            <div class="space-y-4 text-gray-700 text-base sm:text-lg leading-relaxed">
                <p>
                    Настоящий документ является публичной офертой <span class="font-semibold text-gray-900">ИП Редина Дмитрия Витальевича</span> (далее — Продавец) и содержит все существенные условия продажи цветочной продукции через сайт <span class="font-mono text-xs sm:text-sm text-primary-700 bg-primary-50 px-2 py-0.5 rounded break-all">{{ request()->getHost() }}</span> (далее — Сайт).
                </p>
                <p>
                    В соответствии со статьёй 437 Гражданского кодекса РФ, оформление заказа на Сайте является полным и безоговорочным принятием (акцептом) условий настоящей оферты. Акцепт означает, что Покупатель ознакомился и согласен со всеми условиями оферты.
                </p>
            </div>
        </div>

        <!-- Decorative divider -->
        <div class="my-8 sm:my-12 lg:my-16 flex items-center justify-center">
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
            <div class="mx-4 text-accent-400">🌸</div>
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
        </div>

        <!-- Section 2: Предмет оферты -->
        <div class="mb-12 sm:mb-16 animate-fade-in-up stagger-1">
            <div class="flex items-start gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 bg-gradient-to-br from-sage-500 to-sage-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shadow-sage-500/20">
                    <span class="text-white font-display text-lg sm:text-xl md:text-2xl font-bold">2</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-display text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Предмет оферты</h2>
                </div>
            </div>
            <div class="space-y-3 text-gray-700 text-base sm:text-lg leading-relaxed">
                <p>
                    Продавец обязуется передать в собственность Покупателя букеты и цветочную продукцию, представленную в каталоге Сайта, а Покупатель обязуется оплатить и принять заказ на условиях настоящей оферты.
                </p>
                <p>
                    Ассортимент, стоимость и наличие товаров указаны в каталоге на Сайте на момент оформления заказа.
                </p>
            </div>
        </div>

        <!-- Decorative divider -->
        <div class="my-8 sm:my-12 lg:my-16 flex items-center justify-center">
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
            <div class="mx-4 text-accent-400">🌺</div>
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
        </div>

        <!-- Section 3: Оформление и оплата заказа -->
        <div class="mb-12 sm:mb-16 animate-fade-in-up stagger-2">
            <div class="flex items-start gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 bg-gradient-to-br from-gold-500 to-gold-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shadow-gold-500/20">
                    <span class="text-white font-display text-lg sm:text-xl md:text-2xl font-bold">3</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-display text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Оформление и оплата заказа</h2>
                </div>
            </div>
            <div class="space-y-3 text-gray-700 text-base sm:text-lg leading-relaxed">
                <div class="flex items-start gap-3 sm:gap-4 p-4 sm:p-5 bg-gradient-to-r from-white to-primary-50 rounded-xl border-l-4 border-primary-500">
                    <div class="flex-shrink-0 w-2 h-2 bg-primary-500 rounded-full mt-2"></div>
                    <p class="text-sm sm:text-base">Заказ оформляется через форму на Сайте с указанием контактных данных, адреса доставки и выбранных товаров</p>
                </div>
                <div class="flex items-start gap-3 sm:gap-4 p-4 sm:p-5 bg-gradient-to-r from-white to-primary-50 rounded-xl border-l-4 border-primary-500">
                    <div class="flex-shrink-0 w-2 h-2 bg-primary-500 rounded-full mt-2"></div>
                    <p class="text-sm sm:text-base">Оплата заказа производится онлайн банковской картой через платёжный сервис ЮKassa либо иным способом, указанным на странице оформления заказа</p>
                </div>
                <div class="flex items-start gap-3 sm:gap-4 p-4 sm:p-5 bg-gradient-to-r from-white to-primary-50 rounded-xl border-l-4 border-primary-500">
                    <div class="flex-shrink-0 w-2 h-2 bg-primary-500 rounded-full mt-2"></div>
                    <p class="text-sm sm:text-base">Обязательства Покупателя по оплате считаются исполненными с момента поступления денежных средств на счёт Продавца</p>
                </div>
                <div class="flex items-start gap-3 sm:gap-4 p-4 sm:p-5 bg-gradient-to-r from-white to-primary-50 rounded-xl border-l-4 border-primary-500">
                    <div class="flex-shrink-0 w-2 h-2 bg-primary-500 rounded-full mt-2"></div>
                    <p class="text-sm sm:text-base">Обработка платежей происходит на защищённой странице платёжного сервиса; данные банковской карты Продавцу не передаются и не хранятся на Сайте</p>
                </div>
            </div>
        </div>

        <!-- Decorative divider -->
        <div class="my-8 sm:my-12 lg:my-16 flex items-center justify-center">
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
            <div class="mx-4 text-accent-400">🌻</div>
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
        </div>

        <!-- Section 4: Доставка -->
        <div class="mb-12 sm:mb-16 animate-fade-in-up stagger-3">
            <div class="flex items-start gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20">
                    <span class="text-white font-display text-lg sm:text-xl md:text-2xl font-bold">4</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-display text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Доставка</h2>
                </div>
            </div>
            <div class="space-y-3 text-gray-700 text-base sm:text-lg leading-relaxed">
                <p>
                    Условия и сроки доставки указаны на странице <a href="{{ route('delivery') }}" class="text-primary-600 hover:text-primary-700 font-medium underline">«Доставка и оплата»</a>. Доставка по Брянску осуществляется бесплатно, доставка по Брянской области — по договорённости с Продавцом.
                </p>
            </div>
        </div>

        <!-- Decorative divider -->
        <div class="my-8 sm:my-12 lg:my-16 flex items-center justify-center">
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
            <div class="mx-4 text-accent-400">🌷</div>
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
        </div>

        <!-- Section 5: Возврат и обмен -->
        <div class="mb-12 sm:mb-16 animate-fade-in-up stagger-4">
            <div class="flex items-start gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 bg-gradient-to-br from-sage-500 to-sage-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shadow-sage-500/20">
                    <span class="text-white font-display text-lg sm:text-xl md:text-2xl font-bold">5</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-display text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Возврат и обмен</h2>
                </div>
            </div>
            <div class="space-y-4 text-gray-700 text-base sm:text-lg leading-relaxed">
                <p>
                    В соответствии с Постановлением Правительства РФ от 31.12.2020 № 2463, живые цветы и букеты надлежащего качества обмену и возврату не подлежат.
                </p>
                <p>
                    Если Покупателю передан товар ненадлежащего качества (увядшие или повреждённые при доставке цветы), он вправе обратиться к Продавцу для замены букета или возврата денежных средств. Претензия рассматривается по факту обращения через контакты, указанные в разделе 6.
                </p>
            </div>
        </div>

        <!-- Decorative divider -->
        <div class="my-8 sm:my-12 lg:my-16 flex items-center justify-center">
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
            <div class="mx-4 text-accent-400">🌼</div>
            <div class="h-px w-32 bg-gradient-to-r from-transparent via-accent-300 to-transparent"></div>
        </div>

        <!-- Section 6: Заключительные положения -->
        <div class="mb-12 sm:mb-16 animate-fade-in-up stagger-5">
            <div class="flex items-start gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 bg-gradient-to-br from-gold-500 to-gold-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shadow-gold-500/20">
                    <span class="text-white font-display text-lg sm:text-xl md:text-2xl font-bold">6</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-display text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Заключительные положения</h2>
                </div>
            </div>
            <div class="space-y-4 text-gray-700 text-base sm:text-lg leading-relaxed">
                <p>
                    По всем вопросам, касающимся заказа, оплаты, доставки или возврата, Покупатель может обратиться к Продавцу по телефону <a href="tel:+79102333111" class="text-primary-600 hover:text-primary-700 font-medium">+7 (910) 233-31-11</a> или через <a href="https://t.me/FlowersMindale" class="text-primary-600 hover:text-primary-700 font-medium underline">Telegram</a>.
                </p>
                <p>
                    Обработка персональных данных Покупателя осуществляется в соответствии с <a href="{{ route('privacy') }}" class="text-primary-600 hover:text-primary-700 font-medium underline">Политикой конфиденциальности</a>.
                </p>
                <p>
                    Продавец вправе вносить изменения в настоящую оферту. Актуальная редакция всегда доступна по адресу <span class="font-mono text-primary-700 bg-primary-50 px-2 py-0.5 rounded">{{ request()->getSchemeAndHttpHost() }}/oferta</span>.
                </p>
            </div>
        </div>

        <!-- Requisites Card -->
        <div class="relative my-12 sm:my-16 lg:my-20 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-primary-500 to-gold-600 rounded-2xl sm:rounded-3xl"></div>
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-48 h-48 sm:w-64 sm:h-64 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-56 h-56 sm:w-80 sm:h-80 bg-white rounded-full blur-3xl"></div>
            </div>
            <div class="relative p-6 sm:p-8 lg:p-12">
                <div class="text-center mb-6 sm:mb-8">
                    <h2 class="font-display text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2 sm:mb-3">Реквизиты продавца</h2>
                    <p class="text-white/90 text-base sm:text-lg">ИП Редин Дмитрий Витальевич</p>
                </div>
                <div class="max-w-3xl mx-auto space-y-4 text-white">
                    <div class="p-4 sm:p-6 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl sm:rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm sm:text-base text-white/90">
                        <p><span class="text-white/60">ИНН:</span> 323200777288</p>
                        <p><span class="text-white/60">ОГРНИП:</span> 304325504400052</p>
                        <p class="sm:col-span-2 break-words"><span class="text-white/60">Адрес:</span> г. Брянск, ул. Академика Сахарова, 5</p>
                        <p class="sm:col-span-2"><span class="text-white/60">Расчётный счёт:</span> <span class="font-mono">40802810508000007330</span></p>
                        <p class="sm:col-span-2"><span class="text-white/60">Банк:</span> Брянское отделение № 8605 ПАО Сбербанк</p>
                        <p><span class="text-white/60">БИК:</span> 041501601</p>
                        <p><span class="text-white/60">Корр. счёт:</span> <span class="font-mono">30101810400000000601</span></p>
                        <p class="sm:col-span-2">Телефон: <a href="tel:+79102333111" class="hover:text-white transition-colors underline">+7 (910) 233-31-11</a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-12 sm:mt-16">
            <a href="/" class="group inline-flex items-center gap-2 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 border-2 border-primary-400 text-primary-700 rounded-full font-semibold hover:bg-primary-50 transition-all hover:scale-105 shadow-sm text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                <span>Вернуться на главную</span>
            </a>
        </div>
    </div>
</section>

@endsection
