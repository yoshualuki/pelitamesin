<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        if (!session()->get('user')) {
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

        // Get the requested status filter
        $status = request('status');

        $orders = Order::with(['user', 'items.products'])
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('order_id', 'like', '%' . request('search') . '%')
                        ->orWhereHas('items.products', function ($productQuery) {
                            $productQuery->where('name', 'like', '%' . request('search') . '%');
                        });
                });
            })
            ->latest()
            ->paginate(5) // Reduce from 10 to 5 items per page
            ->appends(request()->query());

        return view('customer.orders', compact('orders', 'statuses'));
    }

    public function show($order_id)
    {
        if (!session()->get('user')) {
            return redirect()->route('login');
        }
        $order = Order::with(['items.products', 'user'])
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

    public function submitRating(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:500',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:5120'
        ]);

        $order = Order::findOrFail($request->order_id);

        // Update order status
        $order->update(['status' => 'completed']);

        // Save rating
        $rating = $order->rating()->create([
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        // Handle media upload
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('public/ratings');
                $rating->media()->create(['file_path' => Storage::url($path)]);
            }
        }

        return response()->json(['message' => 'Terima kasih atas penilaiannya!']);
    }
}
