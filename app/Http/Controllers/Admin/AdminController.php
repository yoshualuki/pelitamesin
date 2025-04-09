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
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class AdminController extends Controller
{
    const STATUS_WAITING_PAYMENT = 'waiting_payment';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    const STATUS_REFUNDED = 'refunded';

    // Atau bisa juga dalam bentuk array
    const ORDER_STATUSES = [
        'completed' => 'Selesai',
        'pending' => 'Tertunda',
        'processing' => 'Diproses',
        'cancelled' => 'Dibatalkan',
        'failed' => 'Gagal',
        'waiting_confirmation' => 'Menunggu Konfirmasi'
    ];

    private function checkAdminAuth()
    {
        $user = Session::get('user');
        if ($user != null && $user->role == 'admin') {
            return true;
        }
        return false;
    }
    public function dashboard(Request $request)
    {
        session()->put('menu', 'dashboard');
        // Date ranges
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $lastWeek = Carbon::today()->subDays(7);
        $lastMonth = Carbon::today()->subDays(30);
        $lastYear = Carbon::today()->subYear();
    
        $range = $request->input('range', 'week');
        
        // Order statistics
        $totalOrders = Order::where('status', '!=', self::STATUS_CANCELLED)->count();
        $monthlyOrders = Order::where('created_at', '>=', $lastMonth)->count();
        $weeklyOrders = Order::where('created_at', '>=', $lastWeek)->count();
        $dailyOrders = Order::whereDate('created_at', $today)->count();
        
        // Revenue calculations
        $totalRevenue = Order::where('status', '!=', self::STATUS_CANCELLED)->sum('total_amount');
        $monthlyRevenue = Order::where('status', 'completed')
                              ->where('created_at', '>=', $lastMonth)
                              ->sum('total_amount');
        
        // Pending and failed orders
        $pendingOrders = Order::where('status', 'pending')->count();
        $failedOrders = Order::where('status', 'failed')->count();
    
        // Calculate percentage changes
        $totalOrdersChange = $this->calculatePercentageChange(
            Order::where('created_at', '<', $lastMonth)->count(),
            $totalOrders
        );
        
        $revenueChange = $this->calculatePercentageChange(
            Order::where('status', 'completed')
                 ->whereBetween('created_at', [$lastYear, $lastMonth])
                 ->sum('total_amount'),
            $monthlyRevenue
        );
        
        $pendingChange = $this->calculatePercentageChange(
            Order::where('status', 'pending')
                 ->whereBetween('created_at', [$lastWeek->subDays(7), $lastWeek])
                 ->count(),
            $pendingOrders
        );
        
        $dailyChange = $this->calculatePercentageChange(
            Order::whereDate('created_at', $yesterday)->count(),
            $dailyOrders
        );

        $topProducts = Product::select([
                'products.*',
                DB::raw('COALESCE(SUM(order_details.quantity), 0) as sales_count'),
                DB::raw('COALESCE(SUM(order_details.quantity * order_details.price), 0) as revenue')
            ])
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.order_id')
            // ->where('orders.status', 'completed')
            ->groupBy('products.id')
            ->orderByDesc('sales_count')
            ->take(5)
            ->get();

        app('debugbar')->info($topProducts);
    
        return view('admin.dashboard', [
            // Order statistics
            'totalOrders' => $totalOrders,
            'monthlyOrders' => $monthlyOrders,
            'weeklyOrders' => $weeklyOrders,
            'dailyOrders' => $dailyOrders,
            
            // Revenue
            'totalRevenue' => $totalRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            
            // Order status counts
            'pendingOrders' => $pendingOrders,
            'failedOrders' => $failedOrders,
            
            // Percentage changes for UI
            'totalOrdersChange' => $totalOrdersChange,
            'revenueChange' => $revenueChange,
            'pendingChange' => $pendingChange,
            'dailyChange' => $dailyChange,
            
            // Chart data
            'orderChartData' => $this->getOrderChartData($range === 'today' ? 1 : ($range === 'week' ? 7 : ($range === 'year' ? 365 : 30))),
            
            // Recent transactions
            'recentTransactions' => Order::with(['items.product'])
                                    ->latest()
                                    ->take(8)
                                    ->get(),
            
            // Top products
            'topProducts' => $topProducts,
            
            // Order status distribution
            'orderStatusData' => $this->getOrderStatusData()
        ]);
    }
    
    protected function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue == 0 ? 0 : 100;
        }
        
        return round((($newValue - $oldValue) / $oldValue) * 100, 1);
    }
    
    protected function getOrderChartData($days = 30)
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($days);
        
        $dates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dates[$currentDate->format('Y-m-d')] = 0;
            $currentDate->addDay();
        }
        
        $orders = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');
        
        return [
            'labels' => array_keys($dates),
            'data' => array_values(array_merge($dates, $orders->toArray()))
        ];
    }
    
    protected function getOrderStatusData()
    {
        $statuses = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $statusColors = [
            'waiting_payment' => '#FFA500', // Orange
            'waiting_confirmation' => '#4e73df', // Biru
            'processing' => '#36b9cc', // Biru muda
            'shipped' => '#6c757d', // Abu-abu
            'completed' => '#1cc88a', // Hijau
            'cancelled' => '#e74a3b', // Merah
            'partially_refunded' => '#fd7e14', // Orange tua
            'refunded' => '#6f42c1' // Ungu
        ];

        return $statuses->map(function($item) use ($statusColors) {
            return [
                'status' => $item->status,
                'count' => $item->count,
                'color' => $statusColors[strtolower($item->status)] ?? '#858796'
            ];
        });
    }

    public function chartData(Request $request)
    {
        $period = $request->input('period', 'week');
        
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($period === 'year' ? 365 : ($period === 'month' ? 30 : 7));

        // Format label yang lebih friendly
        $dateFormat = $period === 'year' ? 'M Y' : 'D, d M';
        
        $orders = Order::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"),
                DB::raw('count(*) as count')
            )
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        // Buat range tanggal lengkap
        $labels = [];
        $data = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dateKey = $currentDate->format('Y-m-d');
            $formattedLabel = $currentDate->format($dateFormat);
            
            $labels[] = $formattedLabel;
            
            $order = $orders->firstWhere('date', $dateKey);
            $data[] = $order ? $order->count : 0;
            
            $currentDate->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'status_data' => $this->getOrderStatusData($startDate, $endDate)
        ]);
    }

    protected function getOrderTrendData($startDate, $endDate, $period)
    {
        $groupFormat = 'Y-m-d'; // Default format harian
        
        if ($period === 'year') {
            $groupFormat = 'Y-m'; // Grup per bulan untuk tampilan tahunan
        }

        $dates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dates[$currentDate->format($groupFormat)] = 0;
            if ($period === 'year') {
                $currentDate->addMonth();
            } else {
                $currentDate->addDay();
            }
        }

        $orders = Order::select(
                DB::raw("DATE_FORMAT(created_at, '".$groupFormat."') as date"),
                DB::raw('count(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        return [
            'labels' => array_keys($dates),
            'data' => array_values(array_merge($dates, $orders->toArray()))
        ];
    }

    public function customer(Request $request) {
        session()->put('menu', 'users');
        
        $search = $request->input('search');
        
        $customers = User::query()
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->where('role', 'users')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.customer', compact('customers'));
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
            return response()->json(['success' => 'Produk berhasil ditambahkan']);
        } catch (\Exception $e) {
            // Mengembalikan pesan kesalahan untuk AJAX
            return response()->json(['error' => 'Gagal menambahkan produk: ' . $e->getMessage()], 500);
        }
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
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


    /**
     * Menampilkan daftar pesanan
     */
    public function orders(Request $request)
    {
        $orders = Order::with('customer')
            ->when($request->search, function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('order_code', 'like', '%'.$request->search.'%')
                      ->orWhereHas('customer', function($q) use ($request) {
                          $q->where('name', 'like', '%'.$request->search.'%');
                      });
                });
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orders', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan
     */
    public function showOrder($id)
    {
        $order = Order::with(['customer', 'orderItems.product'])->findOrFail($id);
        return view('admin.order-detail', compact('order'));
    }

    /**
     * Konfirmasi pesanan
     */
    public function confirmOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status != 'pending') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dikonfirmasi karena status bukan pending.');
        }

        $order->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // Kirim notifikasi ke customer
        // ...

        return redirect()->route('admin.orders')->with('success', 'Pesanan berhasil dikonfirmasi.');
    }

    /**
     * Input resi pengiriman
     */
    public function shipOrder(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string',
            'shipping_company' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        
        if ($order->status != 'confirmed') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dikirim karena belum dikonfirmasi.');
        }

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $request->tracking_number,
            'shipping_company' => $request->shipping_company,
            'shipped_at' => now()
        ]);

        // Kirim notifikasi ke customer
        // ...

        return redirect()->route('admin.orders')->with('success', 'Resi pengiriman berhasil disimpan.');
    }

    /**
     * Menyelesaikan pesanan
     */
    public function completeOrder($id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status != 'shipped') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat diselesaikan karena belum dikirim.');
        }

        $order->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->route('admin.orders')->with('success', 'Pesanan berhasil diselesaikan.');
    }
    
}
