<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public function checkout(Cart $cart, int $userId, string $shippingAddress, ?string $discountCode = null): Order
    {
        if ($cart->items->isEmpty()) {
            throw new \RuntimeException('Your cart is empty.');
        }

        $order = DB::transaction(function () use ($cart, $userId, $shippingAddress, $discountCode) {
            $itemIds = $cart->items->pluck('item_id')->filter()->unique()->sort()->values();
            $variantIds = $cart->items->pluck('item_attribute_id')->filter()->unique()->sort()->values();

            $lockedItems = Item::query()->whereIn('id', $itemIds)->orderBy('id')->lockForUpdate()->with('flashSales')->get()->keyBy('id');
            $lockedVariants = ItemAttribute::query()->whereIn('id', $variantIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($cart->items as $cartItem) {
                $item = $lockedItems->get($cartItem->item_id);

                if (! $item) {
                    throw new \RuntimeException('An item in your cart is no longer available.');
                }

                $variant = $cartItem->item_attribute_id ? $lockedVariants->get($cartItem->item_attribute_id) : null;

                if ($cartItem->item_attribute_id && (! $variant || $variant->item_id !== $item->id)) {
                    throw new \RuntimeException('Something in your cart is no longer valid. Please review your cart.');
                }
                $availableStock = $variant ? $variant->stock : $item->stock;

                if ($availableStock < $cartItem->quantity) {
                    $name = $item->translate()?->name ?? 'This item';
                    throw new \RuntimeException("{$name} doesn't have enough stock left.");
                }

                $unitPrice = $item->effectivePrice() + (float) ($variant?->price_modifier ?? 0);
                $subtotal += $unitPrice * $cartItem->quantity;

                $orderItemsData[] = [
                    'item_id' => $item->id,
                    'item_attribute_id' => $variant?->id,
                    'item_name' => $item->translate()?->name ?? 'Item',
                    'unit_price' => $unitPrice,
                    'quantity' => $cartItem->quantity,
                ];

                if ($variant) {
                    $variant->decrement('stock', $cartItem->quantity);
                } else {
                    $item->decrement('stock', $cartItem->quantity);
                }
            }

            [$discount, $discountAmount] = $this->applyDiscount($discountCode, $subtotal);

            $order = Order::create([
                'user_id' => $userId,
                'discount_id' => $discount?->id,
                'status' => OrderStatus::Pending,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total' => max($subtotal - $discountAmount, 0),
                'shipping_address' => $shippingAddress,
            ]);

            $order->items()->createMany($orderItemsData);

            $cart->items()->delete();

            return $order;
        });

        return $order->fresh();
    }

    /**
     * @return array{0: ?Discount, 1: float}
     */
    protected function applyDiscount(?string $code, float $subtotal): array
    {
        if (! $code) {
            return [null, 0.0];
        }

        $discount = Discount::query()->where('code', strtoupper($code))->lockForUpdate()->first();
        if (! $discount || ! $discount->isValid()) {
            throw new \RuntimeException('This discount code is not valid.');
        }

        $amount = $discount->amountFor($subtotal);
        $discount->increment('used_count');

        return [$discount, $amount];
    }
}
