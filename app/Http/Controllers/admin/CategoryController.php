<?php

namespace App\Http\Controllers\admin;

use App\Classes\Messages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Support\Facades\File; 


class CategoryController extends Controller
{

    public function __construct()
    {
        $this->messages = new Messages;
    }
    public function index()
    {
        $categories = Category::where('parent_id', Null)->get();

        return view('admin.category.index', compact('categories'))->with([
            'menu' => 'category',
            'link' => 'category'
        ]);
    }


    public function create()
    {
        $categories = Category::where('parent_id', Null)->get();
        $menus = \App\Models\Menu::where('status', true)->get();

        return view('admin.category.create',compact('categories','menus'))->with([
            'menu' => 'category',
            'link' => 'category_create'
        ]);
    }

    public function store(CategoryRequest $request)
    {

  
        if ($request->file('file_banner')) {
            $file = $request->file('file_banner');
            $rand_file_name = md5(time());
            $ext = $file->extension();
            $file_full_name = $rand_file_name . '.' . $ext;
            $upload_path = 'shop/images/category/banners/';
            $file_url =  $upload_path . $file_full_name;
            $file->move($upload_path, $file_full_name);
            $request['banner'] = $file_url;
        }

        $request['aliase'] = Str::slug($request['name'],'-', 'pt');
        
        $request['aliase'] = Category::where('aliase',  $request['aliase'])->first() ? $request['aliase'] . '-' . rand(0000, 1111) : $request['aliase'];
        
        if (Category::create($request->all())) {
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
        $category = Category::findOrfail($id);
        $parents = Category::where('parent_id', Null)->where('id', "<>", $id)->get();
        
        $menus = \App\Models\Menu::where('status', true)->get();

        return view('admin.category.edit', compact('category','parents','menus'))->with([
            'menu' => 'category',
            'link' => 'category'
        ]);
    }

    public function update(CategoryRequest $request, $id)
    {
        
        $category = Category::findOrfail($id);

        if ($request->file('file_banner')) {

            $old_file =   $category->banner; 
            $file = $request->file('file_banner');
            $rand_file_name = md5(time());
            $ext = $file->extension();
            $file_full_name = $rand_file_name . '.' . $ext;
            $upload_path = 'shop/images/category/banners/';
            $file_url =  $upload_path . $file_full_name;
            
            if($file->move($upload_path, $file_full_name))
            {
                $category->banner = $file_url;
                File::delete( $old_file);
            }
        }


        if( Str::slug($category->name)  !=  Str::slug($request["name"])){
            $request['aliase'] = Str::slug($request['name'],'-', 'pt');
            $category->aliase = Category::where('aliase',  $request['aliase'])->first() ? $request['aliase'] . '-' . rand(0000, 1111) : $request['aliase'];
        }

        $category->name  =  $request["name"];
        $category->description  = $request["description"];
        $category->information  = $request["information"];
        $category->status  = $request["status"];
        $category->featured = $request["featured"]; 
        $category->parent_id = $request["parent_id"];

        if ($category->save()) {
            return redirect()->back()->with('success', $this->messages->get('s_update'));
        }
        return redirect()->back()->with('error', $this->messages->get('e_update'));
    }

    public function destroy($id)
    {
        $category = Category::findOrfail($id);

        if($category->delete()){
            return redirect()->back()->with('success', $this->messages->get('s_remove'));
        }
        return redirect()->back()->with('error', $this->messages->get('error'));
    }
}
