<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineRequest extends FormRequest
{


    public function rules()
    {
        return [
            'name' => 'required | min:2 | max:100',
            'description' => 'nullable | min:5 | max:80',
            'status' => 'nullable',
            'featured' => 'nullable',
            'parent_id' => 'nullable',
            'informarion' => 'nullable',
            'menu_id' => 'nullable',
            'aliase' => 'nullable| min:2 | max:100',
            'slogan' => 'nullable | min:2 | max:100',
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
