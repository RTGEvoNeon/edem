<header class="sticky top-0 z-50 bg-accent-50/95 backdrop-blur-xl border-b border-accent-200/50">
    <nav aria-label="Основная навигация" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img src="/images/logo.jpg" alt="Эдемский сад" class="w-12 h-12 rounded-full object-cover" width="48" height="48">
            <span class="flex flex-col">
                <span class="font-display text-xl sm:text-2xl font-semibold text-gray-900">Эдемский сад</span>
                <span class="text-xs text-sage-700 tracking-wider uppercase">Тюльпаны оптом</span>
            </span>
        </a>
        <a href="tel:+79102333111" class="font-semibold text-sage-700 hover:text-sage-600 whitespace-nowrap">+7 (910) 233-31-11</a>
        <div class="w-full lg:w-auto flex flex-wrap items-center gap-x-5 gap-y-3 text-sm font-medium text-gray-700">
            <a href="{{ route('home') }}#assortment" class="hover:text-sage-700">Каталог сортов</a>
            <a href="{{ route('home') }}#price" class="hover:text-sage-700">Оптовый прайс</a>
            <a href="{{ route('home') }}#contacts" class="hover:text-sage-700">Контакты</a>
        </div>
    </nav>
</header>
