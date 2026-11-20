<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWholesaleOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('/', 'opt', 'opt/*', 'contacts', 'privacy', 'oferta',
            'sitemap.xml', 'robots.txt', 'up', 'admin', 'admin/*',
            'livewire', 'livewire/*', 'login', 'logout', 'payment/webhook/yookassa')) {
            return $next($request);
        }

        return response('<!doctype html><html lang="ru"><meta charset="utf-8"><meta name="robots" content="noindex"><title>Страница закрыта</title><body><h1>Розничный раздел закрыт</h1><p><a href="/">Тюльпаны оптом</a></p></body></html>', 410)
            ->header('Cache-Control', 'no-store')
            ->header('X-Robots-Tag', 'noindex');
    }
}
