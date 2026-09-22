<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ItemVariant extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'sku', 'price_modifier', 'stock', 'combination_hash'];

    protected function casts(): array
    {
        return ['price_modifier' => 'decimal:2'];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function values(): BelongsToMany
    {
        return $this->belongsToMany(ItemAttributeValue::class, 'item_variant_values')
            ->with('type');
    }

    public function label(): string
    {
        return $this->values
            ->sortBy(fn (ItemAttributeValue $v) => $v->type->order)
            ->map(fn (ItemAttributeValue $v) => "{$v->type->name}: {$v->value}")
            ->implode(', ');
    }

    public static function hashFor(array $attributeValueIds): string
    {
        $sorted = collect($attributeValueIds)->map(fn ($id) => (int) $id)->sort()->values()->all();

        return md5(implode('-', $sorted));
    }
}
