<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ProductReviewScopes
{
    public function scopeApproved(Builder $query): void
    {
        $query->where('is_approved', true);
    }
}
