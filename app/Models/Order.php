<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Relations\OrderRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, OrderRelations;

    protected $fillable = [
        'user_id', 'discount_id', 'status', 'subtotal', 'discount_amount',
        'points_redeemed', 'points_discount_amount', 'total', 'shipping_address',
    ];
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }
}
