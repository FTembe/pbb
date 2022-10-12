<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
  
    
    
    public function rules()
    {
        return [
            "name" => "required | min:2 ",
            "slogan" => "nullable | min:2",
            "type" => "nullable",
            "unit_id" => "nullable",
            "menu_id" => "nullable",
            "image_files" => "nullable",
        
            "brand_id" => "nullable",
            "line_id" => "nullable",
            "barcode" => "nullable",
            
            "description" => "nullable | min:5",


            "category_id" => "nullable",
            "tags" => "nullable",

            "supplier_id" => "nullable",
            "purchase_price" => "nullable",
            "quantity" => "nullable",
            "retail_price" => "nullable",
        ];
    }
}
