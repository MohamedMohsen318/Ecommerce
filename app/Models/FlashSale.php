<?php

namespace App\Models;

use App\Models\Relations\FlashSaleRelations;
use App\Models\Traits\FlashSaleLogic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use FlashSaleLogic, FlashSaleRelations, HasFactory;

    protected $fillable = ['name', 'starts_at', 'ends_at', 'is_active'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
