<?php

namespace App\Models\Traits;

trait OrderItemPricing
{
    public function lineTotal(): float
    {
        return (float) $this->unit_price * $this->quantity;
    }
}
