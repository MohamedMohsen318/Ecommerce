<?php

namespace App\Services\Admin;

use App\Enums\MediaType;
use App\Models\Item;
use App\Models\ItemVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ItemService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Item::with(['category.translations', 'translations'])->latest()->paginate($perPage);
    }

    public function create(array $data): Item
    {
        return DB::transaction(function () use ($data) {
            $item = Item::create([
                'category_id' => $data['category_id'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'sku' => $data['sku'] ?? null,
                'is_active' => $data['is_active'] ?? false,
            ]);

            $this->syncTranslations($item, $data['translations']);
            $this->syncAttributes($item, $data['attribute_types'] ?? []);

            if (! empty($data['image'])) {
                $item->setMedia($data['image'], MediaType::Image, 'items');
            }

            return $item;
        });
    }

    public function update(Item $item, array $data): Item
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update([
                'category_id' => $data['category_id'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'sku' => $data['sku'] ?? null,
                'is_active' => $data['is_active'] ?? false,
            ]);

            $this->syncTranslations($item, $data['translations']);
            $this->syncAttributes($item, $data['attribute_types'] ?? []);

            if (! empty($data['image'])) {
                $item->setMedia($data['image'], MediaType::Image, 'items');
            }

            return $item;
        });
    }

    public function delete(Item $item): void
    {
        $item->delete();
    }

    protected function syncTranslations(Item $item, array $translations): void
    {
        foreach ($translations as $locale => $content) {
            if (! empty($content['name'])) {
                $item->setTranslation($locale, $content);
            }
        }
    }

    protected function syncAttributes(Item $item, array $attributeTypes): void
    {
        $keptTypeIds = [];

        foreach ($attributeTypes as $order => $typeData) {
            if (empty($typeData['name']) || empty(array_filter($typeData['values'] ?? []))) {
                continue;
            }

            $type = $item->attributeTypes()->updateOrCreate(
                ['name' => trim($typeData['name'])],
                ['order' => $order]
            );
            $keptTypeIds[] = $type->id;

            $keptValueIds = [];
            foreach (array_values($typeData['values']) as $valueOrder => $rawValue) {
                $value = trim((string) $rawValue);
                if ($value === '') {
                    continue;
                }

                $attrValue = $type->values()->updateOrCreate(
                    ['value' => $value],
                    ['order' => $valueOrder]
                );
                $keptValueIds[] = $attrValue->id;
            }

            $type->values()->whereNotIn('id', $keptValueIds)->delete();
        }

        $item->attributeTypes()->whereNotIn('id', $keptTypeIds)->delete();
    }

    /**
     * @return array<int, array{value_ids: int[], labels: string[], existing: ?ItemVariant}>
     */
    public function generateCombinations(Item $item): array
    {
        $types = $item->attributeTypes()->with('values')->get();

        if ($types->isEmpty()) {
            return [];
        }

        $existingByHash = $item->variants()->with('values.type')->get()->keyBy('combination_hash');

        $combinations = [[]];

        foreach ($types as $type) {
            $next = [];
            foreach ($combinations as $combo) {
                foreach ($type->values as $value) {
                    $next[] = [...$combo, $value];
                }
            }
            $combinations = $next;
        }

        return collect($combinations)->map(function (array $values) use ($existingByHash) {
            $ids = collect($values)->pluck('id')->all();
            $hash = ItemVariant::hashFor($ids);

            return [
                'value_ids' => $ids,
                'labels' => collect($values)->map(fn ($v) => $v->value)->all(),
                'existing' => $existingByHash->get($hash),
            ];
        })->all();
    }

    public function syncVariants(Item $item, array $variantsInput): void
    {
        $keptVariantIds = [];

        foreach ($variantsInput as $row) {
            if (empty($row['selected']) || empty($row['value_ids']) || ! is_numeric($row['stock'] ?? null)) {
                continue;
            }

            $hash = ItemVariant::hashFor($row['value_ids']);

            $variant = $item->variants()->updateOrCreate(
                ['combination_hash' => $hash],
                [
                    'sku' => $row['sku'] ?? null,
                    'price_modifier' => $row['price_modifier'] ?? 0,
                    'stock' => $row['stock'],
                ]
            );

            $variant->values()->sync($row['value_ids']);
            $keptVariantIds[] = $variant->id;
        }

        $item->variants()->whereNotIn('id', $keptVariantIds)->delete();
    }
}
