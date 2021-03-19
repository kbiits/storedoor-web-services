<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteProduct extends Model
{
    use HasFactory;

    protected $table = 'favorite_products';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "user_id",
        "product_id",
    ];
}
