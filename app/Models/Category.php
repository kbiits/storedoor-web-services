<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        "img",
        "slug",
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    public function setSlugAttribute($value)
    {
        $this->attributes["slug"] = ucwords($value);
    }
}
