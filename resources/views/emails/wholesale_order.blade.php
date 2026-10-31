<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая оптовая заявка</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <h2>Новая оптовая заявка с сайта</h2>

    <p><strong>Дата:</strong> {{ now()->format('d.m.Y H:i') }}</p>

    <h3>Клиент</h3>
    <ul>
        <li><strong>Имя:</strong> {{ $customer['name'] }}</li>
        <li><strong>Телефон:</strong> {{ $customer['phone'] }}</li>
    </ul>

    <h3>Заказ</h3>
    <ul>
        <li><strong>Сорт:</strong> {{ $product->name }}</li>
        <li><strong>Количество:</strong> {{ number_format($quantity, 0, '', ' ') }} шт</li>
        <li><strong>Цена за штуку:</strong> {{ number_format($pricePerUnit, 0, '', ' ') }} ₽</li>
        <li><strong>Сумма:</strong> {{ number_format($total, 0, '', ' ') }} ₽</li>
        <li><strong>Ссылка на товар:</strong> <a href="{{ route('wholesale.show', $product->slug) }}">{{ route('wholesale.show', $product->slug) }}</a></li>
    </ul>

    @if($customer['notes'] !== '')
        <h3>Комментарий клиента</h3>
        <p>{{ $customer['notes'] }}</p>
    @endif
</body>
</html>
