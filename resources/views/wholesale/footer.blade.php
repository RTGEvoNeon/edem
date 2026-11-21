<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div>
            <a href="{{ route('home') }}" class="font-display text-2xl font-semibold text-white">Эдемский сад</a>
            <p class="mt-3">Тюльпаны оптом в Брянске</p>
            <p class="mt-2">г. Брянск, ул. Академика Сахарова, 5</p>
        </div>
        <div class="flex flex-col items-start gap-3">
            <span class="font-semibold text-white">Оптовые заказы</span>
            <a href="tel:+79102333111" class="hover:text-white">+7 (910) 233-31-11</a>
        </div>
        <nav aria-label="Ссылки в подвале" class="flex flex-col items-start gap-3">
            <a href="{{ route('home') }}#assortment" class="hover:text-white">Каталог сортов</a>
            <a href="{{ route('home') }}#price" class="hover:text-white">Оптовый прайс</a>
            <a href="{{ route('privacy') }}" class="hover:text-white">Политика конфиденциальности</a>
        </nav>
    </div>
    <p class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 text-sm">ИП Редин Дмитрий Витальевич · ИНН 323200777288 · ОГРНИП 304325504400052</p>
</footer>
