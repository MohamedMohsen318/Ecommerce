<?php

namespace App\Models;

use App\Models\Relations\CategoryRelations;
use App\Models\Traits\CategoryTree;
use App\Models\Traits\HasMedia;
use App\Models\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use CategoryRelations, CategoryTree, HasFactory, HasMedia, HasTranslations;

    protected $fillable = ['parent_id', 'is_active', 'order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
