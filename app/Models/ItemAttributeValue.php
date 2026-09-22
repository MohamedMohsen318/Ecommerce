<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = ['item_attribute_type_id', 'value', 'order'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ItemAttributeType::class, 'item_attribute_type_id');
    }
}
