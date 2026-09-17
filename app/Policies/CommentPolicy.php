<?php

namespace App\Policies;

use App\Models\ProductComment;
use App\Models\User;

class CommentPolicy
{
    public function update(User $user, ProductComment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    public function delete(User $user, ProductComment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
