<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'quantity','price','supply_id','product_id','order_id'
    ];
    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
    public function getTotalAttribute(){
        return $this->price*$this->quantity;
    }
}
