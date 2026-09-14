<?php

namespace App\Models\Relations;

use App\Models\Admin;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait OrderStatusHistoryRelations
{
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'changed_by_admin_id');
    }
}
