<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->brand) {
            $query->where('brand', $request->brand);
        }

        if($request->q){
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->paginate(9);
        return view('products', compact('products'));
    }

    public function products(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string|max:500',
            'brand' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        } else {
            $validated['image'] = 'images/products/default.jpg';
        }

        // Sebelum menyimpan produk
        dd($request->all());

        Product::create($validated);

        return response()->json(['success' => 'Produk berhasil ditambahkan']);
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string|max:500',
            'brand' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            // Add other validation rules as needed
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            // Update other fields as needed
        ]);

        return redirect()->route('products')->with('success', 'Produk berhasil diperbarui.');
    }
    
    public function show($id)
    {
        $product = Product::all()
                    ->findOrFail($id);
        
        // Get related products (same brand)
        $relatedProducts = Product::where('brand', $product->brand)
                            ->where('id', '!=', $product->id)
                            ->inRandomOrder()
                            ->limit(4)
                            ->get();

        return view('customer.productDetail', compact('product', 'relatedProducts'));
    }

}   
