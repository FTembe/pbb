<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entity;

class EntityController extends Controller
{
    public function create()
    {

        $entity = Entity::first();
        return view('admin.entity.create', compact('entity'))->with('menu', 'entity');
    }
    public function store(Request $request)
    {


        if (Entity::all()->count()) {
            $entity = Entity::first();

            $entity->name = $request->input('name');
            $entity->address = $request->input('address');
            $entity->phone = $request->input('phone');
            $entity->email = $request->input('email');
            $entity->user_id =  session()->get('session_user')->id;
            $entity->iva = $request->input('iva');
            $entity->tax = $request->input('tax');
            $entity->country = $request->input('country');
            $entity->message = $request->input('message');

            $entity->save();
        } else {
            $request['user_id'] =  session()->get('session_user')->id;

            Entity::create($request->except('_token'));
        }
      return  redirect()->back();
    }
}
