<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubOrder;
use App\Models\Representative;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\StorePolicy;
use App\Policies\StoreManagementPolicy;
use App\Policies\SubOrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Store::class => [StorePolicy::class, StoreManagementPolicy::class],
        Order::class => [OrderPolicy::class, \App\Policies\StoreOrderPolicy::class],
        Product::class => ProductPolicy::class,
        SubOrder::class => [SubOrderPolicy::class, \App\Policies\SupplierOrderPolicy::class],
        Representative::class => \App\Policies\RepresentativePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // Define role-based gates
        Gate::define('admin', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('supplier', function ($user) {
            return $user->hasRole('supplier');
        });

        Gate::define('store-owner', function ($user) {
            return $user->hasRole('store-owner');
        });
    }
}
