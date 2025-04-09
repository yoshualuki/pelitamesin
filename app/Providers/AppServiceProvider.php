<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RajaOngkirService::class, function ($app) {
            return new RajaOngkirService(config('rajaongkir.api_key'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
        
        Paginator::useBootstrap();
        
        Blade::directive('money', function (string $expression) {
            return "<?php echo 'Rp ' . number_format($expression, 0, ',', '.'); ?>";
        });
    }
}
