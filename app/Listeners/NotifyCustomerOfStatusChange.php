<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\OrderStatusUpdated;

class NotifyCustomerOfStatusChange
{
    public function handle(OrderStatusChanged $event): void
    {
        $event->order->user->notify(new OrderStatusUpdated($event->order, $event->newStatus));

        \Illuminate\Support\Facades\Cache::forget('unread_notifications_count_' . $event->order->user_id);
    }
}
