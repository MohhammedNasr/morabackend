<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the store dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $user = auth()->guard('web')->user();
       // dd($user);

        $store = Store::where('owner_id', $user->id)->first();


        // Get total orders count
        $totalOrders = Order::where('store_id', $store->id)->count();
        $pendingOrders = Order::where('store_id', $store->id)
            ->where('status', 'pending')
            ->count();
        $completedOrders = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->count();

        // Get recent orders with their suborders and suppliers
        $recentOrders = Order::where('store_id', $store->id)
            ->with(['subOrders' => function($query) {
                $query->with('supplier');
            }])
            ->latest()
            ->take(5)
            ->get();

        // Get financial statistics
        $totalRevenue = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        $pendingPayments = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->where('payment_status', 'pending')
            ->sum('total_amount');

        // Get top suppliers by order count (without authentication)
        $topSuppliers = DB::table('sub_orders')
            ->select('suppliers.name', DB::raw('COUNT(sub_orders.id) as total_orders'))
            ->join('suppliers', 'sub_orders.supplier_id', '=', 'suppliers.id')
            ->join('orders', 'sub_orders.order_id', '=', 'orders.id')
            ->where('orders.store_id', $store->id)
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('total_orders')
            ->take(5)
            ->get();

        // Get order trends - last 30 days
        $orderTrendData = [];
        $orderTrendLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $orderTrendLabels[] = $date;
            $orderTrendData[] = Order::where('store_id', $store->id)
                ->whereDate('created_at', $date)
                ->count();
        }

        // Get sales by category with localized names
        $salesByCategory = DB::table('order_items')
            ->select(
                app()->getLocale() == 'ar' ? 'categories.name_ar as name' : 'categories.name_en as name',
                DB::raw('SUM(order_items.quantity * products.price) as total_sales')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.store_id', $store->id)
            ->where('orders.status', 'completed')
            ->groupBy('categories.id', app()->getLocale() == 'ar' ? 'categories.name_ar' : 'categories.name_en')
            ->orderByDesc('total_sales')
            ->get();

        // Get payment method distribution from order payments
        $paymentMethods = DB::table('order_payments')
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->join('orders', 'order_payments.order_id', '=', 'orders.id')
            ->where('orders.store_id', $store->id)
            ->groupBy('payment_method')
            ->get();

        // Get order status distribution
        $orderStatuses = DB::table('orders')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->where('store_id', $store->id)
            ->groupBy('status')
            ->get();

        return view('store.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'recentOrders',
            'totalRevenue',
            'pendingPayments',
            'topSuppliers',
            'orderTrendLabels',
            'orderTrendData',
            'salesByCategory',
            'paymentMethods',
            'orderStatuses'
        ));
    }
}
