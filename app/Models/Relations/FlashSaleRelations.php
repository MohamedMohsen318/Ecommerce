<?php

namespace App\Models\Relations;

use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait FlashSaleRelations
{
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'flash_sale_items')->withPivot('sale_price')->withTimestamps();
    }
}
