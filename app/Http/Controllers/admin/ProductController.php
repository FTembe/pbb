<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Information;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\ProductRequest;
use stdClass;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->messages = new \App\Classes\Messages;
    }

    public function index()
    {
        $products = Product::all();



        //    dd($products->categories);
        return view("admin.product.index", compact('products'))->with([
            "menu" => "product",
            "link" => "product"
        ]);
    }

    public function create()
    {
        $categories  = \App\Models\Category::where('status', true)->get();
        $brands  = \App\Models\Brand::where('status', true)->get();
        $lines  = \App\Models\Line::where('status', true)->get();
        $menus  = \App\Models\Menu::where('status', true)->get();
        $suppliers  = \App\Models\Supplier::all();
        $unities  = \App\Models\Unity::where('status', true)->get();
        $topics  = \App\Models\Topic::where('status', true)->get();

        return view("admin.product.create", compact('categories', 'topics', 'lines', 'unities', 'brands', 'menus', 'suppliers'))->with([
            "menu" => "product",
            "link" => "product"
        ]);
    }


    public function  productCategories($item = null, array $categories = null)
    {
        $new_array = [];
        if (is_array($categories)) {
            foreach ($categories as $category) {
                $new_array[] = [
                    'category_id' => $category,
                    'product_id' => $item,
                ];
            }
        }
        return $new_array;
    }
    public function  productInformation($item, array $data): array
    {
        $new_array = [];
        foreach ($data['body'] as $key => $info) {

            if ($info) {
                $new_array[] = [
                    'topic_id' => $data['topic_id'][$key],
                    'product_id' => $item,
                    'body' => $info,
                ];
            }
        }
        return $new_array;
    }

    public function store(ProductRequest $request)
    {
        $product = new Product();
        $count = $product::count();

        $request['order'] = ++$count;

        $request['aliase'] = Str::slug($request['name']);
        $request['aliase'] = $product::where('aliase',  $request['aliase'])->first() ? $request['aliase'] . '-' . rand(0000, 1111) : $request['aliase'];

        $imges = [];

        if ($request->file('image_files')) {
            $files = $request->allFiles()['image_files'];
            foreach ($files as $key => $file) {

                $rand_file_name = md5(time().$key);
                $ext = $file->extension();
                $file_full_name = $rand_file_name . '.' . $ext;
                $upload_path = 'shop/images/products/';
                $file_url =  $upload_path . $file_full_name;

                $file->move($upload_path, $file_full_name);

                $imges[] = $file_url;
            }
            unset($request['image_files']);
            
            $request['images'] = json_encode($imges);
        }


        $create = $product::create($request->all());

        if ($create) {

            if (is_array($request->input('category_id'))) {
                $productCategor = $this->productCategories($create->id, $request->input('category_id'));
                if (!ProductCategory::insert($productCategor)) {
                    return redirect()->back()->with('warning', $this->messages->get('e_create_only_product_category'));
                }
            }

            if ($request['supplier_id'] || $request['quantity']) {
                $supply = [
                    "reference" => isset($request['reference']) ? $request['reference'] : time(),
                    "supplier_id" => $request['supplier_id'],
                    "purchase_price" => $request['purchase_price'],
                    "quantity" => $request['quantity'],
                    "retail_price" => $request['retail_price'],
                    "barcode" => $request['barcode'],
                    "currency_id" => 1,
                    "product_id" => $create->id,
                    "user_id" =>  session()->get('session_user')->id
                ];
                if (!Supply::insert($supply)) {
                    return redirect()->back()->with('warning', $this->messages->get('e_create_only_supply'));
                }
            }

            if (!empty($request['body'])) {
                $info = $this->productInformation($create->id, [
                    'topic_id' => $request['topic_id'],
                    'body' => $request['body']
                ]);
                if (!Information::insert($info)) {
                    return redirect()->back()->with('warning', $this->messages->get('e_create_only_info'));
                }
            }

            return redirect()->back()->with('success', $this->messages->get('s_create'));
        }
        return redirect()->back()->with('warning', $this->messages->get('e_create'));
    }

    public function edit($id)
    {
        $categories  = \App\Models\Category::where('status', true)->get();
        $brands  = \App\Models\Brand::where('status', true)->get();
        $lines  = \App\Models\Line::where('status', true)->get();
        $menus  = \App\Models\Menu::where('status', true)->get();
        $suppliers  = \App\Models\Supplier::all();
        $unities  = \App\Models\Unity::where('status', true)->get();
        $topics  = \App\Models\Topic::where('status', true)->get();

        $product = Product::findOrFail($id);

        $product_info = [];

        $k = 1;
        foreach ($product->ohterInformation as $info) {
            $product_info[$k] = $info->topic_id;
            $k++;
        }

        $item_categories = [];

        $i = 1;
        foreach ($product->categories as $v) {
            $item_categories[$i] = $v->category_id;
            $i++;
        }

        return view("admin.product.edit", compact('product_info', 'product', 'item_categories', 'categories', 'topics', 'lines', 'unities', 'brands', 'menus', 'suppliers'))->with([
            "menu" => "product",
            "link" => "product_create"
        ]);
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if (Str::slug($product->name) != Str::slug($request['name'])) {

            $request['aliase'] = Str::slug($request['name']);
            $request['aliase'] = $product::where('aliase',  $request['aliase'])->first() ? $request['aliase'] . '-' . rand(0000, 1111) : $request['aliase'];
        }

        if (is_array($request->all('category_id'))) {
            $collection_deleted = ProductCategory::destroy(ProductCategory::where('product_id', $id)->get());
            // if ($collection_deleted) {
            $productCategor = $this->productCategories($id, $request->input('category_id'));
            if (!ProductCategory::insert($productCategor)) {
                return redirect()->back()->with('warning', $this->messages->get('e_create_only_product_category'));
            }
            // }
        }



        if ($request->file('image_files')) {
            $old_file = json_encode($product->images);
            $files = $request->allFiles()['image_files'];

            foreach ($files as $key => $file) {

                $rand_file_name = md5(time().$key);
                $ext = $file->extension();
                $file_full_name = $rand_file_name . '.' . $ext;
                $upload_path = 'shop/images/products/';
                $file_url =  $upload_path . $file_full_name;

                $imges[] = $file_url;

                if ($file->move($upload_path, $file_full_name)) {

                    \Illuminate\Support\Facades\File::delete($old_file);
                }
            }
            $product->images = json_encode($imges);
        }

        /* INFORMATION */

        if (!empty($request['body'])) {

            $new =  [
                'topic_id' => $request['topic_id'],
                'body' => $request['body']
            ];
            if (isset($product->ohterInformation)) {
                foreach ($product->ohterInformation as $key => $info) {


                    $info = Information::where('product_id', $id)->where('topic_id', $info->topic_id)->first();
                    if ($info) {
                        $i = (array_search($info->topic_id, $new['topic_id']));
                        $info->body =  $new['body'][$i];
                        $info->save();

                        unset($new['body'][$i]);
                        unset($new['topic_id'][$i]);
                    }
                }
            }
            $info = $this->productInformation($id, [
                'topic_id' => $new['topic_id'],
                'body' => $new['body']
            ]);

            if (!Information::insert($info)) {
                return redirect()->back()->with('warning', $this->messages->get('e_create_only_info'));
            }
        }

        $product->name = $request['name'];
        $product->slogan = $request['slogan'];
        $product->type = $request['type'];
        $product->unit_id = $request['unit_id'];
        $product->line_id = $request['line_id'];
        $product->tags = $request['tags'];
        $product->menu_id = $request['menu_id'];
        $product->brand_id = $request['brand_id'];
        $product->description = $request['description'];

        /* SUPPLY */

        if ($request['supply_id'] || $request['reference']) {

            $supply = Supply::findOrFail($request['supply_id']);
            $supply->purchase_price = $request['purchase_price'];
            $supply->retail_price = $request['retail_price'];
            $supply->quantity = $request['quantity'];
            $supply->barcode = $request['barcode'];
            $supply->save();
        }

        if ($product->save()) {
            return redirect()->back()->with('success', $this->messages->get('s_update'));
        }
        return redirect()->back()->with('success', $this->messages->get('e_update'));
    }

    public function destroy($id)
    {
        //
    }

    public function removeImage($id, $position)
    {
        // return 12;
    }

    public function setVisibility(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $status = false;
        $product->status = $request->input('status') ? true : false;

        if ($request->input('visibilty') === 'recent') {

            $product->recent = !$product->recent;
            $status = $product->save();
        } elseif ($request->input('visibilty') === 'featured') {
            $product->featured = !$product->featured;
            $status = $product->save();
        } else {
            $product->recent = 0;
            $product->featured = 0;
            $status = $product->save();
        }

        if ($status) {
            return redirect()->back()->with('success', $this->messages->get('s_update'));
        }
        return redirect()->back()->with('warning', $this->messages->get('e_update'));
    }
}
