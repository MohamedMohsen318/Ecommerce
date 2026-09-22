<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\LoyaltyPointTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected function pointsPerDollarEarned(): int
    {
        return (int) config('loyalty.points_per_dollar_earned');
    }

    protected function pointsPerDollarRedeemed(): int
    {
        return (int) config('loyalty.points_per_dollar_redeemed');
    }

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

        return DB::transaction(function () use (
            $cart,
            $userId,
            $shippingAddress,
            $discountCode,
            $redeemPoints
        ) {
            $lockedItems = $this->lockItems($cart);

            $lockedVariants = $this->lockVariants($cart);

            [$subtotal, $orderItemsData] = $this->prepareOrderItems(
                $cart,
                $lockedItems,
                $lockedVariants
            );

            [$discount, $discountAmount] = $this->applyDiscount(
                $discountCode,
                $subtotal,
                $userId
            );

            $maxPointsDiscount = max(
                $subtotal - $discountAmount,
                0
            );

            $pointsDiscountAmount = $this->pointsDiscountAmount(
                $userId,
                $redeemPoints,
                $maxPointsDiscount
            );

            $actualPointsUsed = (int) min(
                $redeemPoints,
                ceil(
                    $pointsDiscountAmount
                    * $this->pointsPerDollarRedeemed()
                )
            );

            $order = $this->createOrder(
                $userId,
                $shippingAddress,
                $subtotal,
                $discount,
                $discountAmount,
                $actualPointsUsed,
                $pointsDiscountAmount
            );

            $order->items()->createMany($orderItemsData);

            $this->redeemPoints(
                $userId,
                $order->id,
                $actualPointsUsed
            );

            $this->awardPoints($order);

            $cart->items()->delete();

            return $order;
        })->fresh();
    }

    protected function lockItems(Cart $cart)
    {
        $itemIds = $cart->items
            ->pluck('item_id')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return Item::query()
            ->whereIn('id', $itemIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->with('flashSales')
            ->get()
            ->keyBy('id');
    }

    protected function lockVariants(Cart $cart)
    {
        $variantIds = $cart->items
            ->pluck('item_variant_id')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return ItemVariant::query()
            ->whereIn('id', $variantIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->with('values.type')
            ->get()
            ->keyBy('id');
    }

    protected function prepareOrderItems(
        Cart $cart,
             $lockedItems,
             $lockedVariants
    ): array {
        $subtotal = 0.0;

        $orderItemsData = [];

        foreach ($cart->items as $cartItem) {
            $item = $lockedItems->get($cartItem->item_id);

            if (! $item) {
                throw new \RuntimeException(
                    'An item in your cart is no longer available.'
                );
            }

            $variant = $cartItem->item_variant_id
                ? $lockedVariants->get($cartItem->item_variant_id)
                : null;

            $this->validateVariant(
                $cartItem->item_variant_id,
                $variant,
                $item
            );

            $availableStock = $variant
                ? $variant->stock
                : $item->stock;

            if ($availableStock < $cartItem->quantity) {
                $name = $item->translate('en')?->name ?? 'This item';

                throw new \RuntimeException(
                    "{$name} doesn't have enough stock left."
                );
            }

            $unitPrice = $this->calculateUnitPrice(
                $item,
                $variant
            );

            $subtotal += $unitPrice * $cartItem->quantity;

            $orderItemsData[] = [
                'item_id' => $item->id,
                'item_variant_id' => $variant?->id,
                'variant_label' => $variant?->label(),
                'item_name' => $item->translate('en')?->name ?? 'Item',
                'unit_price' => $unitPrice,
                'quantity' => $cartItem->quantity,
            ];

            $this->decreaseStock(
                $item,
                $variant,
                $cartItem->quantity
            );
        }

        return [$subtotal, $orderItemsData];
    }

    protected function validateVariant(
        $variantId,
        ?ItemVariant $variant,
        Item $item
    ): void {
        if (
            $variantId
            && (! $variant || $variant->item_id !== $item->id)
        ) {
            throw new \RuntimeException(
                'Something in your cart is no longer valid. Please review your cart.'
            );
        }
    }

    protected function calculateUnitPrice(
        Item $item,
        ?ItemVariant $variant
    ): float {
        return $item->effectivePrice()
            + (float) ($variant?->price_modifier ?? 0);
    }

    protected function decreaseStock(
        Item $item,
        ?ItemVariant $variant,
        int $quantity
    ): void {
        if ($variant) {
            $variant->decrement('stock', $quantity);

            return;
        }

        $item->decrement('stock', $quantity);
    }

    protected function applyDiscount(
        ?string $code,
        float $subtotal,
        int $userId
    ): array {
        if (! $code) {
            return [null, 0.0];
        }

        $discount = Discount::query()
            ->where('code', strtoupper(trim($code)))
            ->lockForUpdate()
            ->first();

        if (! $discount || ! $discount->isValid()) {
            throw new \RuntimeException(
                'This discount code is not valid.'
            );
        }

        if ($discount->once_per_customer) {
            $alreadyUsed = Order::query()
                ->where('user_id', $userId)
                ->where('discount_id', $discount->id)
                ->exists();

            if ($alreadyUsed) {
                throw new \RuntimeException(
                    'You have already used this discount code.'
                );
            }
        }

        $amount = min(
            $discount->amountFor($subtotal),
            $subtotal
        );

        $discount->increment('used_count');

        return [$discount, $amount];
    }

    protected function pointsDiscountAmount(
        int $userId,
        int $points,
        float $maxDiscountable
    ): float {
        if ($points <= 0 || $maxDiscountable <= 0) {
            return 0.0;
        }

        $balance = (int) LoyaltyPointTransaction::query()
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->sum('points');

        if ($points > $balance) {
            throw new \RuntimeException(
                "You don't have enough loyalty points."
            );
        }

        return round(
            min(
                $points / $this->pointsPerDollarRedeemed(),
                $maxDiscountable
            ),
            2
        );
    }

    protected function createOrder(
        int $userId,
        string $shippingAddress,
        float $subtotal,
        ?Discount $discount,
        float $discountAmount,
        int $actualPointsUsed,
        float $pointsDiscountAmount
    ): Order {
        $total = max(
            $subtotal
            - $discountAmount
            - $pointsDiscountAmount,
            0
        );

        return Order::create([
            'user_id' => $userId,
            'discount_id' => $discount?->id,
            'status' => OrderStatus::Pending,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'points_redeemed' => $actualPointsUsed,
            'points_discount_amount' => $pointsDiscountAmount,
            'total' => $total,
            'shipping_address' => $shippingAddress,
        ]);
    }

    protected function redeemPoints(
        int $userId,
        int $orderId,
        int $points
    ): void {
        if ($points <= 0) {
            return;
        }

        LoyaltyPointTransaction::create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'points' => -$points,
            'reason' => 'redeemed_at_checkout',
        ]);
    }

    protected function awardPoints(Order $order): void
    {
        $earned = (int) floor(
            (float) $order->total
            * $this->pointsPerDollarEarned()
        );

        if ($earned <= 0) {
            return;
        }

        LoyaltyPointTransaction::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'points' => $earned,
            'reason' => 'order_placed',
        ]);
    }
}
