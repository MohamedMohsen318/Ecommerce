<?php

namespace App\Models\Relations;

use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Discount;
use App\Models\OrderStatusHistory;

trait OrderRelations
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }
}
