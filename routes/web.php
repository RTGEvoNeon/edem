<?php

declare(strict_types=1);

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WholesaleController;
use App\Http\Controllers\WholesaleImportController;
use App\Http\Controllers\WholesaleOrderController;
use App\Http\Controllers\YooKassaWebhookController;
use Illuminate\Support\Facades\Route;

// Статические страницы
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/delivery', [PageController::class, 'delivery'])->name('delivery');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::get('/cart/products', [CartController::class, 'products'])->name('cart.products');
// Каталог товаров
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

// Оптовый раздел
Route::get('/opt', [WholesaleController::class, 'index'])->name('wholesale.index');
Route::get('/opt/{slug}', [WholesaleController::class, 'show'])->name('wholesale.show');
Route::post('/opt/order/submit', [WholesaleOrderController::class, 'submit'])->name('wholesale.order.submit');

// Оформление заказа
Route::post('/order/submit', [OrderController::class, 'submit'])->name('order.submit');

// Оплата ЮKassa
Route::get('/payment/return/{order}', [PaymentController::class, 'return'])->middleware('signed')->name('payment.return');
Route::get('/payment/retry/{order}', [PaymentController::class, 'retry'])->middleware('signed')->name('payment.retry');
Route::post('/payment/webhook/yookassa', [YooKassaWebhookController::class, 'handle'])->name('payment.webhook.yookassa');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// TODO: Форма заказа кастомного букета (временно отключена)
// Route::get('/custom-bouquet', function () {
//     return view('custom-bouquet');
// })->name('custom-bouquet.show');
//
// Route::post('/custom-bouquet/submit', function () {
//     // TODO: Добавить отправку в Telegram
//     return redirect('/custom-bouquet')->with('success', 'Спасибо! Мы скоро с вами свяжемся!');
// })->name('custom-bouquet.submit');

// Старая самописная админка импорта Excel (список и toggle товаров переехали в Filament-панель /admin)
Route::prefix('admin-old')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Розничные товары
    Route::get('/products/import', [ProductImportController::class, 'showForm'])->name('products.import');
    Route::post('/products/import', [ProductImportController::class, 'import'])->name('products.import.process');
    Route::get('/products/import/template', [ProductImportController::class, 'downloadTemplate'])->name('products.import.template');

    // Оптовые товары
    Route::get('/wholesale/import', [WholesaleImportController::class, 'showForm'])->name('wholesale.import');
    Route::post('/wholesale/import', [WholesaleImportController::class, 'import'])->name('wholesale.import.process');
    Route::get('/wholesale/import/template', [WholesaleImportController::class, 'downloadTemplate'])->name('wholesale.import.template');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::patch('/dashboard/profile', [ProfileController::class, 'update'])->name('dashboard.profile.update');
    Route::delete('/dashboard/profile', [ProfileController::class, 'destroy'])->name('dashboard.profile.destroy');
    Route::put('/dashboard/password', [ProfileController::class, 'updatePassword'])->name('dashboard.password.update');
});

require __DIR__.'/auth.php';
