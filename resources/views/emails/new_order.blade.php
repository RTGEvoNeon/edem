<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая заявка №{{ $order->id }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <h2>Новая заявка с сайта №{{ $order->id }}</h2>

    <p><strong>Дата:</strong> {{ $order->created_at?->format('d.m.Y H:i') }}</p>

    <h3>Клиент</h3>
    <ul>
        <li><strong>Имя:</strong> {{ $order->customer_name }}</li>
        <li><strong>Телефон:</strong> {{ $order->customer_phone }}</li>
        @if($order->customer_email)
            <li><strong>Email:</strong> {{ $order->customer_email }}</li>
        @endif
        @if($order->delivery_address)
            <li><strong>Адрес доставки:</strong> {{ $order->delivery_address }}</li>
        @endif
    </ul>

    <h3>Заказ</h3>
    <ul>
        <li><strong>Сумма:</strong> {{ $order->total_amount }} ₽</li>
        @if($order->payment)
            <li><strong>Оплата:</strong> {{ $order->payment->status === 'succeeded' ? 'оплачено онлайн' : $order->payment->status }}</li>
        @endif
        @if(!empty($productUrl))
            <li><strong>Ссылка на товар:</strong> <a href="{{ $productUrl }}">{{ $productUrl }}</a></li>
        @endif
    </ul>

    @if($order->orderItems->isNotEmpty())
        <h3>Состав заказа</h3>
        <ul>
            @foreach($order->orderItems as $item)
                <li>{{ $item->product?->name ?? 'Товар №'.$item->product_id }} — {{ $item->quantity }} × {{ $item->price }} ₽</li>
            @endforeach
        </ul>
    @endif

    @if($order->notes)
        <h3>Комментарий клиента</h3>
        <p>{{ $order->notes }}</p>
    @endif
</body>
</html>
