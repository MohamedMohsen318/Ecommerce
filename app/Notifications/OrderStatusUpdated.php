<?php

namespace App\Notifications;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public OrderStatus $status,
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order #{$this->order->id} is now {$this->status->label()}")
            ->greeting("Hi {$notifiable->name},")
            ->line("Your order #{$this->order->id} is now {$this->status->label()}.")
            ->action('View order', $this->orderUrl())
            ->line('Thanks for shopping with us.');
    }

    private function orderUrl(): string
    {
        return rtrim($this->tenantRootUrl(), '/') . route('orders.show', $this->order, false);
    }

    private function tenantRootUrl(): string
    {
        $appUrl = config('app.url', 'http://localhost');
        $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?: 'http';
        $appHost = parse_url($appUrl, PHP_URL_HOST);
        $port = parse_url($appUrl, PHP_URL_PORT);

        $tenant = function_exists('tenant') ? tenant() : null;
        $domain = $tenant?->domains()
            ->when($appHost, fn ($query) => $query->orderByRaw('domain = ? desc', [$appHost]))
            ->value('domain') ?: $appHost ?: 'localhost';

        if ($port && ! str_contains($domain, ':')) {
            $domain .= ":{$port}";
        }

        return "{$scheme}://{$domain}";
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->status->value,
        ];
    }
}
