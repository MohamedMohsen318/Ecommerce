<?php

namespace App\Services;

use App\Models\ProductComment;

class CommentService
{
    public function create(int $userId, int $itemId, string $body, ?int $parentId = null): ProductComment
    {
        return ProductComment::create([
            'item_id' => $itemId,
            'user_id' => $userId,
            'parent_id' => $parentId,
            'body' => $body,
        ]);
    }
}
