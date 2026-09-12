<?php

namespace App\Models\Relations;

use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ItemAttributeRelations
{
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
