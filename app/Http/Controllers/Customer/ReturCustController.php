<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Retur;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;

class ReturCustController extends Controller
{
    public function index()
    {   
        $retur = session()->get('retur');
        $order = Order::all();
        $orderDetail = OrderDetail::all();
        $product = Product::all();
        $user = User::all();
        return view('customer.returCust', compact('retur', 'order', 'orderDetail', 'product', 'user'));
    }

    public function create()
    {
        $order = Order::all();
        $orderDetail = OrderDetail::all();
        $product = Product::all();
        $user = User::all();
        $retur = Retur::all();
        return view('customer.returCust', compact('order', 'orderDetail', 'product', 'user', 'retur'));  

    }


    public function edit($id)
    {
        $retur = Retur::find($id);
        return view('customer.returCust', compact('retur'));
    }



    public function search(Request $request)
    {
        $retur = Retur::where('order_id', 'like', '%' . $request->search . '%')->get();
        $order = Order::all();
        $orderDetail = OrderDetail::all();
        $product = Product::all();
        $user = User::all();
        return view('customer.returCust', compact('retur', 'order', 'orderDetail', 'product', 'user'));
        return redirect()->route('customer.retur.index');
    }

    public function sort(Request $request)
    {
        $retur = Retur::orderBy('order_id', 'asc')->get();
        return view('customer.returCust', compact('retur'));
    }

    public function filter(Request $request)
    {
        $retur = Retur::where('order_id', 'like', '%' . $request->filter . '%')->get();
        return view('customer.returCust', compact('retur'));
    }

    public function paginate(Request $request)
    {
        $retur = Retur::paginate(10);
        return view('customer.returCust', compact('retur'));
    }
    
    public function store(Request $request)
    {
        $retur = new Retur();
        $retur->order_id = $request->order_id;
        $retur->product_id = $request->product_id;
        $retur->quantity = $request->quantity;
        $retur->reason = $request->reason;
        $retur->save();
        $order = Order::find($request->order_id);
        $order->status = 'Retur';
        $order->save();
        $orderDetail = OrderDetail::find($request->product_id);
        $orderDetail->quantity = $request->quantity;
        $orderDetail->save();
        $product = Product::find($request->product_id);
        $product->quantity = $request->quantity;
        $product->save();
        return redirect()->route('customer.retur.index');
    }
}   



