<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
   
    public function rules()
    {
        return [
           'name'=>'required | min:2 | max:50',
           'description'=>'nullable | min:5 | max:255',
           'status'=>'nullable',
           'featured'=>'nullable',
           'parent_id'=>'nullable',
           'menu_id'=>'nullable',
           'informarion'=>'nullable',
           'aliase'=>'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Campo obrigatorio',
            'name.min' => 'O campo deve ter no mínimo :min caracters ',
            'name.max' => 'O campo deve ter no maximo :max caracters ',
            'description.min' => 'O campo deve ter no mínimo :min caracters ',
            'description.max' => 'O campo deve ter no maximo :max caracters ',
        ];
    }
}
