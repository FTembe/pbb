<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
use App\Models\Product;
use App\Models\Supply;

class SiteController extends Controller
{

    public function __construct()
    {
        $this->menus = Menu::where('status', true)->get();

        View::share('menus',  $this->menus);
    }

    public function index()
    {

        $products = Product::where('status', 1)->where('recent', 1)->orWhere('featured', 1)->get();

        $recents = [];
        $features = [];

        foreach($products as $product){
            if($product->recent){
                $recents [] =  $product;
            }else{
                $features [] =  $product;
            }
        }

        return view('site.index', compact('features', 'recents'));
    }

    public function find($menu, $category = null, $subcategory = null)
    {
        $menu_id = Menu::where('aliase', $menu)->first();

        // dd( $menu_id );

        // return view('site.index');
    }
}
