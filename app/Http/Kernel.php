<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // Other middleware...
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'authcheck' => \App\Http\Middleware\AdminAuthCheckMiddleware::class,
        'token' => \App\Http\Middleware\VerifyCsrfToken::class,
        // Other middleware...
    ];
} 