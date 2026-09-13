<?php

namespace App\Models;

use App\Models\Relations\CartItemRelations;
use App\Models\Traits\CartItemPricing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use CartItemPricing, CartItemRelations, HasFactory;

    protected $fillable = ['cart_id', 'item_id', 'item_attribute_id', 'quantity'];
}
