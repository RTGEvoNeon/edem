@extends('layouts.app')

@section('content')
<section class="max-w-4xl mx-auto px-6 lg:px-8 py-16 lg:py-24">
    <h1 class="font-display text-3xl lg:text-4xl font-bold text-gray-900 mb-10">Корзина</h1>

    <div id="cart-empty" class="hidden text-center py-16">
        <div class="text-6xl mb-6">🌸</div>
        <p class="text-xl text-gray-600 mb-8">В корзине пока ничего нет</p>
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-primary-600 text-white rounded-full font-semibold shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
            Перейти в каталог
        </a>
    </div>

    <div id="cart-success" class="hidden text-center py-16">
        <div class="text-6xl mb-6">🌸</div>
        <h2 class="font-display text-2xl font-bold text-gray-900 mb-3">Заявка принята!</h2>
        <p class="text-lg text-gray-600">Мы свяжемся с вами в ближайшее время</p>
    </div>

    <div id="cart-content" class="hidden space-y-10">
        <div id="cart-items" class="space-y-4"></div>

        <div class="flex justify-between items-center bg-accent-50 rounded-2xl p-6">
            <span class="text-lg font-medium text-gray-700">Итого</span>
            <span class="text-2xl font-semibold text-primary-600"><span id="cart-total">0</span> ₽</span>
        </div>

        <form id="cart-form" class="space-y-4 bg-white rounded-3xl p-6 lg:p-8 shadow-lg border border-accent-200/50">
            <h2 class="text-2xl font-display font-semibold text-gray-900 mb-2">Оформление заказа</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ваше имя *</label>
                <input type="text" name="customer_name" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 transition-all" placeholder="Как к вам обращаться?">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Телефон *</label>
                <input type="tel" name="customer_phone" required maxlength="20" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 transition-all" placeholder="+7 (___) ___-__-__">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="customer_email" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 transition-all" placeholder="Для чека и подтверждения заказа">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Адрес доставки</label>
                <input type="text" name="delivery_address" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 transition-all" placeholder="Улица, дом, квартира">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Комментарий</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 transition-all" placeholder="Пожелания к букету, время доставки..."></textarea>
            </div>

            <div id="cart-error" class="hidden bg-red-50 border-2 border-red-200 rounded-xl p-4 text-red-700 text-sm"></div>

            <button type="submit" class="btn-organic w-full py-4 animated-gradient text-white font-semibold text-lg hover:glow-primary focus:outline-none focus:ring-4 focus:ring-primary-200">
                Оформить заказ
            </button>
        </form>
    </div>
</section>

<script>
    (function () {
        const itemsBox = document.getElementById('cart-items');
        const fmt = n => new Intl.NumberFormat('ru-RU').format(n);
        let products = {};

        function el(tag, className, text) {
            const node = document.createElement(tag);
            if (className) node.className = className;
            if (text !== undefined) node.textContent = text;
            return node;
        }

        function render() {
            const items = Cart.read().filter(item => products[item.id]);
            const hasItems = items.length > 0;

            document.getElementById('cart-empty').classList.toggle('hidden', hasItems);
            document.getElementById('cart-content').classList.toggle('hidden', !hasItems);

            itemsBox.replaceChildren();
            let total = 0;

            items.forEach(item => {
                const product = products[item.id];
                total += product.price * item.qty;

                const row = el('div', 'flex items-center gap-4 bg-white rounded-2xl p-4 shadow border border-accent-200/50');

                const img = el('img', 'w-20 h-20 rounded-xl object-cover flex-shrink-0');
                img.src = product.image;
                img.alt = product.name;
                row.appendChild(img);

                const info = el('div', 'flex-1 min-w-0');
                const link = el('a', 'font-semibold text-gray-900 hover:text-primary-600 block truncate', product.name);
                link.href = '/product/' + encodeURIComponent(product.slug);
                info.appendChild(link);
                info.appendChild(el('div', 'text-primary-600 font-medium', fmt(product.price) + ' ₽'));
                row.appendChild(info);

                const qty = el('div', 'flex items-center gap-2');
                const minus = el('button', 'w-8 h-8 rounded-full border-2 border-gray-200 hover:border-primary-400', '−');
                minus.type = 'button';
                minus.onclick = () => Cart.setQty(item.id, item.qty - 1);
                const plus = el('button', 'w-8 h-8 rounded-full border-2 border-gray-200 hover:border-primary-400', '+');
                plus.type = 'button';
                plus.onclick = () => Cart.setQty(item.id, item.qty + 1);
                qty.append(minus, el('span', 'w-6 text-center font-medium', String(item.qty)), plus);
                row.appendChild(qty);

                const remove = el('button', 'text-gray-400 hover:text-red-500 transition-colors', '✕');
                remove.type = 'button';
                remove.setAttribute('aria-label', 'Удалить');
                remove.onclick = () => Cart.remove(item.id);
                row.appendChild(remove);

                itemsBox.appendChild(row);
            });

            document.getElementById('cart-total').textContent = fmt(total);
        }

        async function loadProducts() {
            const ids = Cart.read().map(item => item.id);
            if (ids.length === 0) {
                render();
                return;
            }

            try {
                const response = await fetch('{{ route('cart.products') }}?ids=' + ids.join(','), {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await response.json();
                products = Object.fromEntries(data.products.map(p => [p.id, p]));

                // Убираем товары, которых больше нет в наличии.
                Cart.read().filter(item => !products[item.id]).forEach(item => Cart.remove(item.id));
            } catch (e) {
                // Если сервер недоступен, показываем сохранённые в браузере данные.
                products = Object.fromEntries(Cart.read().map(i => [i.id, i]));
            }

            render();
        }

        document.addEventListener('cart:changed', render);
        document.addEventListener('DOMContentLoaded', loadProducts);

        document.getElementById('cart-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const button = this.querySelector('button[type="submit"]');
            const errorBox = document.getElementById('cart-error');
            const formData = new FormData(this);

            Cart.read().forEach((item, index) => {
                formData.append(`items[${index}][product_id]`, item.id);
                formData.append(`items[${index}][quantity]`, item.qty);
            });

            button.disabled = true;
            button.textContent = 'Отправка...';
            errorBox.classList.add('hidden');

            try {
                const response = await fetch('/order/submit', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Произошла ошибка');
                }

                if (typeof ym !== 'undefined') {
                    ym(104582209, 'reachGoal', 'order_submitted');
                }

                Cart.clear();

                if (data.payment_url) {
                    window.location.href = data.payment_url;
                    return;
                }

                document.getElementById('cart-content').classList.add('hidden');
                document.getElementById('cart-empty').classList.add('hidden');
                document.getElementById('cart-success').classList.remove('hidden');
            } catch (error) {
                errorBox.textContent = error.message;
                errorBox.classList.remove('hidden');
                button.disabled = false;
                button.textContent = 'Оформить заказ';
            }
        });
    })();
</script>
@endsection
