<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
   

    
    public function rules()
    {
        return [
        
                'name' => 'required | min:2 | max:50',
                'description' => 'nullable | min:10 | max:500',
                'status' => 'nullable',
        ];
    }

    function messages()
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
