<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        $isLocal = $this->app->environment('local');

        // На проде пишем только нужное: ошибки и оформление заказа. Иначе таблица
        // разрастается на гигабайты (просмотры, запросы и модели вручную отключены в .env).
        Telescope::filter(function (IncomingEntry $entry) use ($isLocal): bool {
            return $isLocal
                || $entry->isReportableException()
                || $entry->isFailedRequest()
                || $entry->isFailedJob()
                || $entry->isScheduledTask()
                || $entry->hasMonitoredTag()
                || $entry->type === EntryType::LOG        // ошибки и ход оформления заказа/оплаты (Log::info/error)
                || $entry->type === EntryType::MAIL       // письма о заказах
                || $this->isOrderFlowRequest($entry);
        });
    }

    /**
     * Запросы оформления заказа и оплаты: отправка заказа, возврат/повтор оплаты, webhook ЮKassa.
     */
    protected function isOrderFlowRequest(IncomingEntry $entry): bool
    {
        if ($entry->type !== EntryType::REQUEST) {
            return false;
        }

        $uri = (string) ($entry->content['uri'] ?? '');

        return Str::is(['/order/submit*', '/opt/order/submit*', '/payment/*'], $uri);
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        // Персональные данные покупателей (розница и опт) в журнал не попадают.
        Telescope::hideRequestParameters([
            '_token',
            'customer_name',
            'customer_phone',
            'customer_email',
            'delivery_address',
            'notes',
        ]);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function (User $user) {
            return in_array($user->email, [
                'mat8765@mail.ru',
            ]);
        });
    }
}
