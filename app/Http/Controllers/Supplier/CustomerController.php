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

            // Get aggregated data with store info
            $query = DB::table('supplier_transactions')
                ->select(
                    'supplier_transactions.store_id',
                    'stores.name as business_name',
                    'stores.phone',
                    'stores.email',
                    'stores.is_active',
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(supplier_transactions.amount) as total_purchases'),
                    DB::raw('MAX(supplier_transactions.transaction_date) as last_purchase_date')
                )
                ->join('stores', 'supplier_transactions.store_id', '=', 'stores.id')
                ->where('supplier_transactions.supplier_id', $supplier->id)
                ->groupBy('supplier_transactions.store_id', 'stores.name', 'stores.phone', 'stores.email', 'stores.is_active');

            if ($search) {
                $query->where('stores.name', 'like', "%{$search}%");
            }

            $results = $query->paginate($perPage);
            
            // Transform to match expected format
            $results->getCollection()->transform(function($customer) {
                return [
                    'store_id' => $customer->store_id,
                    'business_name' => $customer->business_name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'total_purchases' => (float) $customer->total_purchases,
                    'transaction_count' => $customer->transaction_count,
                    'last_purchase_date' => $customer->last_purchase_date,
                    'is_active' => $customer->is_active,
                ];
            });

            return response()->json($results);
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
