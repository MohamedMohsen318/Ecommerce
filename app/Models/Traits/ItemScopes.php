<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ItemScopes
{
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeInStock(Builder $query): void
    {
        $query->where('stock', '>', 0);
    }
}
