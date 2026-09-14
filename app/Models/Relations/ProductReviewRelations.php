<?php

namespace App\Models\Relations;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ProductReviewRelations
{
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
