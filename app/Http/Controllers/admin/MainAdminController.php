<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\UserSession;

class MainAdminController extends Controller
{
    private $UserSession ;

    public function __construct()
    {
        $this->UserSession =   new  UserSession();
      
    }
    
    public function index(){

      return view('admin.main.index')->with(['menu'=>'dashboard']);
    }
}
