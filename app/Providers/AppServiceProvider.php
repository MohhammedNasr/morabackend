<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\GuestLayout;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\Wallet\WalletService::class,
            \App\Services\Wallet\WalletService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        $this->app['router']->aliasMiddleware('supplier', \App\Http\Middleware\SupplierMiddleware::class);
        $this->app['router']->aliasMiddleware('check.user.role', \App\Http\Middleware\CheckUserRole::class);

        Blade::component('guest-layout', GuestLayout::class);
    }
}
