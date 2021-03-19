<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'users_cart';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "user_id",
        "product_id",
    ];
}
