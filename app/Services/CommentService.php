<?php

namespace App\Services;

use App\Models\ProductComment;

class CommentService
{
    public function create(int $userId, int $itemId, string $body, ?int $parentId = null): ProductComment
    {
        if ($parentId) {
            $parent = ProductComment::query()->where('item_id', $itemId)->find($parentId);

            if (! $parent || $parent->parent_id !== null) {
                throw new \RuntimeException('You can only reply to a top-level comment.');
            }
        }

        return ProductComment::create([
            'item_id' => $itemId,
            'user_id' => $userId,
            'parent_id' => $parentId,
            'body' => $body,
        ]);
    }

    public function update(ProductComment $comment, string $body): bool
    {
        return $comment->update(['body' => $body]);
    }

    public function delete(ProductComment $comment): ?bool
    {
        return $comment->delete();
    }
}
