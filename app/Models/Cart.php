<?php

namespace App\Models;

use App\Models\Relations\CartRelations;
use App\Models\Traits\CartPricing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use CartPricing, CartRelations, HasFactory;

    protected $fillable = ['user_id', 'session_id'];
}
