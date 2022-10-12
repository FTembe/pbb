<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\LineRequest;
use App\Models\Line;
use App\Classes\Messages;
use Illuminate\Support\Facades\File; 

class LineController extends Controller
{
    public function __construct()
    {
        $this->messages = new Messages;
    }

    public function index()
    {
        $lines = Line::where('parent_id', Null)->get();
        return view('admin.line.index', compact('lines'))->with([
            'menu' => 'line',
            'link' => 'line',
        ]);
    }

    public function create()
    {
        $lines = Line::where('status', true)->get();
        $menus = \App\Models\Menu::where('status', true)->get();
        return view('admin.line.create', compact('lines', 'menus'))->with([
            'menu' => 'line',
            'link' => 'line_create',
        ]);
    }

    public function store(LineRequest $request)
    {

        if ($request->file('image_file')) {
            $file = $request->file('image_file');
            $rand_file_name = md5(time());
            $ext = $file->extension();
            $file_full_name = $rand_file_name . '.' . $ext;
            $upload_path = 'shop/images/line/thumbs/';
            $file_url =  $upload_path . $file_full_name;
            $file->move($upload_path, $file_full_name);
            $request['picture'] = $file_url;
        }

        if ($request->file('banner_file')) {
            $file = $request->file('banner_file');
            $rand_file_name = md5('banner'.time());
            $ext = $file->extension();
            $file_full_name = $rand_file_name . '.' . $ext;
            $upload_path = 'shop/images/line/banners/';
            $file_url =  $upload_path . $file_full_name;
            $file->move($upload_path, $file_full_name);
            $request['banner'] = $file_url;
        }

        if($request['menu_id']){
            $request['menu_id'] = implode(',', $request['menu_id']) .','; 
        }

        $request['aliase'] = Str::slug($request['name'], '-', 'pt');
        $request['aliase'] = Line::where('aliase',  $request['aliase'])->first() ? $request['aliase'] . '-' . rand(0000, 1111) : $request['aliase'];

        if (Line::create($request->all())) {
            return redirect()->back()->with('success', $this->messages->get('s_create'));
        }

        return redirect()->back()->with('error', $this->messages->get('e_create'));
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $line = Line::findOrFail($id);
       
        $show_in_menu = [] ;
        foreach( explode(',',  $line->menu_id) as $menu_id){
            if($menu_id){
                $show_in_menu[$menu_id] = $menu_id;
            }
        }

        $line->menu_id = $show_in_menu;
     
        $menus = \App\Models\Menu::where('status', true)->get();

        $lines = Line::where('parent_id', Null)->where('id', '<>', $id)->get();

        return view('admin.line.edit', compact('line', 'lines','menus'))->with([
            'menu' => 'line',
            'link' => 'line_create'
        ]);
    }

    public function update(LineRequest $request, $id)
    {



        $line = Line::findOrfail($id);


        if ($request->file('image_file')) {

            $old_file = $line->picture;

            $file = $request->file('image_file');
            $rand_file_name = md5(time());
            $ext = $file->extension();
            $file_full_name = $rand_file_name . '.' . $ext;
            $upload_path = 'shop/images/line/thumbs/';
            $file_url =  $upload_path . $file_full_name;
           
            if($file->move($upload_path, $file_full_name))
            {
                $line->picture = $file_url;
                File::delete( $old_file);
            }
        }

        if ($request->file('banner_file')) {

            $old_file = $line->banner;

            $file = $request->file('banner_file');
            $rand_file_name = md5('banner'.time());
            $ext = $file->extension();
            $file_full_name = $rand_file_name . '.' . $ext;
            $upload_path = 'shop/images/line/banners/';
            $file_url =  $upload_path . $file_full_name;
            if($file->move($upload_path, $file_full_name))
            {
                $line->banner = $file_url;
                File::delete( $old_file);
            }
        }
        
        if($request['menu_id']){
            $line->menu_id = implode(',', $request['menu_id']) .','; 
        }

        if ($line->name  !=  $request["name"]) {
            $request['aliase'] = Str::slug($request['name'], '-', 'pt');
            $request['aliase'] = Line::where('aliase',  $request['aliase'])->first() ? $request['aliase'] . '-' . rand(0000, 1111) : $request['aliase'];
            $line->aliase = $request["aliase"];
            $line->name  =  $request["name"];
        }

        $line->slogan  =  $request["slogan"];
        $line->description  = $request["description"];
        $line->information  = $request["information"];
        $line->status  = $request["status"];
        $line->featured = $request["featured"];

        $line->parent_id = $request["parent_id"];

        if ($line->save()) {
            
            return redirect()->back()->with('success', $this->messages->get('s_update'));
        }
        return redirect()->back()->with('error', $this->messages->get('e_update'));
    }

    public function destroy($id)
    {
        $line = Line::findOrfail($id);

        if ($line->delete()) {
            return redirect()->back()->with('success', $this->messages->get('s_remove'));
        }
        return redirect()->back()->with('error', $this->messages->get('error'));
    }
}
