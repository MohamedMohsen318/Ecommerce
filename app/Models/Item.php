<?php

namespace App\Models;

use App\Models\Relations\ItemRelations;
use App\Models\Traits\HasMedia;
use App\Models\Traits\HasTranslations;
use App\Models\Traits\ItemScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\ItemPricing;

class Item extends Model
{
    use HasFactory, HasMedia, HasTranslations, ItemPricing, ItemRelations, ItemScopes, SoftDeletes;

    protected $fillable = ['category_id', 'price', 'stock', 'sku', 'is_active'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
