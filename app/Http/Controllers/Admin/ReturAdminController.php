<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class ReturAdminController extends Controller
{
    public function index()
    {
        
        $retur = session()->get('retur');
        $item= session()->get('retur');
        $order = Order::all();
        $orderDetail = OrderDetail::all();
        $product = Product::all();
        return view('admin.returAdmin', compact('retur', 'order', 'orderDetail', 'product', 'item'));
        }

    public function create()
    {
        $order = Order::all();
        $orderDetail = OrderDetail::all();
        $product = Product::all();
        $user = User::all();
        $retur = Retur::all();
        return view('admin.returAdmin', compact('order', 'orderDetail', 'product', 'user', 'retur'));
    }

    public function edit($id)
    {
        $retur = Retur::find($id);
        return view('admin.returAdmin', compact('retur'));
    }

    public function store(Request $request)
    {
        $retur = new Retur();
        $retur->order_id = $request->order_id;
        $retur->product_id = $request->product_id;
        $retur->quantity = $request->quantity;
        $retur->reason = $request->reason;
        $retur->save();
        return redirect()->route('admin.retur.index');
    }   

    public function search(Request $request)
    {
        $retur = Retur::where('order_id', 'like', '%' . $request->search . '%')->get();
        $order = Order::all();
        $orderDetail = OrderDetail::all();
        $product = Product::all();
        $user = User::all();
        return view('admin.returAdmin', compact('retur', 'order', 'orderDetail', 'product', 'user'));
        return redirect()->route('admin.retur.index');
        

    }   
    

    // public function update(Request $request, $id)
    // {
    //     $retur = Retur::find($id);
    //     $retur->order_id = $request->order_id;
    //     $retur->product_id = $request->product_id;
    //     $retur->quantity = $request->quantity;
    //     $retur->reason = $request->reason;
    //     $retur->save();
    //     return redirect()->route('admin.retur.index');  
    // }

    // public function destroy($id)
    // {
    //     $retur = Retur::find($id);
    //     $retur->delete();   
    //     $order = Order::find($id);
    //     $order->delete();
    //     $orderDetail = OrderDetail::find($id);
    //     $orderDetail->delete();
    //     $product = Product::find($id);
    //     $product->delete();
    //     $user = User::find($id);    
    //     return redirect()->route('admin.retur.index');
    // }

    public function show($id)
    {
        $retur = Retur::find($id);
        return view('admin.returAdmin', compact('retur'));  
    }


}


