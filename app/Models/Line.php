<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{

    protected $table = 'lines';
    protected $fillable = [
        'name',
        'parent_id',
        'menu_id',
        'status',
        'slogan',
        'picture',
        'description',
        'information',
        'banner',
        'featured',
        'aliase',
    ];

    public function child_lines(){
        return $this->hasMany(Line::class,'parent_id');
    }
}
