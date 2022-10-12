<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table ='product_categories';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'category_id',
    ];


    public function category(){
        return $this->hasOne(Category::class, 'product_id', 'id');
    }
    
}
