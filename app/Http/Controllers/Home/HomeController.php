<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        $topProducts = $query->paginate(10)->take(10);
        $newProducts = $query->paginate(10)->take(10);
        $user = Session::get('user');

        return view('home', compact('topProducts', 'newProducts', 'user'));
    }

}   
