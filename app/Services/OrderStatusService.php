<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Models\Admin;
use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\LoyaltyPointTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderStatusService
{
    /**
     * @var array<string, list<string>>
     */
    private const TRANSITIONS = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['preparing', 'cancelled'],
        'preparing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ];

    /**
     * @return \Illuminate\Support\Collection<int, OrderStatus>
     */
    public function allowedTransitions(Order $order): \Illuminate\Support\Collection
    {
        return collect(self::TRANSITIONS[$order->status->value] ?? [])
            ->map(fn (string $value) => OrderStatus::from($value));
    }

    public function transition(Order $order, OrderStatus $newStatus, ?Admin $admin = null, ?string $note = null): Order
    {
        $allowed = self::TRANSITIONS[$order->status->value] ?? [];

        if (! in_array($newStatus->value, $allowed, true)) {
            throw new \RuntimeException("Can't move an order from {$order->status->label()} to {$newStatus->label()}.");
        }

        DB::transaction(function () use ($order, $newStatus, $admin, $note) {
            if ($newStatus === OrderStatus::Cancelled) {
                $this->restoreStock($order);
                $this->reversePoints($order);
            }

            $order->update(['status' => $newStatus]);

            $order->statusHistory()->create([
                'status' => $newStatus,
                'note' => $note,
                'changed_by_admin_id' => $admin?->id,
            ]);
        });

        OrderStatusChanged::dispatch($order, $newStatus);

        return $order->fresh();
    }

    protected function restoreStock(Order $order): void
    {
        foreach ($order->items as $orderItem) {
            if ($orderItem->item_attribute_id) {
                ItemAttribute::whereKey($orderItem->item_attribute_id)->increment('stock', $orderItem->quantity);
            } elseif ($orderItem->item_id) {
                Item::whereKey($orderItem->item_id)->increment('stock', $orderItem->quantity);
            }
        }
    }

    protected function reversePoints(Order $order): void
    {
        $earnedTransaction = LoyaltyPointTransaction::query()
            ->where('order_id', $order->id)
            ->where('reason', 'order_placed')
            ->first();

        if ($earnedTransaction) {
            LoyaltyPointTransaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'points' => -$earnedTransaction->points,
                'reason' => 'order_cancelled_earned_reversed',
            ]);
        }

        if ($order->points_redeemed > 0) {
            LoyaltyPointTransaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'points' => $order->points_redeemed,
                'reason' => 'order_cancelled_redeemed_returned',
            ]);
        }
    }
}
