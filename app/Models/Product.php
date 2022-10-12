<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table ='products';

    protected $fillable = [
        'name',
        'slogan',
        'type',
        'aliase',
        'unit_id',
        'menu_id',
        'brand_id',
        'line_id',
        'description',
        'tags',
        'retail_price',
        'order',
        'images'
    ];


    public function supplies(){
        return $this->hasMany(Supply::class, 'product_id', 'id');
    }

    public function categories(){
        return $this->hasMany(ProductCategory::class, 'product_id', 'id');
    }


    public function getThumbsAttribute(){

        return $this->images ? json_decode($this->images):[];
    }

    public function ohterInformation(){
        return $this->hasMany(Information::class, 'product_id', 'id');
    }
}
