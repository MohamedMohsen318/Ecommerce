<?php

namespace App\Models\Traits;

use App\Models\ModelTranslation;
use App\Models\Relations\HasTranslationsRelation;

trait HasTranslations
{
    use HasTranslationsRelation;

    public function translate(?string $locale = null): ?ModelTranslation
    {
        $locale ??= app()->getLocale();

        if ($this->relationLoaded('translations')) {
            return $this->translations->firstWhere('locale', $locale);
        }

        return $this->translations()->where('locale', $locale)->first();
    }

    public function setTranslation(string $locale, array $content): void
    {
        $this->translations()->updateOrCreate(
            ['locale' => $locale],
            [
                'name' => $content['name'] ?? null,
                'description' => $content['description'] ?? null,
            ]
        );
    }
}
