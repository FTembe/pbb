<?php

namespace App\Http\Controllers\admin;

use App\Classes\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class loginController extends Controller
{
    public function __construct()
    {
        // dd($this->checkUserSession());
    }

    function index()
    {
        if (!session()->has('session_user')) {
            return view('admin.login');
        }else{
            $user = session()->get('session_user');
            if ($user->backend_access == 1  && $user->status === 'active') {
                return redirect()->route('dashboard');
            } elseif (!$user->backend_access) {
                return redirect()->route('auth')->with('warning', 'Usuario sem acesso administrativo');
            } elseif ($user->status !== 'active') {
                return redirect()->route('auth')->with('warning', 'Conta inactiva');
            } else {
                return redirect()->route('auth')->with('warning', 'Acesso negado');
            }
        }
    }
    function logout()
    {
        session()->forget('session_user');
        return redirect()->route('auth')->with('success', 'Sessão inserada com sucesso');
    }
    function recovery()
    {
        return view('admin.recovery');
    }
    function login(LoginRequest $request)
    {

        $user  = new User;

        $login = $user::where('email', $request['username'])->first();

        if (!$login) {
            return redirect()->back()->withInput()->with('error', 'Os dados fornecidos são invalidos ');
        }

        if ($login->status !== 'active') {
            return redirect()->back()->withInput()->with('warning', 'A sua conta não esta activa');
        }

        if (!$login->backend_acess && $login->access_level_id !== 1 && $login->access_level_id !== 2) {
            return redirect()->back()->withInput()->with('warning', 'Não tem permissão para aceder a area administrativa');
        }

        if ($login->password == $user->encrypt_password($request)) {
            $session_user = $login;

            session()->put('session_user', $session_user);
            $login->last_login = date('Y-m-d H:m:s');
            $login->save();

            return redirect()->route('dashboard');
           
        } else {
            return redirect()->back()->withInput()->with('warning', 'Verifica os dados fornecidos e tente novamente');
        }
    }

    public function checkUserSession()
    {
        return session()->has('session_user');
    }
}
