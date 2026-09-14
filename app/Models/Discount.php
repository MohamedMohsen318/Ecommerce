<?php

namespace App\Models;

use App\Enums\DiscountType;
use App\Models\Traits\DiscountLogic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use DiscountLogic, HasFactory;

    protected $fillable = ['code', 'type', 'value', 'max_uses', 'used_count', 'expires_at', 'is_active'];

    protected function casts(): array
    {
        return [
            'type' => DiscountType::class,
            'value' => 'decimal:2',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
