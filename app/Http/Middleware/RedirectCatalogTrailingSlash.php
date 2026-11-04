<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectCatalogTrailingSlash
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->getPathInfo();

        if (($request->isMethod('GET') || $request->isMethod('HEAD'))
            && str_ends_with($path, '/')
            && $request->is('products', 'product/*', 'bukety/*', 'opt', 'opt/*')) {
            $url = $request->getSchemeAndHttpHost().$request->getBaseUrl().rtrim($path, '/');
            $query = $request->server->get('QUERY_STRING', '');

            return redirect()->to($url.($query !== '' ? '?'.$query : ''), 301);
        }

        return $next($request);
    }
}
