<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\YooKassaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class YooKassaWebhookController extends Controller
{
    public function __construct(private readonly YooKassaService $yooKassa) {}

    public function handle(Request $request): Response
    {
        if (! $this->yooKassa->isNotificationIpTrusted($request->ip())) {
            Log::warning('Получено webhook-уведомление ЮKassa с недоверенного IP', [
                'ip' => $request->ip(),
            ]);

            abort(403);
        }

        try {
            $this->yooKassa->handleWebhookNotification($request->all());
        } catch (\Throwable $e) {
            Log::error('Не удалось обработать webhook-уведомление ЮKassa', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);
        }

        return response()->noContent();
    }
}
