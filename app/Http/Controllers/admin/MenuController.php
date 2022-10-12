<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Classes\Messages;
use Illuminate\Http\Request;
use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use Illuminate\Support\Facades\File; 

class MenuController extends Controller
{

    public function __construct()
    {
        $this->messages = new Messages;
    }
    public function index()
    {
        $menu_links = Menu::all();
        return view('admin.menu.index', compact('menu_links'))->with([
            'menu' => 'menu',
            'link' => 'menu_list'
        ]);
    }


    public function create()
    {
        return view('admin.menu.create')->with([
            'menu' => 'menu',
            'link' => 'menu_create'
        ]);
    }

    public function store(MenuRequest $request)
    {
        $menu = Menu::where('name', $request['name'])->first();

        if ($menu) {
            return redirect()->back()->with('warning', $this->messages->get('exist'));
        }

        if ($request->file('file_banner')) {
            $file = $request->file('file_banner');
            // $file = $request->file('image_files');
            $rand_file_name = md5(time());
            $ext = $file->extension();
            $file_full_name = $rand_file_name . '.' . $ext;
            $upload_path = 'shop/images/menu/banners/';
            $file_url =  $upload_path . $file_full_name;
            $file->move($upload_path, $file_full_name);
            $request['banner'] = $file_url;
        }
        $count = Menu::count();


        $request['aliase'] = Str::slug($request['name']);
        $request['order'] = ++$count;

        $menu = Menu::create($request->all());

        if ($menu) {
            return redirect()->back()->with('success', $this->messages->get('s_create'));
        }
        return redirect()->back()->with('warning', $this->messages->get('e_create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu_link = Menu::where('id', $id)->first();

        if (!$menu_link) {
            return redirect()->back()->with('error', $this->messages->get('not_found'));
        }

        return view('admin.menu.edit', compact('menu_link'))->with([
            'menu' => 'menu',
            'link' => 'menu'
        ]);
    }


    public function update(MenuRequest $request, $id)
    {
        $menu_link = Menu::where('id', $id)->first();

        if (!$menu_link) {
            return redirect()->back()->with('error', $this->messages->get('not_found'));
        }

        if( Str::slug($menu_link->name)  !=  Str::slug($request["name"])){
            $request['aliase'] = Str::slug($request['name'],'-', 'pt');
            $menu_link->aliase = Menu::where('aliase',  $request['aliase'])->first() ? $request['aliase'] . '-' . rand(0000, 1111) : $request['aliase'];
        }

        if ($request->file('banner')) {
            $old_file = $menu_link->banner;
            $file = $request->file('banner');
            // $file = $request->file('image_files');
            $rand_file_name = md5(time());
            $ext = $file->extension();
            $file_full_name = $rand_file_name . '.' . $ext;
            $upload_path = 'shop/images/menu/banners/';
            $file_url =  $upload_path . $file_full_name;

            if($file->move($upload_path, $file_full_name))
            {
                $menu_link->banner = $file_url;
                File::delete( $old_file);
            }
            
        }
      
        $menu_link->status = $request['status'];
        $menu_link->featured = $request['featured'];
        $menu_link->description = $request['description'];
        $menu_link->information = $request['information'];

        if ($menu_link->save()) {
            return redirect()->back()->with('success', $this->messages->get('s_update'));
        }
        return redirect()->back()->with('success', $this->messages->get('e_update'));
    }


    public function destroy($id)
    {
        $menu = Menu::where('id', $id)->first();

        if (!$menu) {
            return redirect()->back()->with('error', $this->messages->get('not_found'));
        }

        $menu->delete();

        return redirect()->back()->with('success', $this->messages->get('s_remove'));
    }
}
