<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;

class TelescopePruneScheduleTest extends TestCase
{
    use DatabaseTransactions;

    private function pruneEvent(): Event
    {
        $events = collect(app(Schedule::class)->events())
            ->filter(fn (Event $event): bool => str_contains((string) $event->command, 'telescope:prune'));

        $this->assertCount(1, $events, 'telescope:prune должен быть в расписании один раз');

        return $events->first();
    }

    private function insertTelescopeEntries(int $count): void
    {
        $rows = [];
        for ($i = 0; $i < $count; $i++) {
            $rows[] = [
                'uuid' => (string) Str::uuid(),
                'batch_id' => (string) Str::uuid(),
                'family_hash' => null,
                'should_display_on_index' => true,
                'type' => 'log',
                'content' => '{}',
                'created_at' => now(),
            ];
        }

        DB::table('telescope_entries')->insert($rows);
    }

    public function test_prune_is_scheduled_daily_with_48_hours_retention(): void
    {
        $event = $this->pruneEvent();

        $this->assertStringContainsString('--hours=48', (string) $event->command);
        $this->assertSame('0 0 * * *', $event->expression);
    }

    public function test_prune_runs_when_telescope_table_is_below_limit(): void
    {
        config(['telescope.prune_max_rows' => 10]);
        $this->insertTelescopeEntries(3);

        $this->assertTrue($this->pruneEvent()->filtersPass($this->app));
    }

    public function test_prune_is_skipped_and_warned_when_telescope_table_exceeds_limit(): void
    {
        config(['telescope.prune_max_rows' => 2]);
        $this->insertTelescopeEntries(3);

        Log::spy();

        $this->assertFalse($this->pruneEvent()->filtersPass($this->app));

        Log::shouldHaveReceived('warning')->once();
    }
}
