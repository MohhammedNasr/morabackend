<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\SupplierLoginController;
use App\Http\Controllers\Auth\StoreLoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Store\DashboardController;
use App\Http\Controllers\Store\OrderController;
use App\Http\Controllers\Store\ProfileController as StoreProfileController;
use App\Http\Controllers\Supplier\DashboardController as SupplierDashboardController;
use App\Http\Controllers\Supplier\OrderController as SupplierOrderController;
use App\Http\Controllers\Supplier\ProductController as SupplierProductController;
use App\Http\Controllers\Supplier\ProfileController as SupplierProfileController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\WalletTransactionController;
use App\Http\Controllers\Store\StoreBranchController;
use App\Http\Controllers\Admin\StoreBranchController as AdminStoreBranchController;
use App\Http\Controllers\Admin\SubOrderController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StoreUserController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\RepresentativeController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Supplier\RepresentativeController as SupplierRepresentativeController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StoreForgotPasswordController;
use App\Http\Controllers\Auth\StoreResetPasswordController;
use App\Http\Controllers\Auth\StoreRegisterController;
use App\Http\Controllers\Web\VerificationController as WebVerificationController;
use App\Http\Middleware\SetLocaleMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::middleware(SetLocaleMiddleware::class)->group(function () {
// Language Switching
Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LandingPageController::class, 'index'])->name('landing');
    Route::get('/login', function () {
        return redirect()->route('admin.login');
    })->name('login');
    Route::get('/store', function () {
        return redirect()->route('store.dashboard');
    });

    Route::get('/dashboard', function () {
        $user = Auth::user();

        switch ($user->role->slug) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'supplier':
                return redirect()->route('supplier.dashboard');
            case 'store-owner':
                return redirect()->route('store.dashboard');
            default:
                return redirect()->route('login');
        }
    })->name('dashboard');

    Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [AdminLoginController::class, 'login']);

    Route::get('store/login', [StoreLoginController::class, 'showLoginForm'])->name('store.login');
    Route::post('store/login', [StoreLoginController::class, 'login'])->name('store.checklogin');

    Route::get('supplier/login', [SupplierLoginController::class, 'showLoginForm'])->name('supplier.login');
    Route::post('supplier/login', [SupplierLoginController::class, 'login']);

    // Store Routes
    Route::prefix('store')->name('store.')->group(function () {
        Route::get('register', [StoreRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [StoreRegisterController::class, 'register']);
        Route::get('password/reset', [StoreForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('password/email', [StoreForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('password/reset/{token}', [StoreResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [StoreResetPasswordController::class, 'reset'])->name('password.update');
    });
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('supplier/logout', [SupplierLoginController::class, 'logout'])->name('supplier.logout');

    // Current authenticated user (for SPA header/profile)
    Route::get('web/me', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $user->load('role');
        }
        return response()->json($user);
    })->name('web.me');

    Route::get('/dashboard', function () {
        $user = Auth::user();

        switch ($user->role->slug) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'supplier':
                return redirect()->route('supplier.dashboard');
            case 'store-owner':
                return redirect()->route('store.dashboard');
            default:
                return redirect()->route('login');
        }
    })->name('dashboard');

    // Phone Verification Routes
    Route::get('verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::post('verify', [VerificationController::class, 'verify'])->withoutMiddleware(['signed'])->name('verification.verify');
    Route::post('verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::post('verify/send', [VerificationController::class, 'send'])->name('verification.send');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('representatives', RepresentativeController::class);
        Route::post('representatives/datatable', [RepresentativeController::class, 'datatable'])->name('representatives.datatable');
        Route::resource('static-pages', StaticPageController::class)->only(['index', 'edit', 'update']);
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Dashboard analytics JSON for SPA
        Route::get('analytics', [AdminDashboardController::class, 'analytics'])->name('dashboard.analytics');
        Route::resource('categories', CategoryController::class);
        Route::post('categories/datatable', [CategoryController::class, 'datatable'])->name('categories.datatable');
        Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');
        Route::resource('products', AdminProductController::class);
        Route::get('/products/import', [AdminProductController::class, 'showImportForm'])->name('products.import');
        Route::post('products/import', [AdminProductController::class, 'import'])->name('products.import.store');
        Route::get('products/export', [AdminProductController::class, 'export'])->name('products.export');
        Route::post('products/datatable', [AdminProductController::class, 'datatable'])->name('products.datatable');
        
        // Specific supplier routes (must come before resource route)
        Route::get('suppliers/list', [SupplierController::class, 'list'])->name('suppliers.list');
        Route::post('suppliers/datatable', [SupplierController::class, 'datatable'])->name('suppliers.datatable');
        Route::post('suppliers/{supplier}/import-products', [SupplierController::class, 'importProducts'])
            ->name('suppliers.import-products');
        Route::put('suppliers/{supplier}/status', [SupplierController::class, 'updateStatus'])
            ->name('suppliers.update-status');
        
        // Resource route (must come after specific routes)
        Route::resource('suppliers', SupplierController::class);
        Route::resource('stores', StoreController::class);
        Route::put('stores/{store}/status', [StoreController::class, 'updateStatus'])
            ->name('stores.status.update');
        Route::get('stores/{store}/import-products', [StoreController::class, 'showImportForm'])
            ->name('stores.import-products');
        Route::post('stores/{store}/import-products', [StoreController::class, 'importProducts'])
            ->name('stores.import-products.store');
        Route::post('stores/{store}/branches', [AdminStoreBranchController::class, 'store'])->name('admin.stores.branches.store');
        Route::post('stores/datatable', [StoreController::class, 'datatable'])->name('stores.datatable');
        Route::resource('store_users', StoreUserController::class)->parameters(['store_users' => 'user']);
        Route::post('store_users/datatable/{store}', [StoreUserController::class, 'datatable'])->name('store_users.datatable');
        Route::resource('orders', AdminOrderController::class);
        Route::post('orders/{order}/approve', [AdminOrderController::class, 'approve'])->name('orders.approve');
        Route::post('orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('orders.reject');
        Route::get('orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
        Route::get('orders/{order}/download-invoice', [AdminOrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
        Route::put('orders/payments/update-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.payments.update_status');
        Route::post('orders/datatable', [AdminOrderController::class, 'datatable'])->name('orders.datatable');
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);

        // Wallets
        Route::resource('wallets', WalletController::class);
        Route::post('wallets/{wallet}/toggle-status', [WalletController::class, 'toggleStatus'])
            ->name('wallets.toggle-status');
        Route::post('wallets/datatable', [WalletController::class, 'datatable'])->name('wallets.datatable');

        // Wallet Transactions
        Route::resource('wallet_transactions', WalletTransactionController::class);
        Route::post('wallet_transactions/datatable', [WalletTransactionController::class, 'datatable'])->name('wallet_transactions.datatable');
        Route::put('wallet_transactions/{wallet_transaction}/approve', [WalletTransactionController::class, 'approve'])->name('wallet_transactions.approve');
        Route::put('wallet_transactions/{wallet_transaction}/reject', [WalletTransactionController::class, 'reject'])->name('wallet_transactions.reject');
        Route::put('wallet_transactions/{wallet_transaction}/update_status', [WalletTransactionController::class, 'updateStatus'])
            ->name('wallet_transactions.update_status');

        // Cities
        Route::resource('cities', CityController::class);
        Route::post('cities/datatable', [CityController::class, 'datatable'])
            ->name('cities.datatable');

        // Areas
        Route::resource('areas', AreaController::class);
        Route::post('areas/datatable', [AreaController::class, 'datatable'])
            ->name('areas.datatable');

        // Store Branches
        Route::name('stores.branches.')->group(function () {
            Route::resource('stores/{store}/branches', AdminStoreBranchController::class)
                ->parameters(['branches' => 'store_branch'])
                ->names([
                    'create' => 'create',
                    'store' => 'store',
                    'show' => 'show',
                    'edit' => 'edit',
                    'update' => 'update',
                    'destroy' => 'destroy'
                ]);

            Route::get('stores/{store}/branches', [AdminStoreBranchController::class, 'index'])
                ->name('index');
        });

        Route::post('stores/{store}/branches/datatable', [AdminStoreBranchController::class, 'datatable'])->name('stores.branches.datatable');
        
        // Update balance limit for store branch
        Route::put('store-branches/{branch}/balance-limit', [AdminStoreBranchController::class, 'updateBalanceLimit'])->name('store-branches.balance-limit.update');
        
        // Toggle branch status
        Route::put('store-branches/{branch}/toggle-status', [AdminStoreBranchController::class, 'toggleStatus'])->name('store-branches.toggle-status');

        // Sub Orders
        Route::resource('sub_orders', SubOrderController::class);
        Route::get('orders/{order}/sub-orders/{subOrder}', [SubOrderController::class, 'show'])
            ->name('orders.sub-orders.show')
            ->where('subOrder', '[0-9]+');
        // Route::resource('store_branches',AdminStoreBranchController::class); // Disabled direct access to store_branches
        // Roles
        Route::resource('roles', RoleController::class);
        Route::post('roles/datatable', [RoleController::class, 'datatable'])->name('roles.datatable');

        // Promotions
        Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
        Route::post('promotions/datatable', [\App\Http\Controllers\Admin\PromotionController::class, 'datatable'])->name('promotions.datatable');
        Route::post('promotions/{promotion}/toggle-status', [\App\Http\Controllers\Admin\PromotionController::class, 'toggleStatus'])->name('promotions.toggle-status');

        // Branch Balance Requests (Admin)
        Route::get('balance-requests', [\App\Http\Controllers\Admin\BranchBalanceRequestController::class, 'index'])->name('balance-requests.index');
        Route::get('balance-requests/{id}', [\App\Http\Controllers\Admin\BranchBalanceRequestController::class, 'show'])->name('balance-requests.show');
        Route::post('balance-requests/{id}/approve', [\App\Http\Controllers\Admin\BranchBalanceRequestController::class, 'approve'])->name('balance-requests.approve');
        Route::post('balance-requests/{id}/reject', [\App\Http\Controllers\Admin\BranchBalanceRequestController::class, 'reject'])->name('balance-requests.reject');

        // Users
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::post('users/datatable', [\App\Http\Controllers\Admin\UserController::class, 'datatable'])->name('users.datatable');
        Route::put('users/{user}/status', [\App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.status.update');
        // JSON show for SPA edit
        Route::get('users/{user}/json', function (\App\Models\User $user) {
            return response()->json($user);
        })->name('users.json');
    });

    // Store Owner Routes
    Route::middleware(['auth:web'])->prefix('store')->name('store.')->group(function () {
        Route::get('wallet', [\App\Http\Controllers\Store\WalletController::class, 'index'])->name('wallet.index');
        Route::get('wallet/charge', [\App\Http\Controllers\Store\WalletController::class, 'charge'])->name('wallet.charge');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('orders/datatable', [OrderController::class, 'datatable'])->name('orders.datatable');
        Route::post('orders/{order}/verify', [OrderController::class, 'verify'])->name('orders.verify');
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('profile', [StoreProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [StoreProfileController::class, 'update'])->name('profile.update');
        // Store Branches
        Route::get('branches', [StoreBranchController::class, 'index'])->name('branches.index');
        Route::get('branches/create', [StoreBranchController::class, 'create'])->name('branches.create');
        Route::get('branches/{store_branch}', [StoreBranchController::class, 'show'])->name('store.branches.show');
        Route::get('branches/{store_branch}/edit', [StoreBranchController::class, 'edit'])->name('branches.edit');
        Route::delete('branches/{store_branch}', [StoreBranchController::class, 'destroy'])->name('branches.destroy');
        Route::post('branches', [StoreBranchController::class, 'store'])->name('branches.store');
        Route::put('branches/{store_branch}', [StoreBranchController::class, 'update'])->name('branches.update');
        Route::post('branches/datatable', [StoreBranchController::class, 'datatable'])->name('branches.datatable');

        // Branch Balance Requests
        Route::get('balance-requests', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'index'])->name('balance-requests.index');
        Route::get('balance-requests/{id}', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'show'])->name('balance-requests.show');
        Route::post('balance-requests', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'store'])->name('balance-requests.store');
        Route::put('balance-requests/{id}', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'update'])->name('balance-requests.update');
        Route::delete('balance-requests/{id}', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'destroy'])->name('balance-requests.destroy');

        // Wallet Transactions
        Route::get('wallet/transactions', [\App\Http\Controllers\Store\WalletTransactionController::class, 'index'])->name('wallet.transactions');
    });
});

// Supplier Routes
Route::middleware(['auth:supplier-web', 'supplier'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/', [SupplierDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Supplier\ProductController::class)->except(['show']);
    Route::get('products/import', [SupplierProductController::class, 'showImportForm'])->name('products.import');
    Route::post('products/import', [SupplierProductController::class, 'import'])->name('products.import.store');
    Route::post('products/datatable', [SupplierProductController::class, 'datatable'])->name('products.datatable');
    Route::post('products/{product}/toggle-status', [SupplierProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::resource('orders', SupplierOrderController::class)->only(['index', 'show']);
    Route::match(['get', 'post'], 'orders/datatable', [SupplierOrderController::class, 'datatable'])->name('orders.datatable');
    Route::get('orders/pending', [SupplierOrderController::class, 'pending'])->name('orders.pending');

    Route::post('orders/{subOrder}/approve', [SupplierOrderController::class, 'approve'])->name('orders.approve');
    Route::post('orders/{subOrder}/reject', [SupplierOrderController::class, 'reject'])->name('orders.reject');
    Route::post('orders/{subOrder}/deliver', [SupplierOrderController::class, 'deliver'])->name('orders.deliver');
    Route::resource('representatives', SupplierRepresentativeController::class);
    Route::post('representatives/datatable', [SupplierRepresentativeController::class, 'datatable'])->name('representatives.datatable');
    Route::post('representatives/{representative}/toggle-status', [SupplierRepresentativeController::class, 'toggleStatus'])
        ->name('representatives.toggle-status');
    Route::resource('sub-orders', SubOrderController::class)->only(['index', 'show']);
    Route::post('sub-orders/{subOrder}/update-status', [SubOrderController::class, 'updateStatus'])
        ->name('sub-orders.update-status');
    Route::post('sub-orders/{subOrder}/assign-representative', [SubOrderController::class, 'assignRepresentative'])
        ->name('sub-orders.assign-representative');
    Route::post('sub-orders/{subOrder}/accept', [SubOrderController::class, 'accept'])->name('sub-orders.accept');
    Route::post('sub-orders/{subOrder}/reject', [SubOrderController::class, 'reject'])->name('sub-orders.reject');
    Route::get('profile', [SupplierProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [SupplierProfileController::class, 'update'])->name('profile.update');
});

// Web Verification Routes
Route::prefix('web')->group(function () {
    Route::post('verify', [WebVerificationController::class, 'verify'])->name('web.verify');
    Route::post('resend-verification', [WebVerificationController::class, 'resend'])->name('web.resend-verification');
});

});
