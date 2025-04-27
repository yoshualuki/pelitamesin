<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderRefund;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = Session::get('user');
                if ($user == null || ($user->role == 'user')) {
                    return redirect()->route('login');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        session()->put('menu', 'refunds');
        $refunds = OrderRefund::with(['order', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.refund.index', compact('refunds'));
    }

    public function showDetail($id)
    {
        $refund = OrderRefund::with(['order', 'user', 'items'])->findOrFail($id);

        return response()->json([
            'refund_id' => $refund->refund_id,
            'order' => [
                'order_id' => $refund->order->order_id
            ],
            'user' => [
                'name' => $refund->user->name
            ],
            'amount' => $refund->amount,
            'status' => $refund->status,
            'refund_method' => $refund->refund_method,
            'bank_account' => $refund->bank_account,
            'bank_name' => $refund->bank_name,
            'account_name' => $refund->account_name,
            'reason' => $refund->reason,
            'admin_notes' => $refund->admin_notes,
            'updated_at' => $refund->updated_at,
            'created_at' => $refund->created_at,
            'items' => $refund->items->map(function ($item) {
                return [
                    'product_name' => $item->orderItem->products->name,
                    'quantity' => $item->quantity,
                    'reason' => $item->reason,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'id' => $item->id,
                    'order_item_id' => $item->order_item_id,
                    'refund_id' => $item->refund_id,
                    'product_id' => $item->product_id,
                    'refund_amount' => $item->refund_amount,
                    'condition' => $item->condition,
                    'images' => $item->images,
                ];
            })
        ]);
    }

    public function approve(Request $request, $id)
    {
        $refund = OrderRefund::findOrFail($id);

        if ($refund->status !== OrderRefund::STATUS_PENDING) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Refund tidak dapat disetujui karena status bukan pending'], 400);
            }
            return redirect()->back()->with('error', 'Refund tidak dapat disetujui karena status bukan pending');
        }

        DB::beginTransaction();
        try {
            $refund->update([
                'status' => OrderRefund::STATUS_APPROVED,
                'approved_at' => now(),
            ]);
            $refund->order->update([
                'status' => 'refunded'
            ]);
            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Refund berhasil disetujui']);
            }
            return redirect()->route('admin.refund')->with('success', 'Refund berhasil disetujui');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat menyetujui refund'], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui refund');
        }
    }

    public function reject(Request $request, $id)
    {
        $refund = OrderRefund::findOrFail($id);

        if ($refund->status !== OrderRefund::STATUS_PENDING) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Refund tidak dapat ditolak karena status bukan pending'], 400);
            }
            return redirect()->back()->with('error', 'Refund tidak dapat ditolak karena status bukan pending');
        }
        DB::beginTransaction();
        $refund->update([
            'status' => OrderRefund::STATUS_REJECTED,
            'rejected_at' => now(),
            'admin_notes' => $request->rejection_reason,
        ]);
        $refund->order->update([
            'status' => 'completed'
        ]);
        DB::commit();

        return response()->json(['success' => true, 'message' => 'Refund berhasil ditolak']);
    }
}
