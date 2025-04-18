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
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Session;


class OrderAdminController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = Session::get('user');
                if ($user == null || ($user->role != 'admin' && $user->role != 'owner')) {
                    return redirect()->route('login');
                }
                return $next($request);
            }),
        ];
    }


    /**
     * Menampilkan daftar pesanan
     */
    public function orders(Request $request)
    {
        session()->put('menu', 'orders');

        $orders = Order::with('customer')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('order_code', 'like', '%' . $request->search . '%')
                        ->orWhereHas('customer', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->when($request->status && $request->status != 'all', function ($query) use ($request) {
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
    public function updateShipping(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            $order->update([
                'tracking_number' => $request->shipping_number,
                'status' => 'shipped'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nomor resi berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nomor resi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyelesaikan pesanan
     */
    public function completeOrder(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            if ($order->status !== 'shipped') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak dapat diselesaikan karena belum dikirim.'
                ], 400);
            }

            $order->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diselesaikan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get shipping information for an order
     */
    public function getShippingInfo($id)
    {
        $order = Order::findOrFail($id);

        return response()->json([
            'courier' => $order->courier,
            'service' => $order->service
        ]);
    }

    /**
     * Cancel an order
     */
    public function cancel(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            if (!in_array($order->status, ['waiting_payment', 'waiting_confirmation', 'processing'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak dapat dibatalkan karena status tidak sesuai.'
                ], 400);
            }

            $order->update([
                'status' => 'cancelled',
                'cancel_reason' => $request->cancel_reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
