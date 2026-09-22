<?php

namespace App\Models\Relations;

use App\Models\Category;
use App\Models\ItemAttributeType;
use App\Models\ItemVariant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductReview;
use App\Models\ProductComment;
use App\Models\FlashSale;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait ItemRelations
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attributeTypes(): HasMany
    {
        return $this->hasMany(ItemAttributeType::class)->orderBy('order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ItemVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ProductComment::class)->whereNull('parent_id');
    }

    public function flashSales(): BelongsToMany
    {
        return $this->belongsToMany(FlashSale::class, 'flash_sale_items')->withPivot('sale_price')->withTimestamps();
    }
}
