<script>
    // Корзина хранится в браузере (localStorage). Цены пересчитываются на сервере при оформлении заказа.
    window.Cart = (function () {
        const KEY = 'edem_cart';

        function read() {
            try {
                const items = JSON.parse(localStorage.getItem(KEY) || '[]');
                return Array.isArray(items) ? items : [];
            } catch (e) {
                return [];
            }
        }

        function write(items) {
            localStorage.setItem(KEY, JSON.stringify(items));
            updateBadges();
            document.dispatchEvent(new CustomEvent('cart:changed'));
        }

        function count() {
            return read().reduce((sum, item) => sum + item.qty, 0);
        }

        function add(product, qty) {
            const items = read();
            const existing = items.find(item => item.id === product.id);
            if (existing) {
                existing.qty = Math.min(99, existing.qty + (qty || 1));
            } else {
                items.push({ ...product, qty: Math.min(99, qty || 1) });
            }
            write(items);
        }

        function setQty(id, qty) {
            const items = read();
            const item = items.find(i => i.id === id);
            if (!item) return;
            item.qty = Math.max(1, Math.min(99, qty));
            write(items);
        }

        function remove(id) {
            write(read().filter(item => item.id !== id));
        }

        function clear() {
            write([]);
        }

        function updateBadges() {
            const total = count();
            document.querySelectorAll('[data-cart-count]').forEach(el => {
                el.textContent = total;
                el.classList.toggle('hidden', total === 0);
            });
        }

        document.addEventListener('DOMContentLoaded', updateBadges);

        return { read, write, count, add, setQty, remove, clear, updateBadges };
    })();

    function addToCart(button) {
        Cart.add({
            id: Number(button.dataset.productId),
            name: button.dataset.productName,
            slug: button.dataset.productSlug,
            price: Number(button.dataset.productPrice),
            image: button.dataset.productImage,
        });

        if (typeof ym !== 'undefined') {
            ym(104582209, 'reachGoal', 'add_to_cart');
        }

        const label = button.querySelector('[data-cart-label]');
        if (label) {
            const original = label.innerHTML;
            label.innerHTML = '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
            setTimeout(() => { label.innerHTML = original; }, 1500);
        }
    }
</script>
