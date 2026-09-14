<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Relations\OrderStatusHistoryRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory, OrderStatusHistoryRelations;

    protected $fillable = ['order_id', 'status', 'note', 'changed_by_admin_id'];

    protected function casts(): array
    {
        return ['status' => OrderStatus::class];
    }
}
