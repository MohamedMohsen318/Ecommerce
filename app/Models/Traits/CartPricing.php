<?php

namespace App\Models\Traits;

use App\Models\CartItem;

trait CartPricing
{
    public function subtotal(): float
    {
        return (float) $this->items->sum(fn (CartItem $item) => $item->lineTotal());
    }
}
