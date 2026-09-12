<?php

namespace App\Services\Admin;

use App\Enums\MediaType;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Category::with(['parent.translations', 'translations'])->latest()->paginate($perPage);
    }

    public function create(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            $category = Category::create([
                'parent_id' => $data['parent_id'] ?? null,
                'is_active' => $data['is_active'] ?? false,
                'order' => $data['order'] ?? 0,
            ]);

            $this->syncTranslations($category, $data['translations']);

            if (! empty($data['image'])) {
                $category->setMedia($data['image'], MediaType::Image, 'categories');
            }

            return $category;
        });
    }

    public function update(Category $category, array $data): Category
    {
        $newParentId = isset($data['parent_id']) ? (int) $data['parent_id'] : null;

        if ($newParentId && $this->wouldCreateCycle($category, $newParentId)) {
            throw new \RuntimeException('A category cannot be moved under its own descendant.');
        }

        return DB::transaction(function () use ($category, $data, $newParentId) {
            $category->update([
                'parent_id' => $newParentId,
                'is_active' => $data['is_active'] ?? false,
                'order' => $data['order'] ?? 0,
            ]);

            $this->syncTranslations($category, $data['translations']);

            if (! empty($data['image'])) {
                $category->setMedia($data['image'], MediaType::Image, 'categories');
            }

            return $category;
        });
    }

    public function delete(Category $category): void
    {
        if ($category->children()->exists()) {
            throw new \RuntimeException('Move or delete the sub-categories first.');
        }

        $category->delete();
    }

    protected function syncTranslations(Category $category, array $translations): void
    {
        foreach ($translations as $locale => $content) {
            if (! empty($content['name'])) {
                $category->setTranslation($locale, $content);
            }
        }
    }

    /**
     * نطلع لفوق في نسب الفئة الأب المقترحة. لو وصلنا لنفس $category،
     * معناه هنعمل حلقة مقفولة (Category تبقى أب لنفسها بشكل غير مباشر).
     */
    protected function wouldCreateCycle(Category $category, ?int $newParentId): bool
    {
        while ($newParentId !== null) {
            if ($newParentId === $category->id) {
                return true;
            }

            $newParentId = Category::query()->whereKey($newParentId)->value('parent_id');
        }

        return false;
    }
}
