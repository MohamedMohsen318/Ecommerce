<?php

namespace App\Models\Traits;

trait DiscountLogic
{
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at?->isPast()) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function amountFor(float $subtotal): float
    {
        return match ($this->type) {
            \App\Enums\DiscountType::Fixed => min((float) $this->value, $subtotal),
            \App\Enums\DiscountType::Percentage => round($subtotal * ((float) $this->value / 100), 2),
        };
    }
}
