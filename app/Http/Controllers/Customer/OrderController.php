<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderRefund;
use App\Models\OrderRefundDetail;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Illuminate\Support\Str;
use Midtrans\Snap;

class OrderController extends Controller
{
    protected $statuses;

    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $this->statuses = [
            'waiting_payment' => 'Menunggu Pembayaran',
            'waiting_confirmation' => 'Menunggu Konfirmasi',
            'processing' => 'Sedang di Proses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Refund',
            'waiting_return' => 'Menunggu Pengiriman Barang Retur',
            'waiting_refund' => 'Menunggu Konfirmasi Refund'
        ];
    }

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
            'refunded' => 'Refunded',
            'waiting_return' => 'Menunggu Pengiriman Barang Retur',
            'waiting_refund' => 'Menunggu Konfirmasi Refund'
        ];

        // Get the requested status filter
        $status = request('status');
        $user = session()->get('user');

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
            ->where('user_id', $user->id)
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

        $statuses = $this->statuses;

        return view('customer.orderShow', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting_payment,waiting_confirmation,processing,shipped,completed,cancelled,waiting_refund,refunded'
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

    public function confirmPickup($order_id, Request $request)
    {
        $order = Order::where('order_id', $order_id)->firstOrFail();
        $order->status = 'shipped';
        $order->order_sent_at = now();
        $order->save();
        return response()->json(['message' => 'Pesanan telah dikonfirmasi sebagai diproses']);
    }

    public function confirmPickupDone($order_id, Request $request)
    {
        $order = Order::where('order_id', $order_id)->firstOrFail();
        $order->status = 'completed';
        $order->completed_at = now();
        $order->save();
        return redirect()->route('orders.show', $order_id)
            ->with('success', 'Pesanan telah selesai');
    }

    public function confirmDelivery($order_id, Request $request)
    {
        $order = Order::where('order_id', $order_id)->firstOrFail();

        // Validasi status order
        if (!(
            $order->status === 'shipped' ||
            ($order->status === 'completed' && $order->courier === 'self_pickup'))) {
            return response()->json(['error' => 'Tidak dapat mengkonfirmasi pesanan yang belum dikirim'], 422);
        }

        // Validate ratings for each product
        $ratings = $request->input('ratings', []);
        foreach ($order->items as $item) {
            $productRating = $ratings[$item->product_id] ?? null;
            if (!$productRating || !isset($productRating['rating']) || $productRating['rating'] < 1 || $productRating['rating'] > 5) {
                return response()->json(['error' => 'Rating untuk semua produk wajib diisi (1-5)'], 422);
            }
        }

        DB::beginTransaction();
        try {
            // Update order status
            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Save ratings per product
            foreach ($order->items as $item) {
                $productRating = $ratings[$item->product_id];
                $rating = $item->products->ratings()->create([
                    'order_id' => $order->order_id,
                    'product_id' => $item->product_id,
                    'user_id' => $order->user_id,
                    'rating' => $productRating['rating'],
                    'review' => $productRating['review'] ?? null,
                ]);

                $product = $item->products;
                $totalRating = $product->average_rating * $product->rating_count;
                $product->rating_count += 1;
                $product->average_rating = ($totalRating + $productRating['rating']) / $product->rating_count;
                $product->save();

                // Handle media upload
                if ($request->hasFile("ratings.{$item->product_id}.media")) {
                    foreach ($request->file("ratings.{$item->product_id}.media") as $file) {
                        $path = $file->store('ratings', 'public');
                        $rating->media()->create(['file_path' => Storage::url($path)]);
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Pesanan telah dikonfirmasi sebagai diterima dan penilaian berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan saat mengkonfirmasi pesanan'], 500);
        }
    }

    public function getSnapToken($order_id)
    {
        $order = Order::where('order_id', $order_id)->firstOrFail();
        // Validasi status order
        if ($order->status !== 'waiting_payment') {
            return response()->json(['error' => 'Tidak dapat mendapatkan token snap untuk pesanan dengan status ini'], 422);
        }

        $midtransUrl = config('midtrans.is_production') ? 'https://app.midtrans.com/snap/v1/' : 'https://api.sandbox.midtrans.com/v2/';

        // cancel order first
        $cancelResponse = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(config('midtrans.server_key') . ':'),
            'Content-Type' => 'application/json',
        ])->post($midtransUrl . $order->order_id . '/cancel');

        // Check if cancel was successful
        if (!$cancelResponse->successful()) {
            $errorMsg = 'Gagal membatalkan transaksi Midtrans.';
            $json = $cancelResponse->json();
            if (isset($json['status_message'])) {
                $errorMsg .= ' ' . $json['status_message'];
            }
            return response()->json([
                'error' => $errorMsg
            ], 500);
        }

        $orderDetails = $order->items;
        $itemDetails = [];
        foreach ($orderDetails as $detail) {
            $product = $detail->products;
            $itemDetails[] = [
                'id' => $product->id,
                'price' => $product->price,
                'quantity' => $detail->quantity,
                'name' => $product->name
            ];
        }

        // Tambahkan ongkir sebagai item
        $itemDetails[] = [
            'id' => 'SHIPPING',
            'price' => $order->shipping_cost,
            'quantity' => 1,
            'name' => 'Ongkos Kirim (' . $order->courier . ' - ' . $order->service . ')'
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => $order->total,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->recipient_name,
                'email' => $order->recipient_email,
                'phone' => $order->recipient_phone,
                'billing_address' => [
                    'address' => $order->shipping_address,
                    'city' => $order->city,
                    'postal_code' => '',
                ],
                'shipping_address' => [
                    'address' => $order->shipping_address,
                    'city' => $order->city,
                    'postal_code' => '',
                ]
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s T'),
                'unit' => 'hours',
                'duration' => 24
            ]
        ];


        // 4. Dapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);
        return response()->json(['snap_token' => $snapToken]);
    }

    public function processRefund(Request $request, $orderId)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.selected' => 'required|accepted',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition' => 'required|in:new,opened,damaged,defective',
            'items.*.reason' => 'required|string|max:500',
            'items.*.photos' => 'required|array|min:1',
            'items.*.photos.*' => 'image|mimes:jpg,jpeg,png|max:5120',
            'items.*.videos' => 'required|array|min:1',
            'items.*.videos.*' => 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/mpeg|max:15728640',
            'note' => 'nullable|string|max:1000',
            'bank_name' => 'required_if:payment_method,virtual_account,bank_transfer',
            'bank_account' => 'required_if:payment_method,virtual_account,bank_transfer',
            'account_name' => 'required_if:payment_method,virtual_account,bank_transfer'
        ]);

        $order = Order::findOrFail($orderId);

        // Check if order is eligible for refund
        if (!$order->canRequestRefund()) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak memenuhi syarat untuk pengembalian dana'
            ], 400);
        }

        // Calculate total refund amount
        $totalRefund = 0;
        $refundItems = [];

        foreach ($request->items as $itemId => $itemData) {
            $orderItem = OrderDetail::find($itemId);

            if (!$orderItem || $orderItem->order_id !== $order->order_id) {
                continue;
            }

            // Calculate refund amount for this item
            $refundAmount = $orderItem->price * $itemData['quantity'];
            $totalRefund += $refundAmount;

            // Process photos
            $photos = [];
            foreach ($itemData['photos'] as $photo) {
                $path = $photo->store('refunds/photos', 'public');
                $photos[] = Storage::url($path);
            }

            // Process videos
            $videos = [];
            foreach ($itemData['videos'] as $video) {
                $path = $video->store('refunds/videos', 'public');
                $videos[] = Storage::url($path);
            }

            $refundItems[] = [
                'order_detail_id' => $itemId,
                'quantity' => $itemData['quantity'],
                'refund_amount' => $refundAmount,
                'reason' => $itemData['reason'],
                'condition' => $itemData['condition'],
                'images' => array_merge($photos, $videos)
            ];
        }

        if ($totalRefund <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada item yang valid untuk dikembalikan'
            ], 400);
        }

        // Create refund record
        $refundData = [
            'refund_id' => 'REF-' . Str::upper(Str::random(10)),
            'order_id' => $order->order_id,
            'user_id' => session()->get('user')->id,
            'amount' => $totalRefund,
            'status' => OrderRefund::STATUS_PENDING,
            'reason' => $request->note,
            'refund_method' => str_contains(strtolower($order->payment_method), 'virtual akun') ||
                str_contains(strtolower($order->payment_method), 'bank transfer')
                ? 'Bank Transfer'
                : 'Kartu Kredit',
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'account_name' => $request->account_name
        ];

        DB::beginTransaction();

        try {
            // Create refund
            $refund = OrderRefund::create($refundData);

            // Create refund items
            foreach ($refundItems as $item) {
                $images = $item['images'];
                unset($item['images']);

                $refundDetail = $refund->items()->create($item);

                // Store images as JSON
                $refundDetail->update(['images' => $images]);
            }

            // Update order status
            $order->update(['status' => Order::STATUS_WAITING_REFUND]);

            DB::commit();

            // Notify admin
            // Notification::send(User::admin()->get(), new NewRefundRequest($refund));

            return response()->json([
                'success' => true,
                'message' => 'Permintaan pengembalian dana berhasil diajukan',
                'data' => $refund
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            app('debugbar')->error('Refund processing error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pengembalian dana'
            ], 500);
        }
    }

    public function updateResi(Request $request, $orderId)
    {
        $order = OrderRefund::where('order_id', $orderId)->firstOrFail();
        if ($order->status !== OrderRefund::STATUS_APPROVED) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak dapat diupdate dengan status ini'
            ], 400);
        }

        $request->validate([
            'resi' => 'required|string|max:50',
        ]);

        $order->update([
            'resi' => $request->resi,
        ]);

        $order->order->update([
            'status' => Order::STATUS_COMPLETED,
        ]);

        return response()->json([
            'message' => 'Resi berhasil diupdate',
        ]);
    }
}
