<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FlashSaleLogic
{
    public function isRunning(): bool
    {
        return $this->is_active && now()->between($this->starts_at, $this->ends_at);
    }

    public function scopeRunning(Builder $query): void
    {
        $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }
}
