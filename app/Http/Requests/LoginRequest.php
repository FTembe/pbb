<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
     return [
            'username'=>'required| email| min:3',
            'password'=>'required| min:6',
        ];

    }

    function messages()
    {
        return [
            'username.required' =>'Campo obrigatorio',
            'username.email' =>'O email deve ser valido',
            'username.min' =>'O campo deve ter no mínimo :min caracters ',
            'password.min' =>'O campo deve ter no mínimo :min caracters ',
            'password.required' =>'Campo obrigatorio',
        ];
    }
}
