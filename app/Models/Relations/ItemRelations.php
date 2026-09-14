<?php

namespace App\Models\Relations;

use App\Models\Category;
use App\Models\ItemAttribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductReview;
use App\Models\ProductComment;

trait ItemRelations
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

   public function variants(): HasMany
    {
        return $this->hasMany(ItemAttribute::class);
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(ProductComment::class)->whereNull('parent_id');
    }
}
