<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\YooKassaService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery\MockInterface;
use Tests\TestCase;

class YooKassaWebhookControllerTest extends TestCase
{
    use DatabaseTransactions;

    private const PAYLOAD = [
        'type' => 'notification',
        'event' => 'payment.succeeded',
        'object' => ['id' => 'test-payment-id', 'status' => 'succeeded'],
    ];

    public function test_webhook_rejects_request_from_untrusted_ip(): void
    {
        $this->mock(YooKassaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('isNotificationIpTrusted')->with('1.2.3.4')->andReturn(false);
            $mock->shouldNotReceive('handleWebhookNotification');
        });

        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->postJson('/payment/webhook/yookassa', self::PAYLOAD);

        $response->assertForbidden();
    }

    public function test_webhook_processes_notification_from_trusted_ip(): void
    {
        $this->mock(YooKassaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('isNotificationIpTrusted')->with('185.71.76.1')->andReturn(true);
            $mock->shouldReceive('handleWebhookNotification')->once()->with(self::PAYLOAD);
        });

        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => '185.71.76.1'])
            ->postJson('/payment/webhook/yookassa', self::PAYLOAD);

        $response->assertNoContent();
    }

    public function test_webhook_returns_no_content_even_if_processing_throws(): void
    {
        $this->mock(YooKassaService::class, function (MockInterface $mock) {
            $mock->shouldReceive('isNotificationIpTrusted')->andReturn(true);
            $mock->shouldReceive('handleWebhookNotification')->once()->andThrow(new \RuntimeException('boom'));
        });

        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => '185.71.76.1'])
            ->postJson('/payment/webhook/yookassa', self::PAYLOAD);

        $response->assertNoContent();
    }
}
