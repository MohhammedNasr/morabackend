<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\SupplierTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Get paginated transactions for supplier
     */
    public function index(Request $request)
    {
        $supplier = $request->user();

        try {
            $perPage = $request->get('per_page', 20);
            $status = $request->get('status');
            $storeId = $request->get('store_id');
            $search = $request->get('search');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            $query = SupplierTransaction::where('supplier_id', $supplier->id)
                ->with(['store:id,name', 'settlement'])
                ->orderBy('transaction_date', 'desc');

            // Filters
            if ($status) {
                $query->where('status', $status);
            }

            if ($storeId) {
                $query->where('store_id', $storeId);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('transaction_reference', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($dateFrom) {
                $query->whereDate('transaction_date', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('transaction_date', '<=', $dateTo);
            }

            $transactions = $query->paginate($perPage);

            $transactions->getCollection()->transform(function($txn) {
                return [
                    'id' => $txn->id,
                    'reference' => $txn->transaction_reference,
                    'store_name' => $txn->store?->name,
                    'store_id' => $txn->store_id,
                    'amount' => (float) $txn->amount,
                    'description' => $txn->description,
                    'status' => $txn->status,
                    'transaction_date' => $txn->transaction_date->format('Y-m-d H:i:s'),
                    'settlement_date' => $txn->settlement_date?->format('Y-m-d'),
                    'settlement_reference' => $txn->settlement?->settlement_reference,
                    'items' => $txn->items,
                ];
            });

            return response()->json($transactions);
        } catch (\Exception $e) {
            \Log::error('Supplier transactions error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch transactions',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single transaction details
     */
    public function show(Request $request, $id)
    {
        $supplier = $request->user();

        try {
            $transaction = SupplierTransaction::where('supplier_id', $supplier->id)
                ->where('id', $id)
                ->with(['store', 'order', 'settlement'])
                ->firstOrFail();

            return response()->json([
                'id' => $transaction->id,
                'reference' => $transaction->transaction_reference,
                'store' => [
                    'id' => $transaction->store->id,
                    'name' => $transaction->store->name,
                    'phone' => $transaction->store->phone,
                ],
                'amount' => (float) $transaction->amount,
                'description' => $transaction->description,
                'items' => $transaction->items,
                'status' => $transaction->status,
                'transaction_date' => $transaction->transaction_date->format('Y-m-d H:i:s'),
                'settlement_date' => $transaction->settlement_date?->format('Y-m-d'),
                'settlement' => $transaction->settlement ? [
                    'id' => $transaction->settlement->id,
                    'reference' => $transaction->settlement->settlement_reference,
                    'status' => $transaction->settlement->status,
                ] : null,
                'order_id' => $transaction->order_id,
                'metadata' => $transaction->metadata,
            ]);
        } catch (\Exception $e) {
            \Log::error('Supplier transaction show error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Transaction not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
