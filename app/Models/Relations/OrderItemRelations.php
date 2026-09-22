<?php

namespace App\Models\Relations;

use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait OrderItemRelations
{
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ItemVariant::class, 'item_variant_id');
    }
}
