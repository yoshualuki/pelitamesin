<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
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
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        return view('admin.inventory.index', [
            'inventories' => $query->paginate(10),
            'totalItems' => Inventory::count(),
            'totalValue' => Inventory::sum('total_cost'),
            'lowStockCount' => Inventory::where('quantity', '<', 10)->count(),
            'recentlyAddedCount' => Inventory::where('created_at', '>', now()->subDays(7))->count(),
            'lowStockItems' => Inventory::with('product')
                ->where('quantity', '<', 10)
                ->orderBy('quantity')
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
            'last_restocked_at' => now(),
        ]);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item added successfully!');
    }

    public function edit(Inventory $inventory)
    {
        $products = Product::all();
        return view('admin.inventory.edit', compact('inventory', 'products'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $inventory->update([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'unit_cost' => $validated['unit_cost'],
            'last_restocked_at' => now(),
        ]);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item updated successfully!');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item deleted successfully!');
    }
}