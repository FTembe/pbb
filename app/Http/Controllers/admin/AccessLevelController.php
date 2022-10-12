<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessLevel;
use App\Models\User;
use App\Classes\Module;
use App\Classes\Messages;

class AccessLevelController extends Controller
{
    public function __construct()
    {
        $this->messages = new Messages();
    }

    public function index()
    {

        $access_levels = AccessLevel::all();

        return view('admin.access.index')->with([
            'menu' => 'access_level',
            'link' => 'access',
            'access_levels' => $access_levels,
        ]);
    }

    public function create()
    {
        return view('admin.access.create')->with([
            'menu' => 'access_level',
            'link' => 'access_create',
        ]);
    }

    public function store(Request $request)
    {


        $validate =  $request->validate([
            'name' => 'required | min:2',
            'permission' => 'nullable'
        ], [
            'name.required' => 'O campo é obrigatorio',
            'name.min' => 'O campo deve ter no mínimo :min caracters ',
        ]);
        $_POST['permission'] = isset($_POST['permission']) ?  json_encode($_POST['permission']) : json_encode([]);
        $validate['permission'] = $_POST['permission'];
        $validate['module'] = 'shop';
        $access = AccessLevel::create($validate);


        if ($access) {
            return redirect()->back()->with('success', $this->messages->get('cr_access_level'));
        }
        return redirect()->back()->with('error', $this->messages->get('ecr_access_level'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $access = AccessLevel::where('id', $id)->first();
        $access->permission = json_decode($access->permission);
        
        return view('admin.access.edit')->with([
            'menu' => 'access_level',
            'link' => 'access',
            'access_level' => $access,
        ]);
    }

    public function update(Request $request, $id)
    {

        $access_level = AccessLevel::where('id', $id)->first();

        

        if (!$access_level) {
            return redirect()->back()->with('warning',  $this->messages->get('not_found'));
        }

        $validate =  $request->validate([
            'name' => 'required | min:2',
            'permission' => 'nullable'
        ], [
            'name.required' => 'O campo é obrigatorio',
            'name.min' => 'O campo deve ter no mínimo :min caracters ',
        ]);

        $_POST['permission'] = isset($_POST['permission']) ?  json_encode($_POST['permission']) : json_encode([]);
        $validate['permission'] = $_POST['permission'];
        $validate['module'] = 'shop';

        $access_level->name =  $validate['name'];
        $access_level->permission =  $validate['permission'];

        if($access_level->save()){
            return redirect()->back()->with('success',  $this->messages->get('s_update'));
        }
        return redirect()->back()->with('warning',  $this->messages->get('eup_access_level'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $access = AccessLevel::where('id', $id)->first();

        if (!$access) {
            return redirect()->back()->with('warning',  $this->messages->get('not_found'));
        }
        if (User::where('access_level_id', $id)->first()) {

            return redirect()->back()->with('warning', $this->messages->get('ras_access_level'));
        }
        $access->delete();
        return redirect()->back()->with('error',   $this->messages->get('rm_access_level'));
    }
}
