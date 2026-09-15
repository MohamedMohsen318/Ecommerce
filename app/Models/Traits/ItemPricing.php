<?php

namespace App\Models\Traits;

use App\Models\FlashSale;

trait ItemPricing
{
    public function effectivePrice(): float
    {
        $activeSale = $this->relationLoaded('flashSales')
            ? $this->flashSales->first(fn (FlashSale $sale) => $sale->isRunning())
            : $this->flashSales()->running()->first();

        $salePrice = $activeSale?->pivot->sale_price;

        return $salePrice !== null ? (float) $salePrice : (float) $this->price;
    }
}
