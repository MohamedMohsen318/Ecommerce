<?php

namespace App\Models;

use App\Models\Relations\ProductCommentRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    use HasFactory, ProductCommentRelations;

    protected $fillable = ['item_id', 'user_id', 'parent_id', 'body'];
}
