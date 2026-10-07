<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

/**
 * @property Form $form
 */
class PaymentSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Настройки оплаты';

    protected static ?string $title = 'Настройки оплаты';

    protected static string $view = 'filament.pages.payment-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'pay_enabled' => Setting::get('pay_enabled', false),
            'yookassa_shop_id' => Setting::get('yookassa_shop_id', ''),
            'pay_test_only' => Setting::get('pay_test_only', false),
            'pay_test_contacts' => Setting::get('pay_test_contacts', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('pay_enabled')
                    ->live()
                    ->label('Онлайн-оплата включена')
                    ->helperText('Если выключено, покупатели оформляют заявку без оплаты — как сейчас.'),
                Forms\Components\TextInput::make('yookassa_shop_id')
                    ->label('Идентификатор магазина (shop_id) в ЮKassa')
                    ->required(fn (Forms\Get $get): bool => (bool) $get('pay_enabled'))
                    ->maxLength(255),
                Forms\Components\Toggle::make('pay_test_only')
                    ->live()
                    ->label('Только для тестовых клиентов')
                    ->helperText('Онлайн-оплата доступна только клиентам из списка ниже. Остальные оформляют заявку без оплаты.'),
                Forms\Components\Textarea::make('pay_test_contacts')
                    ->label('Тестовые клиенты')
                    ->helperText('Телефоны или email, по одному в строке. Клиент определяется по данным, которые он вводит в форме заказа.')
                    ->rows(4)
                    ->visible(fn (Forms\Get $get): bool => (bool) $get('pay_test_only')),
                Forms\Components\Placeholder::make('secret_key_note')
                    ->label('Секретный ключ ЮKassa')
                    ->content('Задаётся в переменной окружения YOOKASSA_SECRET_KEY на сервере и никогда не хранится в базе данных или интерфейсе.'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Setting::set('pay_enabled', (bool) $state['pay_enabled']);
        Setting::set('yookassa_shop_id', (string) $state['yookassa_shop_id']);
        Setting::set('pay_test_only', (bool) $state['pay_test_only']);
        Setting::set('pay_test_contacts', (string) ($state['pay_test_contacts'] ?? ''));

        Notification::make()
            ->title('Настройки оплаты сохранены')
            ->success()
            ->send();
    }
}
