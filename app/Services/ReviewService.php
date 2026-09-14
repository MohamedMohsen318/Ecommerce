<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\OrderItem;
use App\Models\ProductReview;

class ReviewService
{
    public function canReview(int $userId, int $itemId): bool
    {
        $alreadyReviewed = ProductReview::query()
            ->where('item_id', $itemId)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyReviewed) {
            return false;
        }

        return $this->purchaseOrderId($userId, $itemId) !== null;
    }

    public function create(int $userId, int $itemId, int $rating, ?string $body): ProductReview
    {
        $orderId = $this->purchaseOrderId($userId, $itemId);
        $alreadyReviewed = ProductReview::query()->where('item_id', $itemId)->where('user_id', $userId)->exists();

        if (! $orderId || $alreadyReviewed) {
            throw new \RuntimeException("You can only review an item you've purchased, once per item.");
        }

        return ProductReview::create([
            'item_id' => $itemId,
            'user_id' => $userId,
            'order_id' => $orderId,
            'rating' => $rating,
            'body' => $body,
            'is_approved' => false,
        ]);
    }

    protected function purchaseOrderId(int $userId, int $itemId): ?int
    {
        return OrderItem::query()
            ->where('item_id', $itemId)
            ->whereHas('order', fn ($q) => $q->where('user_id', $userId)->where('status', '!=', OrderStatus::Cancelled))
            ->value('order_id');
    }
}
