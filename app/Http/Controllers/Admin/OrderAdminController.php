<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
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
        $order = Order::with([
            'user',
            'items'
        ])->findOrFail($id);

        // Status mapping
        $statusLabels = [
            'waiting_payment' => 'Menunggu Pembayaran',
            'waiting_confirmation' => 'Menunggu Konfirmasi',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'partially_refunded' => 'Pengembalian Sebagian',
            'refunded' => 'Dikembalikan'
        ];

        // Build timeline from datetime fields
        $statusHistory = [];

        // Waiting Payment
        if ($order->waiting_payment_at) {
            $statusHistory[] = [
                'status' => 'waiting_payment',
                'label' => $statusLabels['waiting_payment'],
                'time' => $order->waiting_payment_at->format('d M Y H:i'),
                'icon' => $this->getStatusIcon('waiting_payment'),
                'color' => $this->getStatusColor('waiting_payment'),
                'is_active' => $order->status === 'waiting_payment'
            ];
        }

        // Processing (order_processed_at)
        if ($order->order_processed_at) {
            $statusHistory[] = [
                'status' => 'processing',
                'label' => $statusLabels['processing'],
                'time' => $order->order_processed_at->format('d M Y H:i'),
                'icon' => $this->getStatusIcon('processing'),
                'color' => $this->getStatusColor('processing'),
                'is_active' => $order->status === 'processing'
            ];
        }

        // Shipped (order_sent_at)
        if ($order->order_sent_at) {
            $statusHistory[] = [
                'status' => 'shipped',
                'label' => $statusLabels['shipped'],
                'time' => $order->order_sent_at->format('d M Y H:i'),
                'icon' => $this->getStatusIcon('shipped'),
                'color' => $this->getStatusColor('shipped'),
                'is_active' => $order->status === 'shipped'
            ];
        }

        // Completed
        if ($order->completed_at) {
            $statusHistory[] = [
                'status' => 'completed',
                'label' => $statusLabels['completed'],
                'time' => $order->completed_at->format('d M Y H:i'),
                'icon' => $this->getStatusIcon('completed'),
                'color' => $this->getStatusColor('completed'),
                'is_active' => $order->status === 'completed'
            ];
        }

        // Cancelled (using updated_at if no specific field)
        if ($order->status === 'cancelled') {
            $statusHistory[] = [
                'status' => 'cancelled',
                'label' => $statusLabels['cancelled'],
                'time' => $order->updated_at->format('d M Y H:i'),
                'icon' => $this->getStatusIcon('cancelled'),
                'color' => $this->getStatusColor('cancelled'),
                'is_active' => true
            ];
        }

        // Sort timeline by datetime
        usort($statusHistory, function ($a, $b) {
            return strtotime($a['time']) - strtotime($b['time']);
        });

        // Mark current status as active if not already
        foreach ($statusHistory as &$status) {
            if ($status['status'] === $order->status) {
                $status['is_active'] = true;
            }
        }

        // Format data untuk view
        return view('admin.order.detail', [
            'order' => $order,
            'statusHistory' => $statusHistory,
            'lastStatus' => $statusLabels[$order->status] ?? $order->status,
            'orderItems' => $order->items,
            'paymentStatus' => $this->getPaymentStatus($order->payment_status ?? 'pending'),
            'cancelReason' => $order->cancel_reason
        ]);
    }

    private function getStatusIcon($status)
    {
        $icons = [
            'waiting_payment' => 'fas fa-clock',
            'waiting_confirmation' => 'fas fa-hourglass-half',
            'processing' => 'fas fa-cog',
            'shipped' => 'fas fa-truck',
            'completed' => 'fas fa-check-circle',
            'cancelled' => 'fas fa-times-circle',
            'partially_refunded' => 'fas fa-exchange-alt',
            'refunded' => 'fas fa-undo'
        ];

        return $icons[$status] ?? 'fas fa-info-circle';
    }

    private function getStatusColor($status)
    {
        $colors = [
            'waiting_payment' => 'warning',
            'waiting_confirmation' => 'secondary',
            'processing' => 'primary',
            'shipped' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            'partially_refunded' => 'warning',
            'refunded' => 'dark'
        ];

        return $colors[$status] ?? 'secondary';
    }

    private function getPaymentStatus($status)
    {
        return [
            'pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'warning'],
            'paid' => ['label' => 'Lunas', 'color' => 'success'],
            'expired' => ['label' => 'Kadaluarsa', 'color' => 'danger'],
            'failed' => ['label' => 'Gagal', 'color' => 'danger'],
            'refunded' => ['label' => 'Dikembalikan', 'color' => 'dark'],
            'partially_refunded' => ['label' => 'Pengembalian Sebagian', 'color' => 'warning']
        ][$status] ?? ['label' => 'Pending', 'color' => 'secondary'];
    }

    public function generateInvoice($id)
    {
        $order = Order::with(['items', 'customer'])->findOrFail($id);

        $pdf = PDF::loadView('invoice', compact('order'));

        return $pdf->stream('Invoice-' . $order->order_id . '.pdf');
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
            'order_processed_at' => now()
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
                'status' => 'shipped',
                'order_sent_at' => now()
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
