<?php

namespace App\Services;

use App\Models\Cart;

class CartService
{
    public function currentCart(?int $userId, string $sessionId): Cart
    {
        if ($userId) {
            return Cart::firstOrCreate(['user_id' => $userId]);
        }

        return Cart::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }

    public function addItem(Cart $cart, int $itemId, ?int $itemAttributeId, int $quantity): void
    {
        $existing = $cart->items()
            ->where('item_id', $itemId)
            ->where('item_attribute_id', $itemAttributeId)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $quantity);

            return;
        }

        $cart->items()->create([
            'item_id' => $itemId,
            'item_attribute_id' => $itemAttributeId,
            'quantity' => $quantity,
        ]);
    }

    public function updateQuantity(Cart $cart, int $cartItemId, int $quantity): void
    {
        $cart->items()->whereKey($cartItemId)->update(['quantity' => max(1, $quantity)]);
    }

    public function removeItem(Cart $cart, int $cartItemId): void
    {
        $cart->items()->whereKey($cartItemId)->delete();
    }

    public function mergeGuestCartIntoUser(string $guestSessionId, int $userId): void
    {
        $guestCart = Cart::query()
            ->where('session_id', $guestSessionId)
            ->whereNull('user_id')
            ->first();

        if (! $guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $userId]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()
                ->where('item_id', $guestItem->item_id)
                ->where('item_attribute_id', $guestItem->item_attribute_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $guestItem->quantity);
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }
}
