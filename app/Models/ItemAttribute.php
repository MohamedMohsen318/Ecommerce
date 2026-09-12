<?php

namespace App\Models;

use App\Models\Relations\ItemAttributeRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAttribute extends Model
{
    use HasFactory, ItemAttributeRelations;

    protected $fillable = ['item_id', 'name', 'value', 'price_modifier', 'stock'];

    protected function casts(): array
    {
        return ['price_modifier' => 'decimal:2'];
    }
}
