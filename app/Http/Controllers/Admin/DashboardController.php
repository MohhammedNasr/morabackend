<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Store statistics
        $totalStores = Store::count();
        $verifiedStores = Store::where('is_verified', true)->count();
        $pendingStores = Store::where('is_verified', false)->count();

        // Supplier statistics
        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('is_active', true)->count();
        $inactiveSuppliers = Supplier::where('is_active', false)->count();

        // Order statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();
        $completedOrders = Order::where('status', Order::STATUS_COMPLETED)->count();
        $recentOrders = Order::latest()
            ->take(5)
            ->get();

        // Product statistics
        $totalProducts = \App\Models\Product::count();
        $activeProducts = \App\Models\Product::where('status', true)->count();

        // Order trends - last 30 days
        $orderTrendData = [];
        $orderTrendLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $orderTrendLabels[] = $date;
            $orderTrendData[] = Order::whereDate('created_at', $date)->count();
        }

        // Recent stores
        $recentStores = Store::latest()
            ->take(5)
            ->get();

        // Store performance - top 5 stores by revenue
        $storePerformance = DB::table('orders')
            ->select('store_id', DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('store_id')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $storePerformanceLabels = [];
        $storePerformanceData = [];
        foreach ($storePerformance as $performance) {
            $store = Store::find($performance->store_id);
            $storePerformanceLabels[] = $store->name;
            $storePerformanceData[] = $performance->revenue;
        }

        return view('admin.dashboard', compact(
            'totalStores',
            'verifiedStores',
            'pendingStores',
            'totalSuppliers',
            'activeSuppliers',
            'inactiveSuppliers',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'recentOrders',
            'totalProducts',
            'activeProducts',
            'orderTrendLabels',
            'orderTrendData',
            'storePerformanceLabels',
            'storePerformanceData',
            'recentStores'
        ));
    }

    /**
     * Return dashboard analytics as JSON for SPA frontend.
     */
    public function analytics(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

        $orderQuery = Order::query();
        if ($fromDate) { $orderQuery->where('created_at', '>=', $fromDate); }
        if ($toDate) { $orderQuery->where('created_at', '<=', $toDate); }

        $totalOrders = (clone $orderQuery)->count();
        $pendingOrders = (clone $orderQuery)->where('status', Order::STATUS_PENDING)->count();
        $completedOrders = (clone $orderQuery)->where('status', Order::STATUS_COMPLETED)->count();
        $totalRevenue = (clone $orderQuery)->sum('total_amount');

        // Products
        $totalProducts = \App\Models\Product::count();
        $activeProducts = \App\Models\Product::where('status', true)->count();

        // Stores & Suppliers (totals)
        $totalStores = Store::count();
        $verifiedStores = Store::where('is_verified', true)->count();
        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('is_active', true)->count();

        // Trend (by day)
        $labels = [];
        $data = [];
        if ($fromDate && $toDate) {
            $period = new \DatePeriod($fromDate, new \DateInterval('P1D'), $toDate->copy()->addDay());
            foreach ($period as $date) {
                $d = Carbon::instance($date)->format('Y-m-d');
                $labels[] = $d;
                $data[] = Order::whereDate('created_at', $d)->count();
            }
        } else {
            for ($i = 29; $i >= 0; $i--) {
                $d = now()->subDays($i)->format('Y-m-d');
                $labels[] = $d;
                $data[] = Order::whereDate('created_at', $d)->count();
            }
        }

        // Top stores by revenue in range
        $topStoresQuery = DB::table('orders')
            ->select('store_id', DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('store_id')
            ->orderByDesc('revenue')
            ->take(5);
        if ($fromDate) { $topStoresQuery->where('created_at', '>=', $fromDate); }
        if ($toDate) { $topStoresQuery->where('created_at', '<=', $toDate); }
        $topStores = $topStoresQuery->get();
        $topStoresFormatted = [];
        foreach ($topStores as $row) {
            $store = Store::find($row->store_id);
            if ($store) {
                $topStoresFormatted[] = [
                    'store_id' => $store->id,
                    'name' => $store->name,
                    'revenue' => (float) $row->revenue,
                ];
            }
        }

        // Recent orders (latest 5 in range)
        $recentOrdersQuery = Order::latest();
        if ($fromDate) { $recentOrdersQuery->where('created_at', '>=', $fromDate); }
        if ($toDate) { $recentOrdersQuery->where('created_at', '<=', $toDate); }
        $recentOrders = $recentOrdersQuery->take(5)->get(['id','store_id','status','total_amount','created_at']);

        return response()->json([
            'totals' => [
                'orders' => $totalOrders,
                'pendingOrders' => $pendingOrders,
                'completedOrders' => $completedOrders,
                'revenue' => (float) $totalRevenue,
                'stores' => $totalStores,
                'verifiedStores' => $verifiedStores,
                'suppliers' => $totalSuppliers,
                'activeSuppliers' => $activeSuppliers,
                'products' => $totalProducts,
                'activeProducts' => $activeProducts,
            ],
            'ordersTrend' => [
                'labels' => $labels,
                'data' => $data,
            ],
            'topStores' => $topStoresFormatted,
            'recentOrders' => $recentOrders,
        ]);
    }
}
