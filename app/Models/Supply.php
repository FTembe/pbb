<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected  $table = 'supplies';
    protected $fillable = [
        'reference',
        'retail_price',
        'purchase_price',
        'availability',
        'quantity',
        'min_quantity',
        'tax',
        'current',
        'validity',
        'package_quantity',
        'status',
        'currency_id',
        'user_id',
        'product_id',
        'entity_id',
        'supplier_id',
        'barcode',
    ];

    public function supplier(){
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
