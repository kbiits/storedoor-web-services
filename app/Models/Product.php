<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'category_name',
    ];

    protected $fillable = [
        "title",
        "description",
        "img",
        "price",
        "category_id",
        "user_id",
        "rating"
    ];

    public function getCategoryNameAttribute()
    {
        $category =  \App\Models\Category::find($this->category_id);
        if ($category) {
            return $category->slug;
        }
        return "Tidak ada kategori";
    }


    // public function getPriceAttribute($value)
    // {
    //     return is_numeric($value) ? (string) number_format($value, 0, ',', '.') : (string) $value;
    // }

    public function getRatingAttribute($value)
    {
        return (float) $value;
    }

    public function getUserIdAttribute($val)
    {
        return (int) $val;
    }

    public function getCategoryIdAttribute($val)
    {
        return (int) $val;
    }
}
