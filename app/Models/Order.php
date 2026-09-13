<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Relations\OrderRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, OrderRelations;

    protected $fillable = ['user_id', 'status', 'subtotal', 'total', 'shipping_address'];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }
}
