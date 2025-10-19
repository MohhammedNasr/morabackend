<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the supplier dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $supplier = auth()->guard('supplier-web')->user();

        // Get total products count
        $totalProducts = Product::where('supplier_id', $supplier->id)->count();

        // Get orders statistics
        $totalOrders = SubOrder::where('supplier_id', $supplier->id)->count();
        $pendingOrders = SubOrder::where('supplier_id', $supplier->id)
            ->whereHas('order', function($q) {
                $q->where('status', 'pending');
            })
            ->count();

        // Get recent orders
        $recentOrders = SubOrder::where('supplier_id', $supplier->id)
            ->with(['order.store'])
            ->latest()
            ->take(5)
            ->get();

        // Get financial statistics
        $totalRevenue = SubOrder::where('supplier_id', $supplier->id)
            ->whereHas('order', function($q) {
                $q->where('status', 'completed');
            })
            ->sum('total_amount');

        $pendingPayments = SubOrder::where('supplier_id', $supplier->id)
            ->whereHas('order', function($q) {
                $q->where('status', 'completed')
                  ->where('payment_status', 'pending');
            })
            ->sum('total_amount');

        // Get representatives count
        $representativesCount = $supplier->representatives->count();

        // Get top products by order count
        $topProducts = Product::select([
                'products.*',
                DB::raw('COUNT(order_items.id) as total_ordered')
            ])
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('sub_orders', 'order_items.order_id', '=', 'sub_orders.order_id')
            ->where('products.supplier_id', $supplier->id)
            ->where('sub_orders.supplier_id', $supplier->id)
            ->groupBy('products.id')
            ->orderByDesc('total_ordered')
            ->with('category')
            ->take(5)
            ->get();

        // Get sub orders statistics
        $subOrders = SubOrder::where('supplier_id', $supplier->id)
            ->with(['order'])
            ->latest()
            ->take(5)
            ->get();

        // Get sales by category
        $salesByCategory = Product::select([
                'categories.name_'.app()->getLocale().' as name',
                DB::raw('SUM(order_items.unit_price * order_items.quantity) as total_sales')
            ])
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('sub_orders', 'order_items.order_id', '=', 'sub_orders.order_id')
            ->where('products.supplier_id', $supplier->id)
            ->where('sub_orders.supplier_id', $supplier->id)
            ->groupBy('categories.name_'.app()->getLocale())
            ->orderByDesc('total_sales')
            ->get();

        // Get payment methods statistics
        $paymentMethods = SubOrder::select([
                DB::raw('COALESCE(order_payments.payment_method, "Unknown") as payment_method'),
                DB::raw('COUNT(*) as count')
            ])
            ->join('orders', 'sub_orders.order_id', '=', 'orders.id')
            ->join('order_payments', 'orders.id', '=', 'order_payments.order_id')
            ->where('sub_orders.supplier_id', $supplier->id)
            ->groupBy('order_payments.payment_method')
            ->get();

        $data = compact(
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'recentOrders',
            'topProducts',
            'totalRevenue',
            'pendingPayments',
            'representativesCount',
            'subOrders',
            'salesByCategory',
            'paymentMethods'
        );

        // Debug output
        logger()->debug('Supplier Dashboard Data', [
            'salesByCategory' => $salesByCategory->toArray(),
            'paymentMethods' => $paymentMethods->toArray(),
            'chartDataExists' => [
                'salesByCategory' => $salesByCategory->isNotEmpty(),
                'paymentMethods' => $paymentMethods->isNotEmpty()
            ]
        ]);

        return view('supplier.dashboard', $data);
    }
}
