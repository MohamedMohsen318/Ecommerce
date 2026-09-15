<?php

namespace App\Models;

use App\Models\Relations\AddressRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use AddressRelations, HasFactory;

    protected $fillable = ['user_id', 'label', 'line', 'is_default'];

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }
}
