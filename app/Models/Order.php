<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'reference',
        'discount',
        'tax',
        'iva',
        'amount',
        'customer_id',
        'user_id',
        'net_amount',
    ];

    public function customer()
    {
        // return $this->hasOne(Customer::class, 'id', 'customer_id');

        return $this->belongsTo('App\Models\Customer');
    }

    public function items()
    {
        // return $this->hasOne(Customer::class, 'id', 'customer_id');

        return $this->hasMany('App\Models\OrderItem');
    }

}
