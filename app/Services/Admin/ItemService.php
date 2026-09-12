<?php

namespace App\Services\Admin;

use App\Enums\MediaType;
use App\Models\Item;
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
            $this->syncVariants($item, $data['variants'] ?? []);

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
            $this->syncVariants($item, $data['variants'] ?? []);

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

    protected function syncVariants(Item $item, array $variants): void
    {
        $keptIds = [];

        foreach ($variants as $variant) {
            if (empty($variant['name']) || empty($variant['value'])) {
                continue;
            }

            $attributes = [
                'name' => $variant['name'],
                'value' => $variant['value'],
                'price_modifier' => $variant['price_modifier'] ?? 0,
                'stock' => $variant['stock'] ?? 0,
            ];

            $existing = ! empty($variant['id'])
                ? $item->variants()->whereKey($variant['id'])->first()
                : null;

            if ($existing) {
                $existing->update($attributes);
                $keptIds[] = $existing->id;
            } else {
                $keptIds[] = $item->variants()->create($attributes)->id;
            }
        }

        $item->variants()->whereNotIn('id', $keptIds)->delete();
    }
}
