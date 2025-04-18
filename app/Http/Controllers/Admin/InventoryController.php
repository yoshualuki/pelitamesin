<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class InventoryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = Session::get('user');
                if ($user == null || ($user->role != 'owner')) {
                    return redirect()->route('login');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        session()->put('menu', 'inventory');

        // $inventories = Inventory::with('product')
        //     ->orderBy('last_restocked_at', 'desc')
        //     ->paginate(10);

        // return view('admin.inventory.index', compact('inventories'));
        $search = request('search');

        $query = Inventory::with('product')
            ->orderBy('last_restocked_at', 'desc');

        if ($search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        return view('admin.inventory.index', [
            'inventories' => $query->paginate(10),
            'totalItems' => Product::whereHas('inventories')->count(),
            'totalValue' => Inventory::sum('total_cost'),
            'lowStockCount' => DB::table('inventories')
                ->select('product_id')
                ->groupBy('product_id')
                ->havingRaw('SUM(quantity) < 10')
                ->count(),
            'recentlyAddedCount' => Inventory::where('created_at', '>', now()->subDays(7))->count(),
            'lowStockItems' => Inventory::with('product')
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('product_id')
                ->having('total_quantity', '<', 10)
                ->orderBy('total_quantity')
                ->limit(5)
                ->get(),
        ]);
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.inventory.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $inventory = Inventory::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'unit_cost' => $validated['unit_cost'],
            'total_cost' => $validated['unit_cost'] * $validated['quantity'],
            'last_restocked_at' => now(),
        ]);

        $product = $inventory->product;
        $product->stock += $inventory->quantity;
        $product->save();

        return response()->json(['success' => 'Inventory item added successfully!']);
    }

    public function edit(Inventory $inventory)
    {
        // Return JSON response for AJAX requests (modal)
        if (request()->ajax()) {
            return response()->json([
                'inventory' => $inventory,
                'product' => $inventory->product,
                'current_stock' => $inventory->product->current_stock ?? 0
            ]);
        }

        // Fallback for non-AJAX requests (if needed)
        $products = Product::all();
        return view('admin.inventory.edit', compact('inventory', 'products'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0.01'
        ]);

        $inventory = Inventory::findOrFail($request->id);

        // Calculate stock difference
        $quantityDifference = $validated['quantity'] - $inventory->quantity;

        $inventory->update([
            'quantity' => $validated['quantity'],
            'unit_cost' => $validated['unit_cost'],
            'total_cost' => $validated['quantity'] * $validated['unit_cost']
        ]);

        // Update product stock
        $product = $inventory->product;
        $product->stock += $quantityDifference;
        $product->save();

        return response()->json([
            'message' => 'Stock updated successfully!'
        ]);
    }

    public function destroy(Inventory $inventory)
    {
        try {
            DB::transaction(function () use ($inventory) {
                $product = $inventory->product;

                // Reduce product stock before deletion
                $product->stock -= $inventory->quantity;
                $product->save();

                $inventory->delete();
            });

            return response()->json([
                'message' => 'Stok berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus stok. ' . $e->getMessage()
            ], 500);
        }
    }
}
