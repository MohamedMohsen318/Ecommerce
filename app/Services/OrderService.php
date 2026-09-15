<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\LoyaltyPointTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private const POINTS_PER_DOLLAR_EARNED = 1;

    private const POINTS_PER_DOLLAR_REDEEMED = 100;

    public function checkout(
        Cart $cart,
        int $userId,
        string $shippingAddress,
        ?string $discountCode = null,
        int $redeemPoints = 0,
    ): Order {
        if ($cart->items->isEmpty()) {
            throw new \RuntimeException('Your cart is empty.');
        }

        $order = DB::transaction(function () use ($cart, $userId, $shippingAddress, $discountCode, $redeemPoints) {
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
                    $name = $item->translate('en')?->name ?? 'This item';
                    throw new \RuntimeException("{$name} doesn't have enough stock left.");
                }

                $unitPrice = $item->effectivePrice() + (float) ($variant?->price_modifier ?? 0);
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

            [$discount, $discountAmount] = $this->applyDiscount($discountCode, $subtotal);

            $pointsDiscountAmount = $this->pointsDiscountAmount($userId, $redeemPoints, $subtotal - $discountAmount);

            $order = Order::create([
                'user_id' => $userId,
                'discount_id' => $discount?->id,
                'status' => OrderStatus::Pending,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'points_redeemed' => $redeemPoints,
                'points_discount_amount' => $pointsDiscountAmount,
                'total' => max($subtotal - $discountAmount - $pointsDiscountAmount, 0),
                'shipping_address' => $shippingAddress,
            ]);

            $order->items()->createMany($orderItemsData);

            if ($redeemPoints > 0) {
                LoyaltyPointTransaction::create([
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'points' => -$redeemPoints,
                    'reason' => 'redeemed_at_checkout',
                ]);
            }

            $this->awardPoints($order);

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

    protected function pointsDiscountAmount(int $userId, int $points, float $maxDiscountable): float
    {
        if ($points <= 0) {
            return 0.0;
        }

        $balance = (int) LoyaltyPointTransaction::where('user_id', $userId)->lockForUpdate()->sum('points');

        if ($points > $balance) {
            throw new \RuntimeException("You don't have enough loyalty points.");
        }

        return round(min($points / self::POINTS_PER_DOLLAR_REDEEMED, $maxDiscountable), 2);
    }

    protected function awardPoints(Order $order): void
    {
        $earned = (int) floor((float) $order->total * self::POINTS_PER_DOLLAR_EARNED);

        if ($earned > 0) {
            LoyaltyPointTransaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'points' => $earned,
                'reason' => 'order_placed',
            ]);
        }
    }
}
