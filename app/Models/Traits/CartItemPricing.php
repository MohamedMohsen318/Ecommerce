<?php

namespace App\Models\Traits;

trait CartItemPricing
{
    public function unitPrice(): float
    {
        return (float) $this->item->price + (float) ($this->variant?->price_modifier ?? 0);
    }

    public function lineTotal(): float
    {
        return $this->unitPrice() * $this->quantity;
    }

    public function availableStock(): int
    {
        return $this->variant?->stock ?? $this->item->stock;
    }
}
