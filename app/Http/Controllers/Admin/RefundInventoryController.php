<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundInventory;
use App\Models\Product;
use Illuminate\Http\Request;

class RefundInventoryController extends Controller
{
    public function index(Request $request)
    {
        session()->put('menu', 'refund_inventory');

        $query = RefundInventory::with('product')
            ->where('status', 0) // Only show pending refunds
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })
                ->orWhere('order_id', 'like', "%$search%");
        }

        $refunds = $query->paginate(10);

        return view('admin.refundinventory.refund-inventory', compact('refunds'));
    }

    public function detail($id)
    {
        $refund = RefundInventory::with(['product', 'orderRefund'])->findOrFail($id);
        return response()->json($refund);
    }

    public function approve($id)
    {
        $refund = RefundInventory::findOrFail($id);

        // Update product stock
        $product = Product::find($refund->product_id);
        $product->stock += $refund->quantity;
        $product->save();

        // update inventory
        $inventory = $product->inventory;
        $inventory->quantity += $refund->quantity;
        $inventory->save();

        // Update refund status
        $refund->status = 1; // Approved
        $refund->save();

        return response()->json(['success' => 'Refund approved successfully']);
    }

    public function reject($id)
    {
        $refund = RefundInventory::findOrFail($id);
        $refund->status = 2; // Rejected
        $refund->save();

        return response()->json(['success' => 'Refund rejected successfully']);
    }
}
