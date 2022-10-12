<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function register()
    {
        return view('admin.register');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'first_name' => 'required | min:2',
            'last_name' => 'required | min:2',
            'email' => 'email | required | min:3',
            'password' => 'required | min:8',
            're_password' => 'required | min:8',
        ]);

        if ($validate['re_password'] != $validate['password']) {
            return redirect()->back()->withInput()->with('error', "As senhas não coincidem");
        }

        if (User::where('email', $validate['email'])->first()) {
            return redirect()->back()->withInput()->with('warning', "O e-mail inserido faz parte da nossa lista de emails");
        }

        $validate['aliase']  = Str::slug($validate['first_name'] . '-' . $validate['last_name'] . '-' . rand(0000, 9999));
        $validate['password'] =  md5($validate['password']  . 'glpcc' . strtolower(trim($validate['email'])));

        if (User::create($validate)) {
            return redirect()->route('login')->with('success', "Conta criada com sucesso");
        }
        return redirect()->back()->with('error', "Ocorreu um erro ao tentar criar a conta");
    }
}
