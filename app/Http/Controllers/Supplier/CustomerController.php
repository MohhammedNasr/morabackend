<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\SupplierTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Get list of business customers (stores that purchased from this supplier)
     */
    public function index(Request $request)
    {
        $supplier = $request->user();

        try {
            $perPage = $request->get('per_page', 20);
            $search = $request->get('search');

            // First get the aggregated data
            $aggregatedQuery = DB::table('supplier_transactions')
                ->select(
                    'store_id',
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(amount) as total_purchases'),
                    DB::raw('MAX(transaction_date) as last_purchase_date')
                )
                ->where('supplier_id', $supplier->id)
                ->groupBy('store_id');

            if ($search) {
                $aggregatedQuery->join('stores', 'supplier_transactions.store_id', '=', 'stores.id')
                    ->where('stores.name', 'like', "%{$search}%");
            }

            // Get paginated results
            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);
            $offset = ($page - 1) * $perPage;
            
            $total = DB::table(DB::raw("({$aggregatedQuery->toSql()}) as sub"))
                ->mergeBindings($aggregatedQuery)
                ->count();
            
            $results = $aggregatedQuery->offset($offset)->limit($perPage)->get();
            
            // Load store details
            $storeIds = $results->pluck('store_id')->toArray();
            $stores = Store::whereIn('id', $storeIds)
                ->select('id', 'name', 'phone', 'email', 'is_active')
                ->get()
                ->keyBy('id');

            // Transform results
            $data = $results->map(function($customer) use ($stores) {
                $store = $stores->get($customer->store_id);
                return [
                    'store_id' => $customer->store_id,
                    'business_name' => $store?->name,
                    'phone' => $store?->phone,
                    'email' => $store?->email,
                    'total_purchases' => (float) $customer->total_purchases,
                    'transaction_count' => $customer->transaction_count,
                    'last_purchase_date' => $customer->last_purchase_date,
                    'is_active' => $store?->is_active,
                ];
            });

            // Create manual pagination response
            $customers = new \Illuminate\Pagination\LengthAwarePaginator(
                $data,
                $total,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json($customers);
        } catch (\Exception $e) {
            \Log::error('Supplier customers error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch customers',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed purchase history for a specific customer
     */
    public function show(Request $request, $storeId)
    {
        $supplier = $request->user();

        try {
            $perPage = $request->get('per_page', 20);

            // Get store info
            $storeStats = SupplierTransaction::select(
                    'store_id',
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(amount) as total_purchases'),
                    DB::raw('MAX(transaction_date) as last_purchase_date'),
                    DB::raw('MIN(transaction_date) as first_purchase_date')
                )
                ->where('supplier_id', $supplier->id)
                ->where('store_id', $storeId)
                ->with('store:id,name,phone,email')
                ->groupBy('store_id')
                ->firstOrFail();

            // Get transactions
            $transactions = SupplierTransaction::where('supplier_id', $supplier->id)
                ->where('store_id', $storeId)
                ->orderBy('transaction_date', 'desc')
                ->paginate($perPage);

            $transactions->getCollection()->transform(function($txn) {
                return [
                    'id' => $txn->id,
                    'reference' => $txn->transaction_reference,
                    'amount' => (float) $txn->amount,
                    'description' => $txn->description,
                    'status' => $txn->status,
                    'transaction_date' => $txn->transaction_date->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'store' => [
                    'id' => $storeStats->store->id,
                    'name' => $storeStats->store->name,
                    'phone' => $storeStats->store->phone,
                    'email' => $storeStats->store->email,
                ],
                'stats' => [
                    'total_purchases' => (float) $storeStats->total_purchases,
                    'transaction_count' => $storeStats->transaction_count,
                    'first_purchase_date' => $storeStats->first_purchase_date,
                    'last_purchase_date' => $storeStats->last_purchase_date,
                ],
                'transactions' => $transactions,
            ]);
        } catch (\Exception $e) {
            \Log::error('Supplier customer show error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Customer not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
