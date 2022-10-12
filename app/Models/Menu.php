<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table ='menus';

    protected $fillable = [
        'name',
        'status',
        'aliase',
        'featured',
        'banner',
        'description',
        'information',
        'order',
    ];

    public function categories (){
        return $this->hasMany(Category::class, 'menu_id', 'id'); 
    }
    
}
