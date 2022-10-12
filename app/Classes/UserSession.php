<?php

namespace App\Classes;

class UserSession
{

    public function get()
    {
        return session()->get('session_user');
    }
}
