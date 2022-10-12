<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessLevel;
use App\Models\User;
use App\Http\Requests\userCreateRequest;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();

        return view('admin.users.index')->with([
            'menu' => 'users',
            'link' => 'users',
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $access_levels = AccessLevel::where('status', 1)->get();

        return view('admin.users.create')->with([
            'menu' => 'users',
            'link' => 'create',
            'access_levels' => $access_levels,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(userCreateRequest $request)
    {
        if ($request['re_password'] != $request['password']) {
            return redirect()->back()->withInput()->with('error', "As senhas não coincidem");
        }

        if (User::where('email', $request['email'])->first()) {
            return redirect()->back()->withInput()->with('warning', "O e-mail inserido faz parte da nossa lista de emails");
        }

        $request['aliase']  = Str::slug($request['first_name'] . '-' . $request['last_name'] . '-' . rand(0000, 9999));
        $request['password'] =  md5($request['password']  . 'glpcc' . strtolower(trim($request['email'])));

        if ($request['username'] == '') {
            $request['username'] = $request['email'];
        }
        if (User::create($request->all())) {
            return redirect()->route('users.create')->with('success', "Conta criada com sucesso");
        }
        return redirect()->back()->with('error', "Ocorreu um erro ao tentar criar a conta");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $access_levels = AccessLevel::where('status', 1)->get();

        $user = User::where('id', $id)->first();
        if (!$user) {
            redirect()->back()->with('warning', 'O sistema não consegui processar o seu pedido');
        }

        return view('admin.users.edit')->with([
            'menu' => 'users',
            'link' => 'create',
            'access_levels' => $access_levels,
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(userCreateRequest $request, $id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return redirect()->back()->with('warning', 'O sistema não consegui processar o seu pedido');
        }

        $user->access_level_id = trim($request["access_level_id"]);
        $user->username = $request["email"];
        $user->email = $request["email"];
        $user->first_name = $request["first_name"];
        $user->last_name = $request["last_name"];
        $user->phone = $request["phone"];
        $user->gender = $request["gender"];
        $user->status = $request["status"];
        $user->backend_access = $request["backend_access"];

        if ($user->save()) {
        
            return redirect()->route('users.edit', ['id'=>  $user->id])->with('success', 'Usuario actualizado com successo');
        } else {
            return redirect()->back()->with('warning', 'Ocorreu um erro ao tentar actualizar o usuario');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
