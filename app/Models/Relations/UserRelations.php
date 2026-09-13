<?php

namespace App\Models\Relations;

use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelations
{
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
