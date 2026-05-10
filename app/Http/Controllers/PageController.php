<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\Seo;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Главная страница
     */
    public function home(): View
    {
        // SEO для главной страницы
        Seo::setTitle('Свежие букеты с доставкой по Брянску')
            ->setDescription('Цветочная мастерская Эдемский сад создаёт уникальные букеты для особенных моментов. Доставка цветов по Брянску в день заказа. ✓ Свежие цветы ✓ Собственное производство')
            ->setKeywords(['цветы Брянск', 'букеты Брянск', 'доставка цветов', 'купить цветы Брянск', 'заказать букет'])
            ->setCanonical(url('/'))
            ->setImage('/images/logo.jpg', 'Эдемский сад - Цветочная мастерская')
            ->setLocalBusinessSchema([
                'name' => 'Эдемский сад',
                'description' => 'Цветочная мастерская для особенных моментов',
                'telephone' => '+79532929246',
                'city' => 'Брянск',
                'region' => 'Брянская область',
                'priceRange' => '₽₽',
                'openingHours' => 'Mo-Su 09:00-00:00',
            ])
            ->setBreadcrumbSchema([
                ['name' => 'Главная', 'url' => url('/')],
            ]);

        $randomProducts = Product::available()
            ->withImages()
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('home', compact('randomProducts'));
    }

    /**
     * Страница доставки
     */
    public function delivery(): View
    {
        Seo::setTitle('Доставка цветов по Брянску')
            ->setDescription('Бесплатная доставка букетов по Брянску в день заказа. Работаем с 9:00 до 00:00. Доставка в удобное время, бережная транспортировка, свежие цветы.')
            ->setKeywords(['доставка цветов Брянск', 'доставка букетов', 'курьерская доставка цветов'])
            ->setCanonical(route('delivery'))
            ->setBreadcrumbSchema([
                ['name' => 'Главная', 'url' => url('/')],
                ['name' => 'Доставка', 'url' => route('delivery')],
            ]);

        return view('delivery');
    }

    /**
     * Страница О нас
     */
    public function about(): View
    {
        Seo::setTitle('О нас — Семейная цветочная мастерская')
            ->setDescription('Эдемский сад — семейная цветочная мастерская в Брянске. Создаём букеты с душой и любовью для ваших особенных моментов. Более 500 счастливых клиентов.')
            ->setKeywords(['цветочная мастерская Брянск', 'о цветочном магазине', 'флористы Брянск'])
            ->setCanonical(route('about'))
            ->setBreadcrumbSchema([
                ['name' => 'Главная', 'url' => url('/')],
                ['name' => 'О нас', 'url' => route('about')],
            ]);

        return view('about');
    }

    /**
     * Страница контактов
     */
    public function contacts(): View
    {
        Seo::setTitle('Контакты — Связаться с нами')
            ->setDescription('Контакты цветочной мастерской Эдемский сад в Брянске. Телефон: +7 (953) 292-92-46. Telegram. Работаем ежедневно с 9:00 до 00:00.')
            ->setKeywords(['контакты цветочного магазина', 'телефон цветы Брянск', 'где купить цветы Брянск'])
            ->setCanonical(route('contacts'))
            ->setBreadcrumbSchema([
                ['name' => 'Главная', 'url' => url('/')],
                ['name' => 'Контакты', 'url' => route('contacts')],
            ]);

        return view('contacts');
    }

    /**
     * Страница политики конфиденциальности
     */
    public function privacy(): View
    {
        Seo::setTitle('Политика конфиденциальности')
            ->setDescription('Политика конфиденциальности цветочной мастерской Эдемский сад. Правила обработки персональных данных.')
            ->setRobots('noindex, follow') // Служебная страница, не индексируем
            ->setCanonical(route('privacy'));

        return view('privacy');
    }

    /**
     * Личный кабинет (Dashboard)
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->with('orderItems')
            ->get();

        Seo::setTitle('Личный кабинет')
            ->setDescription('Личный кабинет — просмотр заказов и управление профилем')
            ->setRobots('noindex, follow');

        return view('dashboard', compact('orders'));
    }
}
