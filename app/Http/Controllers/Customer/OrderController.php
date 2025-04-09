<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderDetail;

class OrderController extends Controller
{
    public function index()
    {
        if(!session()->get('user')) {
            return redirect()->route('login');
        }

        $statuses = [
            'waiting_payment' => 'Menunggu Pembayaran',
            'waiting_confirmation' => 'Menunggu Konfirmasi',
            'processing' => 'Sedang di Proses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Refunded'
        ];

        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders', 'statuses'));
    }

    public function show($order_id)
    {
        if(!session()->get('user')) {
            return redirect()->route('login');
        }
        $order = Order::with(['items.product', 'user'])
                    ->where('order_id', $order_id)
                    ->firstOrFail();

        $statuses = [
            'waiting_payment' => 'Menunggu Pembayaran',
            'waiting_confirmation' => 'Menunggu Konfirmasi',
            'processing' => 'Sedang di Proses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Refunded'
        ];
            

        return view('customer.orderShow', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting_payment,waiting_confirmation,processing,shipped,completed,cancelled,refunded'
        ]);

        $order->update(['status' => $validated['status']]);

        // Add status history
        $order->histories()->create([
            'status' => $validated['status'],
            'notes' => $request->notes ?? 'Status changed by admin'
        ]);

        return back()->with('success', 'Order status updated successfully');
    }

    public function cancel(Request $request, $order_id)
    {
        $order = Order::where('order_id', $order_id)->firstOrFail();
    
        // Validasi status order
        if (!in_array($order->status, ['waiting_payment', 'processing'])) {
            return back()->with('error', 'Tidak dapat membatalkan pesanan dengan status ini');
        }
        
        // Update status order
        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason,
            'cancellation_notes' => $request->notes,
            'cancelled_at' => now()
        ]);
        
        // Kembalikan stok produk jika perlu
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            $product->stock += $item->quantity;
            // perlu kembalikan stock ke inventory dengan hb yang sama

            $product->save();
        }
        
        
        
        return redirect()->route('orders.show', $order_id)
            ->with('success', 'Pesanan telah dibatalkan');
    }

    public function confirmDelivery($order_id, Request $request)
    {
        $order = Order::where('order_id', $order_id)->firstOrFail();
        
        // Validasi status order
        if ($order->status !== 'shipped') {
            return back()->with('error', 'Tidak dapat mengkonfirmasi pesanan yang belum dikirim');
        }
        
        // Update status order
        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
            'delivery_notes' => $request->notes
        ]);
        
        // Kirim notifikasi ke admin
        // ...
        
        return redirect()->route('orders.show', $order_id)
            ->with('success', 'Pesanan telah dikonfirmasi sebagai diterima');
    }
}