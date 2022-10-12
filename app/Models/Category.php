<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id',
        'status',
        'menu_id',
        'description',
        'information',
        'banner',
        'featured',
        'aliase',
    ];


    function subcategories()
    {
        return $this->hasMany(Category::class,'parent_id');
    }
}
