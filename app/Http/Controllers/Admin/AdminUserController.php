<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;   
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;


class AdminUserController extends Controller
{

    public function index(Request $request)
    {
        
        session()->put('menu', 'users');
        
        $search = $request->input('search');
        
        $customers = User::query()
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->where('role','user')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.customer', compact('customers'));
    }

    private function checkAdminAuth()
    {
        if (Auth::guard('users')->check()) {
            $user = Auth::guard('users')->user();
            if ($user->role === 'admin') {
                return true; // Pengguna sudah login sebagai admin
            }
            return false; // Pengguna sudah login tetapi bukan admin
        }
        return false; // Pengguna belum login
    }


    public function create() {
        return view('admin.products.create');
    }

    public function store(Request $request) {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string|max:2000',
            'brand' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
                $validated['image'] = 'images/products/' . $imageName;
            }
            else{
                $validated['image'] = 'images/products/default.jpg';
            }
            // Menyimpan produk jika validasi berhasil
            Product::create($validated);
            
            // Mengembalikan respons untuk AJAX
            // return response()->json(['success' => 'Produk berhasil ditambahkan']);
            return redirect()->route('admin.product')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            // Mengembalikan pesan kesalahan untuk AJAX
            return redirect()->route('admin.product')->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        try{
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'weight' => 'required|numeric|min:0',
                'description' => 'required|string|max:2000',
                'brand' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);
    
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
                $validated['image'] = 'images/products/' . $imageName;
            }
    
            $product->update($validated);
    
            return redirect()->route('admin.product')->with('success', 'Produk berhasil diupdate');
        }catch(\Exception $e){
            return redirect()->route('admin.product.edit', $product)->with('error', 'Gagal mengupdate produk: ' . $e->getMessage());
        }
    }



    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('admin.product')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.product')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    
}
