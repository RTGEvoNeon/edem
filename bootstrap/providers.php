<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\SeoServiceProvider;
use App\Providers\TelescopeServiceProvider;

return [
    AppServiceProvider::class,
    SeoServiceProvider::class,
    TelescopeServiceProvider::class,
];
