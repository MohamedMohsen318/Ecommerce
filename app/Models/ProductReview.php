<?php

namespace App\Models;

use App\Models\Relations\ProductReviewRelations;
use App\Models\Traits\ProductReviewScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory, ProductReviewRelations, ProductReviewScopes;

    protected $fillable = ['item_id', 'user_id', 'order_id', 'rating', 'body', 'is_approved'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
        ];
    }
}
