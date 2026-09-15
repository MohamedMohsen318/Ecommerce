<?php

namespace App\Models\Relations;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\LoyaltyPointTransaction;

trait UserRelations
{
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlistedItems(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'wishlists')->withTimestamps();
    }
    public function loyaltyPointTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyPointTransaction::class);
    }
}
