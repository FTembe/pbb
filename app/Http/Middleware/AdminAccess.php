<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccess
{

    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('session_user')) {
            return redirect()->route('auth')->with('warning', 'Acesso negado');
        } else {

            $user = session()->get('session_user');

            if ($user->backend_access == 1  && $user->status === 'active') {
                return $next($request);
            } elseif (!$user->backend_access) {
                return redirect()->route('auth')->with('warning', 'Usuario sem acesso administrativo');
            } elseif ($user->status !== 'active') {
                return redirect()->route('auth')->with('warning', 'Conta inactiva');
            } else {
                return redirect()->route('auth')->with('warning', 'Acesso negado');
            }
        }
    }
}
