<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    protected $rajaOngkirService;

    public function __construct()
    {
        // Initialize Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        
        // Initialize RajaOngkir
        $this->rajaOngkirService = new RajaOngkirService(config('rajaongkir.api_key'));
    }

    public function index()
    {
        if(!session()->get('user')) {
            return redirect()->route('login');
        }
        
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;
        $totalWeight = 0;
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $product->quantity = $details['quantity'];
                $products[] = $product;
                $total += $product->price * $details['quantity'];
                $totalWeight += $product->weight * $details['quantity'];
            }
        }

        $user = session()->get('user');
        return view('customer.cart', compact('products', 'user', 'total', 'totalWeight'));
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return count($cart);
    }

    public function destroy($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->route('cart')->with('success', 'Produk berhasil dihapus dari keranjang');
        }
        return redirect()->route('cart')->with('error', 'Produk tidak ditemukan di keranjang');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $product = Product::find($id);
            
            if($request->quantity > $product->stock) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia');
            }
            
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->route('cart')->with('success', 'Jumlah produk berhasil diperbarui');
        }
        
        return redirect()->route('cart')->with('error', 'Produk tidak ditemukan di keranjang');
    }

    public function addToCart(Request $request)
    {
        if (!session()->has('user')) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk sedang habis'
            ], 404);
        }

        if ($request->quantity > $product->stock) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jumlah melebihi stok yang tersedia'
            ], 404);
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $request->quantity;
            if($newQuantity > $product->stock) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total jumlah melebihi stok yang tersedia'
                ], 404);
            }
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'image' => $product->image,
                'weight' => $product->weight
            ];
        }

        session()->put('cart', $cart);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'totalQuantity' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        $user = session()->get('user');
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja Anda kosong');
        }

        try {
            $provinces = $this->rajaOngkirService->getProvinces();
            $provinces = $provinces['rajaongkir']['results'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error fetching provinces: ' . $e->getMessage());
            $provinces = [];
        }

        // Hitung total sementara (tanpa ongkir)
        $products = [];
        $subtotal = 0;
        $totalWeight = 0;
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $product->quantity = $details['quantity'];
                $products[] = $product;
                $subtotal += $product->price * $details['quantity'];
                $totalWeight += $product->weight * $details['quantity'];
            }
        }

        return view('customer.checkout', compact(
            'products',
            'subtotal',
            'provinces',
            'totalWeight'
        ));
    }

    public function getCities(Request $request)
    {
        $request->validate([
            'province_id' => 'required|numeric'
        ]);

        try {
            $response = $this->rajaOngkirService->getCities($request->province_id);
            return response()->json([
                'success' => true,
                'data' => $response['rajaongkir']['results'] ?? []
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching cities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota'
            ], 500);
        }
    }

    public function getDistricts(Request $request)
    {
        $request->validate([
            'city_id' => 'required|numeric'
        ]);

        try {
            $response = $this->rajaOngkirService->getDistricts($request->city_id);
            return response()->json([
                'success' => true,
                'data' => $response['rajaongkir']['results'] ?? []
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching districts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kecamatan'
            ], 500);
        }
    }

    public function getShippingCost(Request $request)
    {
        $request->validate([
            'destination' => 'required|numeric',
            'courier' => 'required|string|in:jne,tiki,pos',
            'weight' => 'required|numeric|min:1'
        ]);

        try {
            $response = $this->rajaOngkirService->getCost(
                config('rajaongkir.origin'),
                $request->destination,
                30000,
                $request->courier
            );
            
            if (!isset($response['rajaongkir']['results'][0]['costs'])) {
                throw new \Exception('Invalid shipping cost response');
            }
            
            return response()->json([
                'success' => true,
                'courier' => $response['rajaongkir']['results'][0]['code'],
                'services' => $response['rajaongkir']['results'][0]['costs']
            ]);
        } catch (\Exception $e) {
            Log::error('Error calculating shipping cost: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim'
            ], 500);
        }
    }
}