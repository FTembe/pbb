<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userCreateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'access_level_id' => 'required',
            'username' => 'nullable',
            'email' => 'required | min:2 | email',
            'phone' => 'nullable | min:8',
            'gender' => 'nullable',
            'password' => 'nullable|min:6',
            'first_name' => 'required | min:2',
            'last_name' => 'required | min:2',
        ];
    }
    function messages()
    {
        return [
            'first_name.required' => 'Campo obrigatorio',
            'last_name.required' => 'Campo obrigatorio',
            'email.email' => 'O email deve ser valido',
            'email.min' => 'O campo deve ter no mínimo :min caracters ',
            'email.required' => 'Campo obrigatorio',
            'first_name.min' => 'O campo deve ter no mínimo :min caracters ',
            'last_name.min' => 'O campo deve ter no mínimo :min caracters ',
            'password.min' => 'O campo deve ter no mínimo :min caracters ',
            'phone.min' => 'O campo deve ter no mínimo :min caracters ',
        ];
    }
}
