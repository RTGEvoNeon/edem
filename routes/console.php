<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Если в Telescope накоплен большой бэклог, prune удалит его пачками, а MySQL запишет
 * каждую строку в бинлог (формат ROW) — диск может закончиться. Большую таблицу
 * нужно один раз очистить вручную через TRUNCATE, после чего очистка идёт сама.
 * Порог задаётся в config('telescope.prune_max_rows').
 */
Schedule::command('telescope:prune', ['--hours' => 48])
    ->daily()
    ->when(function (): bool {
        $maxRows = (int) config('telescope.prune_max_rows', 300_000);
        $rows = DB::table('telescope_entries')->count();

        if ($rows > $maxRows) {
            Log::warning('telescope:prune пропущен: в telescope_entries слишком много строк, очистите таблицу вручную через TRUNCATE', [
                'rows' => $rows,
                'max_rows' => $maxRows,
            ]);

            return false;
        }

        return true;
    });
