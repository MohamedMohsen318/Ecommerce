<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemAttributeType extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'name', 'order'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ItemAttributeValue::class)->orderBy('order');
    }
}
