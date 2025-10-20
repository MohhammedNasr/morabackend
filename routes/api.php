<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PromotionController;
use App\Http\Controllers\API\RepresentativeController;
use App\Http\Controllers\API\StoreRegistrationController;
use App\Http\Controllers\API\StoreTypeController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\RejectionReasonController;
use App\Http\Controllers\API\SubOrderController;
use App\Http\Controllers\API\StaticPageController;
use App\Http\Controllers\API\SupplierProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\BankController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\OrderPaymentController;
use App\Http\Middleware\SetLocaleMiddleware;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(SetLocaleMiddleware::class)->group(function () {

    // Public routes
    Route::get('banks', [BankController::class, 'index']);
    // Payment Webhooks

    Route::any('/payment/webhook', [\App\Http\Controllers\API\OrderPaymentController::class, 'webhook'])
        ->name('payment.webhook');

    // Payment Webhooks (public)
    Route::prefix('webhooks')->group(function () {
        Route::post('/tap', [OrderPaymentController::class, 'webhook']);
    });

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('register', [StoreRegistrationController::class, 'register']);
        Route::post('login', [\App\Http\Controllers\API\Auth\LoginController::class, 'login']);
        Route::post('verify', [\App\Http\Controllers\API\Auth\VerificationController::class, 'verify']);
        Route::post('resend-verification', [\App\Http\Controllers\API\Auth\VerificationController::class, 'resend']);
        Route::post('forget-password', [\App\Http\Controllers\API\Auth\ForgetPasswordController::class, 'sendOtp']);
        Route::post('verify-reset-code', [\App\Http\Controllers\API\Auth\ForgetPasswordController::class, 'verifyOtp']);
        Route::post('reset-password', [\App\Http\Controllers\API\Auth\ForgetPasswordController::class, 'resetPassword']);

        // Supplier registration (public)
        Route::prefix('suppliers')->group(function () {
            Route::post('/register', [\App\Http\Controllers\API\Auth\SupplierRegisterController::class, 'register']);
            Route::post('/forget-password', [\App\Http\Controllers\API\Auth\SupplierForgetPasswordController::class, 'sendOtp']);
            Route::post('/verify-reset-code', [\App\Http\Controllers\API\Auth\SupplierForgetPasswordController::class, 'verifyOtp']);
            Route::post('/reset-password', [\App\Http\Controllers\API\Auth\SupplierForgetPasswordController::class, 'resetPassword']);
            // verify supplier
            Route::post('/verify', [SupplierController::class, 'verify']);
            Route::post('/resend-verification', [SupplierController::class, 'resendVerification']);
            Route::get('/verification-status', [SupplierController::class, 'checkVerificationStatus']);
            //
        });

        // Admin authentication (for dashboard)
        Route::prefix('admin')->group(function () {
            Route::post('/login', [\App\Http\Controllers\API\Auth\AdminAuthController::class, 'login']);
            
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('/me', [\App\Http\Controllers\API\Auth\AdminAuthController::class, 'me']);
                Route::post('/logout', [\App\Http\Controllers\API\Auth\AdminAuthController::class, 'logout']);
                
                // Admin Dashboard API Routes
                Route::get('/analytics', [\App\Http\Controllers\Admin\DashboardController::class, 'analytics']);
                
                // Users/Admins Management
                Route::post('/users/datatable', [\App\Http\Controllers\Admin\UserController::class, 'datatable']);
                Route::get('/users/{id}/json', [\App\Http\Controllers\Admin\UserController::class, 'show']);
                Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store']);
                Route::post('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update']);
                Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy']);
                
                // Balance Requests
                Route::get('/balance-requests', [\App\Http\Controllers\Admin\BranchBalanceRequestController::class, 'index']);
                Route::get('/balance-requests/{id}', [\App\Http\Controllers\Admin\BranchBalanceRequestController::class, 'show']);
                Route::post('/balance-requests/{id}/approve', [\App\Http\Controllers\Admin\BranchBalanceRequestController::class, 'approve']);
                Route::post('/balance-requests/{id}/reject', [\App\Http\Controllers\Admin\BranchBalanceRequestController::class, 'reject']);
                
                // Stores
                Route::post('/stores/datatable', [\App\Http\Controllers\Admin\StoreController::class, 'datatable']);
                Route::get('/stores/{id}/json', [\App\Http\Controllers\Admin\StoreController::class, 'show']);
                Route::post('/stores/{id}/toggle-status', [\App\Http\Controllers\Admin\StoreController::class, 'toggleStatus']);
                Route::post('/stores/{id}/verify', [\App\Http\Controllers\Admin\StoreController::class, 'verify']);
                
                // Suppliers
                Route::post('/suppliers/datatable', [\App\Http\Controllers\Admin\SupplierController::class, 'datatable']);
                Route::get('/suppliers', [\App\Http\Controllers\Admin\SupplierController::class, 'index']);
                Route::get('/suppliers/list', [\App\Http\Controllers\Admin\SupplierController::class, 'list']);
                Route::get('/suppliers/{supplier}/json', [\App\Http\Controllers\Admin\SupplierController::class, 'show']);
                Route::get('/suppliers/{supplier}/edit', [\App\Http\Controllers\Admin\SupplierController::class, 'edit']);
                Route::post('/suppliers', [\App\Http\Controllers\Admin\SupplierController::class, 'store']);
                Route::put('/suppliers/{supplier}', [\App\Http\Controllers\Admin\SupplierController::class, 'update']);
                Route::delete('/suppliers/{supplier}', [\App\Http\Controllers\Admin\SupplierController::class, 'destroy']);
                Route::post('/suppliers/{id}/toggle-status', [\App\Http\Controllers\Admin\SupplierController::class, 'toggleStatus']);
                Route::post('/suppliers/{id}/verify', [\App\Http\Controllers\Admin\SupplierController::class, 'verify']);
                
                // Representatives
                Route::post('/representatives/datatable', [\App\Http\Controllers\Admin\RepresentativeController::class, 'datatable']);
                Route::get('/representatives/{id}/json', [\App\Http\Controllers\Admin\RepresentativeController::class, 'show']);
                Route::post('/representatives', [\App\Http\Controllers\Admin\RepresentativeController::class, 'store']);
                Route::post('/representatives/{id}', [\App\Http\Controllers\Admin\RepresentativeController::class, 'update']);
                Route::delete('/representatives/{id}', [\App\Http\Controllers\Admin\RepresentativeController::class, 'destroy']);
                Route::post('/representatives/{id}/toggle-status', [\App\Http\Controllers\Admin\RepresentativeController::class, 'toggleStatus']);
            });
        });

        Route::middleware('auth:sanctum')->group(function () {
            // Regular auth routes
            Route::post('logout', [\App\Http\Controllers\API\Auth\LoginController::class, 'logout']);
            Route::get('user', [AuthController::class, 'me']);
            Route::delete('account', [\App\Http\Controllers\API\Auth\VerificationController::class, 'deleteAccount']);
        });
    });

    // Store types
    Route::get('store-types', [StoreTypeController::class, 'index']);

    // Cities
    Route::get('cities', [CityController::class, 'index']);

    // Stores
    Route::get('stores', [\App\Http\Controllers\API\StoreController::class, 'index']);
    Route::get('stores/{store}/branches', [\App\Http\Controllers\API\StoreController::class, 'branches']);

    // Settings
    Route::get('settings', [\App\Http\Controllers\API\SettingsController::class, 'index']);
    Route::put('settings', [\App\Http\Controllers\API\SettingsController::class, 'update']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Categories
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/products', [CategoryController::class, 'products']);
            Route::get('/{category}/suppliers', [CategoryController::class, 'suppliers']);
        });

        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::post('/', [OrderController::class, 'store']);
            Route::get('/{order}', [OrderController::class, 'show']);
            Route::post('/{order}/verify', [OrderController::class, 'verify']);
            Route::post('/{order}/resend-verification', [OrderController::class, 'resendVerification']);
            Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
            Route::post('/{order}/approve', [OrderController::class, 'approve']);
            Route::post('/{order}/process', [OrderController::class, 'process']);
            Route::post('/{order}/complete', [OrderController::class, 'complete']);
            Route::post('/{order}/modify', [OrderController::class, 'modify']);
        });

        // Sub-orders
        Route::prefix('sub-orders')->group(function () {
            Route::post('/{subOrder}/change-status', [SubOrderController::class, 'changeStatus']);
            Route::put('/{subOrder}/modify', [SubOrderController::class, 'modifySubOrder']);
        });

        // Suppliers
        Route::prefix('suppliers')->group(function () {
            Route::get('/home', [HomeController::class, 'supplierStatistics']);
            Route::get('/list-orders', [SupplierController::class, 'listSuborders']);
            Route::put('/profile', [SupplierProfileController::class, 'update']);
            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/', [SupplierController::class, 'store']);
            Route::post('/by-categories', [SupplierController::class, 'byCategories']);
            Route::get('/{supplier}', [SupplierController::class, 'show']);
            Route::put('/{supplier}', [SupplierController::class, 'update']);
            Route::get('/{supplier}/products', [SupplierController::class, 'products']);
            Route::post('/{supplier}/products', [SupplierController::class, 'attachProduct']);
            Route::put('/{supplier}/products/{product}', [SupplierController::class, 'updateProduct']);
            Route::delete('/{supplier}/products/{product}', [SupplierController::class, 'detachProduct']);
            Route::put('/{supplier}/sub-orders/{subOrder}/assign-representative/{representative}', [SupplierController::class, 'assignRepresentative']);
            Route::get('/{supplier}/representatives', [SupplierController::class, 'listRepresentatives']);
            Route::post('/{supplier}/representatives', [SupplierController::class, 'registerRepresentative']);
            Route::get('/{supplier}/representatives/{representative}', [SupplierController::class, 'showRepresentative']);
            Route::put('/{supplier}/representatives/{representative}', [SupplierController::class, 'updateRepresentative'])->scopeBindings();
            Route::delete('/{supplier}/representatives/{representative}', [SupplierController::class, 'destroyRepresentative']);
        });

        // Representatives
        Route::middleware('check.user.role:representative,supplier')->group(function () {
            Route::prefix('representative')->group(function () {
                Route::get('/home', [HomeController::class, 'representativeHome']);
                Route::get('/list-orders', [SubOrderController::class, 'listAssignedOrders']);
                Route::get('/sub-orders/{subOrder}', [SubOrderController::class, 'show']);
                Route::put('/profile', [RepresentativeController::class, 'updateProfile']);
            });
        });

        // Branches
        Route::apiResource('branches', BranchController::class);
        // Rejection reasons
        Route::get('rejection-reasons', [RejectionReasonController::class, 'index']);

        // Store owner routes
        Route::middleware('check.user.role:store-owner')->group(function () {
            // Wallet
            Route::prefix('wallet')->group(function () {
                //old one chargeWallet
                Route::post('/charge', [\App\Http\Controllers\API\WalletController::class, 'charge']);
                Route::post('/wallet/charge', [WalletController::class, 'charge']);
                Route::get('/balance', [\App\Http\Controllers\API\WalletController::class, 'getStoreBalance']);
                Route::get('/transactions', [\App\Http\Controllers\API\WalletController::class, 'getWalletTransactions']);
            });

            // Order Payments
            Route::prefix('order-payments')->group(function () {
                Route::post('/{orderPayment}/pay', [\App\Http\Controllers\API\OrderPaymentController::class, 'pay']);
            });

            // Home
            Route::get('/home', [HomeController::class, 'index']);

            // Store Profile
            Route::prefix('store-profile')->group(function () {
                Route::put('/', [\App\Http\Controllers\API\StoreProfileController::class, 'update']);
            });

            // Store Branches
            Route::prefix('store')->group(function () {
                Route::get('/branches', [BranchController::class, 'index']);
                Route::get('/branches/{branch}', [BranchController::class, 'show']);
                Route::post('/branches', [BranchController::class, 'store']);
                Route::put('/branches/{branch}', [BranchController::class, 'update']);
                Route::delete('/branches/{branch}', [BranchController::class, 'destroy']);
                
                // Branch Balance Requests
                Route::get('/balance-requests', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'index']);
                Route::get('/balance-requests/{id}', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'show']);
                Route::post('/balance-requests', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'store']);
                Route::put('/balance-requests/{id}', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'update']);
                Route::delete('/balance-requests/{id}', [\App\Http\Controllers\Store\BranchBalanceRequestController::class, 'destroy']);
            });
        });

        // Promotions
        Route::prefix('promotions')->group(function () {
            Route::post('/validate', [PromotionController::class, 'validatePromotion']);
            Route::post('/apply', [PromotionController::class, 'applyPromotion']);
        });

        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/', [\App\Http\Controllers\API\NotificationController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\API\NotificationController::class, 'show']);
            Route::post('/mark-as-read', [\App\Http\Controllers\API\NotificationController::class, 'markAsRead']);
            Route::delete('/{id}', [\App\Http\Controllers\API\NotificationController::class, 'destroy']);
            Route::post('/register-device-token', [\App\Http\Controllers\API\NotificationController::class, 'registerDeviceToken']);
            Route::post('/send-push-notification', [\App\Http\Controllers\API\NotificationController::class, 'sendPushNotification']);
        });

        // Static Pages
        Route::get('terms-and-conditions', [StaticPageController::class, 'termsAndConditions']);
        Route::get('static-pages/{id}', [StaticPageController::class, 'show']);
        Route::get('static-pages/terms-and-conditions', [StaticPageController::class, 'termsAndConditions']);

        // Notification Testing
        Route::post('test-notification', [\App\Http\Controllers\API\NotificationTestController::class, 'sendTestNotification']);
    });
});
