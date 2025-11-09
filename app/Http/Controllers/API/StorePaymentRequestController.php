<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SupplierPaymentRequest;
use App\Models\SupplierTransaction;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StorePaymentRequestController extends Controller
{
    /**
     * Verify a payment request by code
     * Store/Business can scan QR or enter code to see payment details
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $code = $request->code;
            
            $paymentRequest = SupplierPaymentRequest::where('request_code', $code)
                ->with('supplier:id,name,business_name')
                ->first();

            if (!$paymentRequest) {
                return response()->json([
                    'error' => 'Invalid payment code'
                ], 404);
            }

            // Check if expired
            if ($paymentRequest->isExpired()) {
                $paymentRequest->markAsExpired();
                return response()->json([
                    'error' => 'This payment request has expired'
                ], 400);
            }

            // Check if already paid
            if ($paymentRequest->status === SupplierPaymentRequest::STATUS_COMPLETED) {
                return response()->json([
                    'error' => 'This payment request has already been paid'
                ], 400);
            }

            // Check if cancelled
            if ($paymentRequest->status === SupplierPaymentRequest::STATUS_CANCELLED) {
                return response()->json([
                    'error' => 'This payment request has been cancelled'
                ], 400);
            }

            return response()->json([
                'payment_request' => [
                    'id' => $paymentRequest->id,
                    'code' => $paymentRequest->request_code,
                    'amount' => (float) $paymentRequest->amount,
                    'description' => $paymentRequest->description,
                    'supplier' => [
                        'id' => $paymentRequest->supplier->id,
                        'name' => $paymentRequest->supplier->business_name ?? $paymentRequest->supplier->name,
                    ],
                    'expires_at' => $paymentRequest->expires_at->toIso8601String(),
                    'can_be_paid' => $paymentRequest->canBePaid(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment request verification error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to verify payment request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process payment for a payment request
     * Store pays the supplier using their Mora balance
     */
    public function pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'store_id' => 'required|exists:stores,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $code = $request->code;
            $storeId = $request->store_id;

            // Get the store with branches
            $store = Store::with('branches')->findOrFail($storeId);

            // Get payment request
            $paymentRequest = SupplierPaymentRequest::where('request_code', $code)
                ->with('supplier')
                ->lockForUpdate()
                ->first();

            if (!$paymentRequest) {
                DB::rollBack();
                return response()->json(['error' => 'Invalid payment code'], 404);
            }

            // Validate payment request status
            if (!$paymentRequest->canBePaid()) {
                DB::rollBack();
                return response()->json([
                    'error' => 'This payment request cannot be paid (expired, cancelled, or already paid)'
                ], 400);
            }

            // Calculate total available balance from active branches
            $totalAvailableBalance = $store->branches()
                ->where('is_active', true)
                ->sum('balance_limit');

            if ($totalAvailableBalance < $paymentRequest->amount) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Insufficient balance',
                    'required' => (float) $paymentRequest->amount,
                    'available' => (float) $totalAvailableBalance,
                ], 400);
            }

            // Deduct from branch balance limits (proportionally from active branches)
            $activeBranches = $store->branches()->where('is_active', true)->where('balance_limit', '>', 0)->get();
            $remainingAmount = $paymentRequest->amount;
            
            foreach ($activeBranches as $branch) {
                if ($remainingAmount <= 0) break;
                
                $deductAmount = min($branch->balance_limit, $remainingAmount);
                $branch->decrement('balance_limit', $deductAmount);
                $remainingAmount -= $deductAmount;
            }

            // Create supplier transaction
            $supplierTransaction = SupplierTransaction::create([
                'supplier_id' => $paymentRequest->supplier_id,
                'store_id' => $store->id,
                'transaction_reference' => SupplierTransaction::generateReference(),
                'amount' => $paymentRequest->amount,
                'description' => $paymentRequest->description ?? "Payment via QR code: {$code}",
                'status' => SupplierTransaction::STATUS_PENDING_SETTLEMENT,
                'transaction_date' => now(),
            ]);

            // Mark payment request as completed
            $paymentRequest->markAsCompleted($store, $supplierTransaction);

            DB::commit();

            // Calculate new total balance after payment
            $newTotalBalance = $store->branches()
                ->where('is_active', true)
                ->sum('balance_limit');

            return response()->json([
                'message' => 'Payment successful',
                'payment' => [
                    'transaction_reference' => $supplierTransaction->transaction_reference,
                    'amount' => (float) $paymentRequest->amount,
                    'supplier_name' => $paymentRequest->supplier->business_name ?? $paymentRequest->supplier->name,
                    'paid_at' => $paymentRequest->paid_at->toIso8601String(),
                    'remaining_balance' => (float) $newTotalBalance,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment processing error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to process payment',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment history for authenticated store
     */
    public function history(Request $request)
    {
        try {
            // Get authenticated user's store
            $user = auth()->user();
            $store = $user->store;

            if (!$store) {
                return response()->json([
                    'error' => 'Store not found'
                ], 404);
            }

            // Get supplier transactions for this store
            $transactions = SupplierTransaction::with('supplier:id,name,business_name')
                ->where('store_id', $store->id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($txn) {
                    return [
                        'id' => $txn->id,
                        'supplier_name' => $txn->supplier->business_name ?? $txn->supplier->name,
                        'amount' => (float) $txn->amount,
                        'description' => $txn->description,
                        'status' => $txn->status,
                        'transaction_reference' => $txn->transaction_reference,
                        'paid_at' => $txn->created_at->toIso8601String(),
                        'created_at' => $txn->created_at->toIso8601String(),
                    ];
                });

            return response()->json([
                'transactions' => $transactions,
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment history error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch payment history',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
