<?php

namespace App\Services\Home;

use App\Models\Order;
use App\Models\Banner;
use App\Http\Resources\BannerResource;
use App\Http\Resources\OrderResource;
use App\Models\Product;
use App\Models\SubOrder;
use App\Services\BaseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeService extends BaseService
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getHomeData()
    {
        $data = [
            'pending_orders_count' => $this->getPendingOrdersCount(),
            'inprogress_orders_count' => $this->getInProgressOrdersCount(),
            'ads' => $this->getActiveBanners(),
            'orders' => $this->getRecentOrders() ?? OrderResource::collection($this->getRecentOrders())
        ];

        //  $data['confusion'] = config('app.confusion');
        return $this->successResponse(
            $data,
            __('api.home_data_retrieved')
        );
    }

    protected function getPendingOrdersCount(): int
    {
        $user = $this->request->user();
        //return Cache::remember('pending_orders_count_' . $user->id, 60, function () use ($user) {
        return Order::when($user->isStoreOwner(), function ($query) use ($user) {
            $query->whereHas('store', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        })
            ->whereIn('status', [Order::STATUS_PENDING])
            ->count();
        //  });
    }

    protected function getInProgressOrdersCount(): int
    {
        $user = $this->request->user();
        //  return Cache::remember('inprogress_orders_count_' . $user->id, 60, function () use ($user) {
        return Order::when($user->isStoreOwner(), function ($query) use ($user) {
            $query->whereHas('store', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        })
            ->where('user_id', $user->id)
            ->whereIn('status', [Order::STATUS_UNDER_PROCESSING, Order::STATUS_VERIFIED])
            ->count();
        //   });
    }

    protected function getActiveBanners(): array
    {
        return Cache::remember('active_banners', 60, function () {
            return BannerResource::collection(
                Banner::active()->get()
            )->toArray(request());
        });
    }

    protected function getRecentOrders(): array
    {
        $user = $this->request->user();
        // return Cache::remember('recent_orders_' . $user->id, 60, function () use ($user) {
        return OrderResource::collection(
            Order::with(['items', 'customer'])
                ->when($user->isStoreOwner(), function ($query) use ($user) {
                    $query->whereHas('store', function ($q) use ($user) {
                        $q->where('owner_id', $user->id);
                    });
                })
                ->whereIn('status', ['canceled', 'completed'])
                ->latest()
                ->limit(5)
                ->get()
        )->toArray(request());
        //  });
    }


    public function getRepresentativeHomeData($representativeId)
    {
        $approvedCount = SubOrder::whereIn('status', [SubOrder::STATUS_ACCEPTED_BY_REP, SubOrder::STATUS_MODIFIED_BY_REP])
            ->where('representative_id', $representativeId)
            ->count();

        $newCount = SubOrder::whereIn('status', [
            SubOrder::STATUS_ASSIGNED_TO_REP
            // ,'acceptedByRep','rejectedByRep','modifiedByRep'
        ])
            ->where('representative_id', $representativeId)
            ->count();

        $outForDeliveryCount = SubOrder::where('status', SubOrder::STATUS_OUT_FOR_DELIVERY)
            ->where('representative_id', $representativeId)
            ->count();

        $data = [
            'approved' => $approvedCount,
            'new' => $newCount,
            'out_for_delivery' => $outForDeliveryCount,
        ];

        //  $data['confusion'] = config('app.confusion');
        return $this->successResponse(
            $data,
            __('api.representative_home_data_retrieved')
        );
    }

    public function getSupplierStatistics()
    {

        $supplier =  auth('supplier')->user();

        // Get total products count
        $totalProducts = Product::where('supplier_id', $supplier->id)->count();

        // Get orders statistics
        $totalOrders = SubOrder::where('supplier_id', $supplier->id)->count();
        $pendingOrders = SubOrder::where('supplier_id', $supplier->id)
            ->whereHas('order', function ($q) {
                $q->where('status', SubOrder::STATUS_PENDING);
            })
            ->count();

        // Get recent orders
        $recentOrders = SubOrder::where('supplier_id', $supplier->id)
            ->with(['order.store'])
            // ->latest()
            // ->take(5)
            ->count();

        // Get financial statistics
        $totalRevenue = SubOrder::where('supplier_id', $supplier->id)
            ->where('status', SubOrder::STATUS_DELIVERED)
            ->sum('total_amount');

            $monthlyRevenue = SubOrder::select([
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('DATE_FORMAT(created_at, "%M") as month'),
                DB::raw('MONTH(created_at) as month_num') // Add this for proper ordering
            ])
            ->where('supplier_id', $supplier->id)
            ->where('status', SubOrder::STATUS_DELIVERED)
            ->groupBy('month', 'month_num') // Group by both month name and number
            ->orderBy('month_num') // Order by the numeric month value
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'revenue' => $item->revenue
                ];
            })
            ->toArray();
       


        $bestSold = Product::select([
            'products.id',
            DB::raw("products.name_" . app()->getLocale() . " as name"),
            DB::raw('products.sku'),
            DB::raw('COUNT(order_items.id) as total_ordered'),
            DB::raw('DATE_FORMAT(sub_orders.created_at, "%M") as month')
        ])
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('sub_orders', 'order_items.order_id', '=', 'sub_orders.order_id')
            ->where('products.supplier_id', $supplier->id)
            ->where('sub_orders.supplier_id', $supplier->id)
            ->groupBy('products.id', 'month')
            ->orderBy('month')
            ->orderByDesc('total_ordered')
            ->get()
            ->groupBy('month')
            ->map(function ($items, $month) {
                return [
                    'month' => $month,
                    'product' => $items->first() // Best product of the month
                ];
            })
            ->values() // Converts to indexed array (removes month keys)
            ->toArray(); // Optional: Converts to pure PHP array

        $pendingPayments = SubOrder::where('supplier_id', $supplier->id)
            ->whereHas('order', function ($q) {
                $q->where('status', order::STATUS_COMPLETED)
                    ->where('payment_status', 'pending');
            })
            ->sum('total_amount');

        // Get representatives count
        $representativesCount = $supplier->representatives->count();

        // Get top products by order count
        $soldProducts = Product::select([
            'products.*',
            DB::raw('COUNT(order_items.id) as total_ordered')
        ])
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('sub_orders', 'order_items.order_id', '=', 'sub_orders.order_id')
            ->where('products.supplier_id', $supplier->id)
            ->where('sub_orders.supplier_id', $supplier->id)
            ->groupBy('products.id')
            ->count();

        // Get sub orders statistics
        // $subOrders = SubOrder::where('supplier_id', $supplier->id)
        //     ->with(['order'])
        //     ->latest()
        //     ->take(5)
        //     ->get();

        $data = [
            'monthlyBestSold' => $bestSold,
            'monthlyReveneu' => $monthlyRevenue,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'recentOrders' => $recentOrders,
            'soldProducts' => $soldProducts,
            'totalRevenue' => $totalRevenue,
            'pendingPayments' => $pendingPayments,
            'representativesCount' => $representativesCount,
            // 'subOrders' => $subOrders
        ];
        return $this->successResponse(
            $data,
            __('api.representative_home_data_retrieved')
        );
    }
}
