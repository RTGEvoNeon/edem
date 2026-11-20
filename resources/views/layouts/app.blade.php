<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#e96d3f">

    @if($yandexVerification = config('services.yandex_webmaster.verification'))
        <meta name="yandex-verification" content="{{ $yandexVerification }}">
    @endif
    @if($googleVerification = config('services.google_search_console.verification'))
        <meta name="google-site-verification" content="{{ $googleVerification }}">
    @endif

    @stack('head')
    @stack('seo')

    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg?v=3">
    <link rel="alternate icon" type="image/x-icon" href="/favicon.ico?v=3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Эдемский сад">

    <!-- Fonts (async, не блокируют FCP) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Commissioner:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Commissioner:wght@300;400;500;600;700&display=swap" rel="stylesheet"></noscript>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Schema.org JSON-LD -->
    @stack('schema')
</head>

<body class="min-h-screen bg-accent-50 text-gray-900 grain-texture">
    @include('wholesale.navigation')
    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>
    @include('wholesale.footer')
    <!-- Yandex.Metrika (в конце body — не блокирует FCP/LCP) -->
    <script type="text/javascript">
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) {
                    return;
                }
            }
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js?id=104582209', 'ym');

        ym(104582209, 'init', {
            ssr: true,
            webvisor: true,
            clickmap: true,
            ecommerce: "dataLayer",
            accurateTrackBounce: true,
            trackLinks: true
        });
    </script>
    <!-- /Yandex.Metrika -->

    @stack('scripts')

    <noscript>
        <div><img src="https://mc.yandex.ru/watch/104582209" style="position:absolute; left:-9999px;" alt="" /></div>
    </noscript>


    <!-- Cookie consent -->
    @include('components.cookie-consent')
</body>

</html>
