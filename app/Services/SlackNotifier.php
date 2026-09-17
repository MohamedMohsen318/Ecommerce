<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SlackNotifier
{
    public function send(string $message): void
    {
        $webhookUrl = config('services.slack.notifications.webhook_url');

        if (! $webhookUrl) {
            return;
        }

        try {
            $response = Http::timeout(5)->post($webhookUrl, ['text' => $message]);

            if ($response->failed()) {
                Log::warning('Slack notification failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (Throwable $exception) {
            Log::warning('Slack notification could not be sent.', [
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
