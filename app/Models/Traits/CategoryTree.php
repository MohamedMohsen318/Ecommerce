<?php

namespace App\Models\Traits;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait CategoryTree
{
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public static function tree(): Collection
    {
        $categories = Category::query()->active()->with('translations')->orderBy('order')->get();

        return $categories->whereNull('parent_id')
            ->map(fn (Category $category) => self::attachChildren($category, $categories))
            ->values();
    }

    protected static function attachChildren(Category $category, Collection $all): Category
    {
        $category->setRelation(
            'children',
            $all->where('parent_id', $category->id)
                ->map(fn (Category $child) => self::attachChildren($child, $all))
                ->values()
        );

        return $category;
    }
}
