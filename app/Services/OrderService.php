<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function checkout(Cart $cart, int $userId, string $shippingAddress): Order
    {
        if ($cart->items->isEmpty()) {
            throw new \RuntimeException('Your cart is empty.');
        }

        $order = DB::transaction(function () use ($cart, $userId, $shippingAddress) {
            $itemIds = $cart->items->pluck('item_id')->filter()->unique()->sort()->values();
            $variantIds = $cart->items->pluck('item_attribute_id')->filter()->unique()->sort()->values();

            $lockedItems = Item::query()->whereIn('id', $itemIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $lockedVariants = ItemAttribute::query()->whereIn('id', $variantIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($cart->items as $cartItem) {
                $item = $lockedItems->get($cartItem->item_id);

                if (! $item) {
                    throw new \RuntimeException('An item in your cart is no longer available.');
                }

                $variant = $cartItem->item_attribute_id ? $lockedVariants->get($cartItem->item_attribute_id) : null;
                $availableStock = $variant ? $variant->stock : $item->stock;

                if ($availableStock < $cartItem->quantity) {
                    $name = $item->translate('en')?->name ?? 'This item';
                    throw new \RuntimeException("{$name} doesn't have enough stock left.");
                }


                $unitPrice = (float) $item->price + (float) ($variant?->price_modifier ?? 0);
                $subtotal += $unitPrice * $cartItem->quantity;

                $orderItemsData[] = [
                    'item_id' => $item->id,
                    'item_attribute_id' => $variant?->id,
                    'item_name' => $item->translate('en')?->name ?? 'Item',
                    'unit_price' => $unitPrice,
                    'quantity' => $cartItem->quantity,
                ];

                if ($variant) {
                    $variant->decrement('stock', $cartItem->quantity);
                } else {
                    $item->decrement('stock', $cartItem->quantity);
                }
            }

            $order = Order::create([
                'user_id' => $userId,
                'status' => OrderStatus::Pending,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'shipping_address' => $shippingAddress,
            ]);

            $order->items()->createMany($orderItemsData);

            $cart->items()->delete();

            return $order;
        });

        return $order->fresh();
    }
}
