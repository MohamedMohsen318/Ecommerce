<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Services\SlackNotifier;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderStatusChangedNotification implements ShouldQueue
{
    public int $tries = 3;

    public function __construct(private SlackNotifier $slack) {}

    public function handle(OrderStatusChanged $event): void
    {
        $this->slack->send(
            "Order #{$event->order->id} for {$event->order->user->email} is now {$event->newStatus->label()}."
        );
    }
}
