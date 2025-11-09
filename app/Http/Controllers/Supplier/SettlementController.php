<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\SupplierSettlement;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    /**
     * Get all settlements for supplier
     */
    public function index(Request $request)
    {
        $supplier = $request->user();

        try {
            $perPage = $request->get('per_page', 20);
            $status = $request->get('status');

            $query = SupplierSettlement::where('supplier_id', $supplier->id)
                ->with('processor:id,name')
                ->orderBy('scheduled_payment_date', 'desc');

            if ($status) {
                $query->where('status', $status);
            }

            $settlements = $query->paginate($perPage);

            $settlements->getCollection()->transform(function($settlement) {
                return [
                    'id' => $settlement->id,
                    'reference' => $settlement->settlement_reference,
                    'period_start' => $settlement->period_start->format('Y-m-d'),
                    'period_end' => $settlement->period_end->format('Y-m-d'),
                    'total_amount' => (float) $settlement->total_amount,
                    'transaction_count' => $settlement->transaction_count,
                    'status' => $settlement->status,
                    'scheduled_payment_date' => $settlement->scheduled_payment_date->format('Y-m-d'),
                    'actual_payment_date' => $settlement->actual_payment_date?->format('Y-m-d H:i:s'),
                    'payment_reference' => $settlement->payment_reference,
                    'payment_method' => $settlement->payment_method,
                ];
            });

            return response()->json($settlements);
        } catch (\Exception $e) {
            \Log::error('Supplier settlements error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch settlements',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single settlement details
     */
    public function show(Request $request, $id)
    {
        $supplier = $request->user();

        try {
            $settlement = SupplierSettlement::where('supplier_id', $supplier->id)
                ->where('id', $id)
                ->with(['transactions.store', 'processor'])
                ->firstOrFail();

            $transactions = $settlement->transactions->map(function($txn) {
                return [
                    'id' => $txn->id,
                    'reference' => $txn->transaction_reference,
                    'store_name' => $txn->store?->name,
                    'amount' => (float) $txn->amount,
                    'transaction_date' => $txn->transaction_date->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'id' => $settlement->id,
                'reference' => $settlement->settlement_reference,
                'period_start' => $settlement->period_start->format('Y-m-d'),
                'period_end' => $settlement->period_end->format('Y-m-d'),
                'total_amount' => (float) $settlement->total_amount,
                'transaction_count' => $settlement->transaction_count,
                'status' => $settlement->status,
                'scheduled_payment_date' => $settlement->scheduled_payment_date->format('Y-m-d'),
                'actual_payment_date' => $settlement->actual_payment_date?->format('Y-m-d H:i:s'),
                'payment_reference' => $settlement->payment_reference,
                'payment_method' => $settlement->payment_method,
                'bank_account' => $settlement->bank_account,
                'payment_notes' => $settlement->payment_notes,
                'processed_by' => $settlement->processor?->name,
                'processed_at' => $settlement->processed_at?->format('Y-m-d H:i:s'),
                'transactions' => $transactions,
            ]);
        } catch (\Exception $e) {
            \Log::error('Supplier settlement show error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Settlement not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
