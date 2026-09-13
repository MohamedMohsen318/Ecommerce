<?php

namespace App\Models;

use App\Models\Relations\OrderItemRelations;
use App\Models\Traits\OrderItemPricing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, OrderItemPricing, OrderItemRelations;

    protected $fillable = ['order_id', 'item_id', 'item_attribute_id', 'item_name', 'unit_price', 'quantity'];

    protected function casts(): array
    {
        return ['unit_price' => 'decimal:2'];
    }
}
