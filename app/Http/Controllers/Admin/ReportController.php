<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\OrderDetail;
use App\Models\OrderRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    // Daily Transactions Report
    public function dailyTransactions(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $transactions = Order::with(['items.product'])
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
        // Threshold for low stock (adjust as needed)
        $threshold = 10;

        $lowStockItems = Product::with(['inventory'])
            ->whereHas('inventory', function ($query) use ($threshold) {
                $query->where('quantity', '<=', $threshold);
            })
            ->orWhereDoesntHave('inventory')
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
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Calculate revenue from completed orders
        $revenue = Order::where('status', Order::STATUS_COMPLETED)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->sum('final_amount');

        // Calculate cost of goods sold from order details
        $cogs = OrderDetail::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->where('status', Order::STATUS_COMPLETED)
                ->whereBetween('completed_at', [$startDate, $endDate]);
        })
            ->sum(DB::raw('buy_price * quantity'));

        // Calculate expenses (you'll need to implement this based on your expense tracking)
        $expenses = 0; // Placeholder - implement based on your expense model

        $grossProfit = $revenue - $cogs;
        $netProfit = $grossProfit - $expenses;

        return view('admin.reports.monthly-profit', [
            'year' => $year,
            'month' => $month,
            'revenue' => $revenue,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'expenses' => $expenses,
            'netProfit' => $netProfit,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    // Product Returns Report
    public function productReturns(Request $request)
    {
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
        $timeframe = $request->input('timeframe', 'month'); // day, week, month, year

        $startDate = match ($timeframe) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->subMonth(),
        };

        $cancelledOrders = Order::with(['customer', 'items.product'])
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

    // Top Rated Products Report
    public function topRated(Request $request)
    {
        // Assuming you have a product_ratings table
        // Adjust based on your actual rating system
        $limit = $request->input('limit', 5);

        $topRated = Product::select([
            'products.id',
            'products.name',
            'products.price',
            DB::raw('AVG(product_ratings.rating) as average_rating'),
            DB::raw('COUNT(product_ratings.id) as rating_count')
        ])
            ->leftJoin('product_ratings', 'products.id', '=', 'product_ratings.product_id')
            ->groupBy('products.id', 'products.name', 'products.price')
            ->having('rating_count', '>', 0)
            ->orderByDesc('average_rating')
            ->orderByDesc('rating_count')
            ->take($limit)
            ->get();

        return view('admin.reports.top-rated', [
            'topRated' => $topRated,
            'limit' => $limit
        ]);
    }

    // Unsold Products Report
    public function unsoldProducts(Request $request)
    {
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
