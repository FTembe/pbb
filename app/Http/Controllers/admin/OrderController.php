<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Messages;
use App\Models\Entity;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->messages = new Messages;
    }
    public function index()
    {

        $orders = Order::all();
        return view('admin.order.index', compact('orders'))->with([
            "menu" => "order",
            "link" => "order"
        ]);
    }


    public function create()
    {
        $products = \App\Models\Supply::all();


        return view('admin.order.create', compact('products'))->with([
            "menu" => "order",
            "link" => "order"
        ]);
    }

    public function store(Request $request)
    {


        $customer_id = null;
        if ($request->input('first_name') && $request->input('email')) {

            $user = new \App\Models\User;

            $find = $user::where('email', $request->input('email'))->first();
            $user_id = null;

            if ($find) {
                $user_id = $find->id;
            } else {
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->email = $request->input('email');
                $user->save();
                $user_id = $user->id;
            }
            if ($user_id) {

                $customer = new \App\Models\Customer;
                $customer->user_id = $user_id;
                $customer->name = $request->input('first_name') . ' ' . $request->input('last_name');
                $customer->email = $request->input('email');
                $customer->phone = $request->input('phone');
                $customer->address = $request->input('address');

                $customer->save();

                $customer_id = $customer->id;
            }
        }
        $order = \App\Models\Order::create([
            'reference' => time(),
            'discount' => $request->input('discount'),
            'tax' => $request->input('tax'),
            'iva' => $request->input('iva'),
            'amount' => $request->input('gross_amount_value'),
            'customer_id' => $customer_id,
            'status' => true,
            'net_amount' => $request->input('net_amount'),
        ]);

        if ($order) {

            $order_items = [];

            foreach ($request['product'] as $key => $product) {
                $product_suplly = \App\Models\Supply::where('id', $product)->first();
                $order_items[] = [
                    'supply_id' => $product,
                    'price' => $request['price'][$key],
                    'product_id' => $product_suplly->product_id,
                    'order_id' => $order->id,
                    'quantity' => $request['qty'][$key],
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s'),
                ];
            }

            if (OrderItem::insert($order_items)) {
                return redirect()->back()->with('success', $this->messages->get('s_create'));
            }
            return redirect()->back()->with('warning', $this->messages->get('e_create'));
        }
    }

    public function getProductData($id = null)
    {
        if ($id) {
            $product = \App\Models\Supply::find($id);

            return response()->json($product, 200);
        }
    }
    public function getTableProductRow()
    {

        $products = \App\Models\Supply::all();

        $new_products = [];

        foreach ($products as $pr) {


            $new_products[] = [

                'id' => $pr->id,
                'name' => $pr->product->name,
                'retail_price' => $pr->retail_price,
            ];
        }


        return response()->json($new_products, 200);
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


    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.edit', compact('order'));
    }


    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->status = $request->input('paid');
        if ($order->save()) {
            return redirect()->back()->with('success', $this->messages->get('s_update'));
        }
        return redirect()->back()->with('warning', $this->messages->get('errors'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order->delete()) {
            return redirect()->back()->with('success', $this->messages->get('s_remove'));
        }
        return redirect()->back()->with('warning', $this->messages->get('errors'));
    }

    public function print($id)
    {


        $order = Order::findOrFail($id);
        $entity = Entity::all()->first();


        $html = view('admin.order.pdf', compact('order', 'entity'))->render();

        $date = date('d-m-Y');
        $entity_name = isset($entity->name) ? $entity->name : "";

        $mpdf = new \Mpdf\Mpdf();
        
        $mpdf->WriteHTML(($html));
        $mpdf->SetFooter("<div style='text-align:center'>{$entity_name} - {$date}</div>");
        $filename = time();

        $mpdf->Output($filename, 'I');
    }
}
