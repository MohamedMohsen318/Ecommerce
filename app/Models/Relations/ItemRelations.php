<?php

namespace App\Models\Relations;

use App\Models\Category;
use App\Models\ItemAttribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
