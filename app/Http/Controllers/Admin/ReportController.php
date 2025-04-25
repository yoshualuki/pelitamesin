<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;

use App\Models\OrderDetail;
use App\Models\OrderRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ReportController extends Controller implements HasMiddleware
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

    // Daily Transactions Report
    public function dailyTransactions(Request $request)
    {
        session()->put('menu', 'daily-transactions');
        $date = $request->input('date', now()->format('Y-m-d'));

        $transactions = Order::with(['items.products'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTransactions = $transactions->count();
        $totalRevenue = $transactions->sum('final_amount');
        $averageOrderValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        return view('admin.reports.daily-transactions', [
            'transactions' => $transactions,
            'date' => $date,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'averageOrderValue' => $averageOrderValue
        ]);
    }

    // Low Stock Report
    public function lowStock()
    {
        session()->put('menu', 'low-stock');
        // Threshold for low stock (adjust as needed)
        $threshold = 10;

        $lowStockItems = Product::with(['inventories'])
            ->whereHas('inventories', function ($query) use ($threshold) {
                $query->where('quantity', '<=', $threshold);
            })
            ->orWhereDoesntHave('inventories')
            ->orderBy('name')
            ->get();

        return view('admin.reports.low-stock', [
            'lowStockItems' => $lowStockItems,
            'threshold' => $threshold
        ]);
    }

    // Monthly Profit Report
    public function monthlyProfit(Request $request)
    {
        session()->put('menu', 'monthly-profit');
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Get all completed orders for the month with their details
        $transactions = Order::with(['items.products'])
            ->where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->orderBy('completed_at', 'desc')
            ->get();

        // Calculate revenue
        $revenue = $transactions->sum('final_amount');

        // Calculate COGS
        $cogs = $transactions->reduce(function ($carry, $order) {
            return $carry + $order->items->sum(function ($item) {
                return $item->buy_price * $item->quantity;
            });
        }, 0);

        // Calculate profit
        $profit = $revenue - $cogs;

        return view('admin.reports.monthly-profit', [
            'year' => $year,
            'month' => $month,
            'revenue' => $revenue,
            'cogs' => $cogs,
            'profit' => $profit,
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    // Product Returns Report
    public function productReturns(Request $request)
    {
        session()->put('menu', 'product-return');
        $returns = OrderRefund::with(['order', 'items.orderItem.product'])
            ->where('status', '!=', OrderRefund::STATUS_REJECTED)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRefunds = $returns->count();
        $totalRefundAmount = $returns->sum('amount');

        return view('admin.reports.product-returns', [
            'returns' => $returns,
            'totalRefunds' => $totalRefunds,
            'totalRefundAmount' => $totalRefundAmount
        ]);
    }

    // Top Selling Products Report
    public function topProducts(Request $request)
    {
        session()->put('menu', 'top-products');
        $limit = $request->input('limit', 10);
        $timeframe = $request->input('timeframe', 'month'); // day, week, month, year

        $startDate = match ($timeframe) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $topProducts = Product::select([
            'products.id',
            'products.name',
            'products.image',
            'products.price',
            DB::raw('SUM(order_details.quantity) as total_quantity'),
            DB::raw('SUM(order_details.price * order_details.quantity) as total_revenue')
        ])
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->where('orders.completed_at', '>=', $startDate)
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_quantity')
            ->take($limit)
            ->get();

        return view('admin.reports.top-products', [
            'topProducts' => $topProducts,
            'timeframe' => $timeframe,
            'limit' => $limit
        ]);
    }

    // Cancelled Orders Report
    public function cancelledOrders(Request $request)
    {
        session()->put('menu', 'cancelled-orders');
        $timeframe = $request->input('timeframe', 'month'); // day, week, month, year

        $startDate = match ($timeframe) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->subMonth(),
        };

        $cancelledOrders = Order::with(['customer', 'items.products'])
            ->where('status', Order::STATUS_CANCELLED)
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCancelled = $cancelledOrders->count();
        $totalAmount = $cancelledOrders->sum('final_amount');

        return view('admin.reports.cancelled-orders', [
            'cancelledOrders' => $cancelledOrders,
            'totalCancelled' => $totalCancelled,
            'totalAmount' => $totalAmount,
            'timeframe' => $timeframe
        ]);
    }

    // ReportController.php
    public function topRated(Request $request)
    {
        session()->put('menu', 'top-rated');
        $limit = $request->input('limit', 10); // Default to 10 products
        $timeRange = $request->input('time_range', 'month'); // month, quarter, year, all

        $topRated = Product::select()
            // ->withCount(['ratings as rating_count'])
            // ->withAvg('ratings', 'rating')
            // ->withCount(['orderItems as sales_count'])
            // ->when($timeRange !== 'all', function ($query) use ($timeRange) {
            //     $this->applyTimeRange($query, $timeRange);
            // })
            // ->orderBy('sales_count', 'desc')
            ->orderBy('average_rating', 'desc')
            ->orderBy('rating_count', 'desc')
            ->take($limit)
            ->get();
        app('debugbar')->info($topRated->toArray());
        // Calculate average rating and rating count


        return view('admin.reports.top-rated', [
            'topRated' => $topRated,
            'limit' => $limit,
            'timeRange' => $timeRange
        ]);
    }

    public function productReviews(Product $product, Request $request)
    {
        $ratingFilter = $request->input('rating');

        $reviews = $product->ratings()
            ->with(['user', 'images'])
            ->when($ratingFilter, function ($query) use ($ratingFilter) {
                $query->where('rating', $ratingFilter);
            })
            ->latest()
            ->paginate(10);

        return view('admin.reports.product-reviews', [
            'product' => $product,
            'reviews' => $reviews,
            'ratingFilter' => $ratingFilter
        ]);
    }

    protected function applyTimeRange($query, $timeRange)
    {
        $now = now();

        switch ($timeRange) {
            case 'month':
                $query->whereHas('ratings', function ($q) use ($now) {
                    $q->whereBetween('created_at', [$now->startOfMonth(), $now->endOfMonth()]);
                });
                break;

            case 'quarter':
                $query->whereHas('ratings', function ($q) use ($now) {
                    $q->whereBetween('created_at', [$now->subMonths(3), $now]);
                });
                break;

            case 'year':
                $query->whereHas('ratings', function ($q) use ($now) {
                    $q->whereBetween('created_at', [$now->startOfYear(), $now->endOfYear()]);
                });
                break;
        }
    }

    // Unsold Products Report
    public function unsoldProducts(Request $request)
    {
        session()->put('menu', 'unsold-products');
        $timeframe = $request->input('timeframe', 'month'); // day, week, month, year

        $startDate = match ($timeframe) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->subMonth(),
        };

        $unsoldProducts = Product::select(['products.*'])
            ->whereDoesntHave('orderItems', function ($query) use ($startDate) {
                $query->whereHas('order', function ($q) use ($startDate) {
                    $q->where('status', Order::STATUS_COMPLETED)
                        ->where('completed_at', '>=', $startDate);
                });
            })
            ->orderBy('name')
            ->get();

        return view('admin.reports.unsold-products', [
            'unsoldProducts' => $unsoldProducts,
            'timeframe' => $timeframe
        ]);
    }

    // Payment Methods Report
    public function paymentMethods(Request $request)
    {
        $timeframe = $request->input('timeframe', 'month'); // day, week, month, year

        $startDate = match ($timeframe) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->subMonth(),
        };

        $paymentMethods = Order::select([
            'payment_method',
            DB::raw('COUNT(*) as order_count'),
            DB::raw('SUM(final_amount) as total_amount')
        ])
            ->where('status', Order::STATUS_COMPLETED)
            ->where('completed_at', '>=', $startDate)
            ->groupBy('payment_method')
            ->orderByDesc('total_amount')
            ->get();

        return view('admin.reports.payment-methods', [
            'paymentMethods' => $paymentMethods,
            'timeframe' => $timeframe
        ]);
    }

    // Low Rated Products Report
    public function lowRated(Request $request)
    {
        // Assuming you have a product_ratings table
        // Adjust based on your actual rating system
        $threshold = $request->input('threshold', 3);
        $limit = $request->input('limit', 10);

        $lowRated = Product::select([
            'products.id',
            'products.name',
            'products.price',
            DB::raw('AVG(product_ratings.rating) as average_rating'),
            DB::raw('COUNT(product_ratings.id) as rating_count')
        ])
            ->leftJoin('product_ratings', 'products.id', '=', 'product_ratings.product_id')
            ->groupBy('products.id', 'products.name', 'products.price')
            ->having('average_rating', '<', $threshold)
            ->having('rating_count', '>', 0)
            ->orderBy('average_rating')
            ->orderBy('rating_count', 'desc')
            ->take($limit)
            ->get();

        return view('admin.reports.low-rated', [
            'lowRated' => $lowRated,
            'threshold' => $threshold,
            'limit' => $limit
        ]);
    }
}
