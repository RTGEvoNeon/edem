@extends('layouts.app')

@if($state === 'pending')
    @push('head')
        <meta http-equiv="refresh" content="5">
    @endpush
@endif

@section('content')
<section class="max-w-3xl mx-auto px-6 lg:px-8 py-24 lg:py-32 text-center">
    <div class="space-y-6">
        @if($state === 'succeeded')
            <div class="text-6xl">🌸</div>
            <h1 class="font-display text-3xl lg:text-4xl font-bold text-gray-900">Заказ №{{ $order->id }} оплачен</h1>
            <p class="text-lg text-gray-600 leading-relaxed">
                Спасибо! Мы получили оплату.
            </p>
        @elseif($state === 'canceled')
            <div class="text-6xl">😔</div>
            <h1 class="font-display text-3xl lg:text-4xl font-bold text-gray-900">Оплата не прошла</h1>
            <p class="text-lg text-gray-600 leading-relaxed">
                Платёж по заказу №{{ $order->id }} не был завершён. Деньги не списаны — можно попробовать ещё раз.
            </p>
            @if(session('error'))
                <p class="text-red-600">{{ session('error') }}</p>
            @endif
            <a href="{{ $retryUrl }}" class="inline-flex items-center gap-3 px-8 py-4 bg-primary-600 text-white rounded-full font-semibold shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
                <span>Оплатить ещё раз</span>
            </a>
        @else
            <div class="text-6xl">⏳</div>
            <h1 class="font-display text-3xl lg:text-4xl font-bold text-gray-900">Проверяем оплату заказа №{{ $order->id }}</h1>
            <p class="text-lg text-gray-600 leading-relaxed">
                Ждём подтверждения от банка. Страница обновится автоматически.
            </p>
        @endif

        <div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 px-8 py-4 {{ $state === 'canceled' ? 'text-gray-700 hover:text-primary-600' : 'bg-primary-600 text-white rounded-full font-semibold shadow-xl hover:shadow-2xl hover:scale-105' }} transition-all">
                <span>Вернуться на главную</span>
            </a>
        </div>
    </div>
</section>
@endsection
