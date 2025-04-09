<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        // $user = Session::get('user');
        // if ($user != null && $user->role != 'admin') {
        //     // User is not authenticated, redirect to login or throw exception
        //     return redirect('login');
        // }
        return $next($request);
    }
} 