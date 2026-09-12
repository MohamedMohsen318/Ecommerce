<?php

namespace App\Models\Relations;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMediaRelation
{
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }
}
