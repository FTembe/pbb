<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Classes\Messages;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandController extends Controller
{

    public function __construct()
    {
        $this->messages = new Messages();
    }

    public function index()
    {
        $brands = Brand::all();
        return view('admin.brand.index')->with([
            'menu' => 'brand',
            'link' => 'brand',
            'brands'=>$brands
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brand.create')->with([
            'menu' => 'brand',
            'link' => 'brand_create'
        ]);
    }


    public function store(BrandRequest $request)
    {
        
        if(Brand::where('name', $request['name'])->first()) {
            return redirect()->back()->with('warning', $this->messages->get('exist'));
        }

        $request['aliase'] = Str::slug($request['name'], 'pt');
        $brand = Brand::create($request->all());

        if (!$brand) {
            return redirect()->back()->with('error', $this->messages->get('e_create'));
        }
        return redirect()->back()->with('success', $this->messages->get('s_create'));
    }

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
        $brand = Brand::where('id', $id)->first();
        if(!$brand){
            return redirect()->back()->with('error' , $this->messages->get('not_found'));
        }
        return view('admin.brand.edit')->with([
            'menu' => 'brand',
            'link' => 'brand',
            'brand'=>$brand 
        ]);
    }

    public function update(BrandRequest $request, $id)
    {
        $brand = Brand::where('id', $id)->first();

        if(!$brand){
            return redirect()->back()->with('error' , $this->messages->get('not_found'));
        }
        $brand->name = $request['name'];
        $brand->status = $request['status'];
        $brand->description = $request['description'];

        if($brand->save()){
            return redirect()->back()->with('success' , $this->messages->get('s_update'));
        }
        return redirect()->back()->with('error' , $this->messages->get('e_update'));
    }

    public function destroy($id)
    {
        $brand = Brand::where('id', $id)->first();
        if(!$brand){
            return redirect()->back()->with('error' , $this->messages->get('not_found'));
        }
        $brand->delete();
        return redirect()->back()->with('error' , $this->messages->get('s_remove'));
    }
}
