<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Session;


class AdminUserController extends Controller implements HasMiddleware
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

    public function index(Request $request)
    {

        session()->put('menu', 'users');

        $search = $request->input('search');

        $customers = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.customer', compact('customers'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
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
            } else {
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
        try {

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
        } catch (\Exception $e) {
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

    // List all admin users (only accessible by owner)
    public function adminIndex(Request $request)
    {
        session()->put('menu', 'admin');

        $search = $request->input('search');

        $admins = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.useradmin', compact('admins'));
    }

    // Store new admin user
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['role'] = 'admin';
        $validated['password'] = bcrypt($validated['password']);
        $validated['active'] = true;

        User::create($validated);

        $user = User::where(['email' => $validated['email']])->first();
        $user->role = 'admin';
        $user->save();

        return redirect()->route('admin.useradmin.index')->with('success', 'Admin user created successfully.');
    }

    // Deactivate or activate an admin user
    public function toggleAdminStatus($id)
    {
        $admin = User::where('id', $id)->where('role', 'admin')->firstOrFail();
        $admin->active = !$admin->active;
        $admin->save();

        return response()->json(['success' => 'Admin status updated.', 'active' => $admin->active]);
    }
}
