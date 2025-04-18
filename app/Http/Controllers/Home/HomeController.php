<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get new arrivals - latest added products with stock > 0
        $newProducts = Product::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get top products based on orders, if none exist get random products
        $topProducts = Product::where('stock', '>', 0)
            ->whereHas('orderItems', function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->whereIn('status', ["'completed'", "'shipped'"]);
                });
            })
            ->withCount(['orderItems' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->whereIn('status', ["'completed'", "'shipped'"]);
                });
            }])
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        // If no top products found, get random products
        if ($topProducts->isEmpty()) {
            $topProducts = Product::where('stock', '>', 0)
                ->inRandomOrder()
                ->take(10)
                ->get();
        }

        $user = Session::get('user');

        return view('home', compact('topProducts', 'newProducts', 'user'));
    }
}
