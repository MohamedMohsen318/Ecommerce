<?php

namespace App\Models\Relations;

use App\Models\ModelTranslation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslationsRelation
{
    public function translations(): MorphMany
    {
        return $this->morphMany(ModelTranslation::class, 'model');
    }
}
