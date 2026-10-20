<?php

declare(strict_types=1);

namespace Tests\Feature;

use Laravel\Telescope\EntryType;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Tests\TestCase;

class TelescopeFilterTest extends TestCase
{
    /**
     * @param  array<string, mixed>  $content
     */
    private function passes(string $type, array $content = []): bool
    {
        $entry = IncomingEntry::make($content)->type($type);

        return collect(Telescope::$filterUsing)->every(fn (callable $filter): bool => $filter($entry));
    }

    public function test_order_submit_requests_are_recorded(): void
    {
        $this->assertTrue($this->passes(EntryType::REQUEST, ['uri' => '/order/submit', 'response_status' => 200]));
        $this->assertTrue($this->passes(EntryType::REQUEST, ['uri' => '/opt/order/submit', 'response_status' => 200]));
        $this->assertTrue($this->passes(EntryType::REQUEST, ['uri' => '/payment/webhook/yookassa', 'response_status' => 200]));
        $this->assertTrue($this->passes(EntryType::REQUEST, ['uri' => '/payment/return/15?signature=abc', 'response_status' => 200]));
    }

    public function test_failed_requests_are_recorded(): void
    {
        $this->assertTrue($this->passes(EntryType::REQUEST, ['uri' => '/products', 'response_status' => 500]));
    }

    public function test_regular_page_requests_are_not_recorded(): void
    {
        $this->assertFalse($this->passes(EntryType::REQUEST, ['uri' => '/products', 'response_status' => 200]));
        $this->assertFalse($this->passes(EntryType::REQUEST, ['uri' => '/bukety/wedding', 'response_status' => 200]));
    }

    public function test_logs_and_mails_are_recorded(): void
    {
        $this->assertTrue($this->passes(EntryType::LOG, ['level' => 'error', 'message' => 'x']));
        $this->assertTrue($this->passes(EntryType::LOG, ['level' => 'info', 'message' => 'Оформление заказа: заказ создан']));
        $this->assertTrue($this->passes(EntryType::MAIL, ['mailable' => 'NewOrderMail']));
    }

    public function test_views_queries_and_models_are_not_recorded(): void
    {
        $this->assertFalse($this->passes(EntryType::VIEW, ['name' => 'home']));
        $this->assertFalse($this->passes(EntryType::QUERY, ['sql' => 'select 1']));
        $this->assertFalse($this->passes(EntryType::MODEL, ['model' => 'App\\Models\\Product']));
    }
}
