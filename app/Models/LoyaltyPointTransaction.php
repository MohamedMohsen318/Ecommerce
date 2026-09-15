<?php

namespace App\Models;

use App\Models\Relations\LoyaltyPointTransactionRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPointTransaction extends Model
{
    use HasFactory, LoyaltyPointTransactionRelations;

    protected $fillable = ['user_id', 'order_id', 'points', 'reason'];
}
