<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Retur;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class OrderAdminController extends Controller
{
   /**
     * Menampilkan daftar pesanan
     */
    public function orders(Request $request)
    {
        session()->put('menu', 'orders');

        $orders = Order::with('customer')
            ->when($request->search, function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('order_code', 'like', '%'.$request->search.'%')
                      ->orWhereHas('customer', function($q) use ($request) {
                          $q->where('name', 'like', '%'.$request->search.'%');
                      });
                });
            })
            ->when($request->status && $request->status != 'all', function($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orderAdmin', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan
     */
    public function showOrder($id)
    {
        $order = Order::with(['customer', 'items.product'])->findOrFail($id);
        return view('admin.orderDetail', compact('order'));
    }

    /**
     * Konfirmasi pesanan
     */
    public function confirmOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status != 'waiting_confirmation') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dikonfirmasi karena status bukan menunggu konfirmasi.'
            ], 400);
        }

        $order->update([
            'status' => 'processing',
            'confirmed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dikonfirmasi.',
            'order' => $order
        ]);
    }
    /**
     * Input resi pengiriman
     */
    public function shipOrder(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string',
            'shipping_company' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        
        if ($order->status != 'confirmed') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dikirim karena belum dikonfirmasi.');
        }

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $request->tracking_number,
            'shipping_company' => $request->shipping_company,
            'shipped_at' => now()
        ]);

        // Kirim notifikasi ke customer
        // ...

        return redirect()->route('admin.orders')->with('success', 'Resi pengiriman berhasil disimpan.');
    }

    /**
     * Menyelesaikan pesanan
     */
    public function completeOrder($id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status != 'shipped') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat diselesaikan karena belum dikirim.');
        }

        $order->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->route('admin.orders')->with('success', 'Pesanan berhasil diselesaikan.');
    }
}


