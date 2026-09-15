<?php

namespace App\Models\Traits;

trait UserLoyaltyPoints
{
    public function loyaltyPointsBalance(): int
    {
        return (int) $this->loyaltyPointTransactions()->sum('points');
    }
}
