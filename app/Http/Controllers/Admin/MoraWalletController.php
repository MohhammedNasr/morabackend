<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MoraWallet;
use App\Models\MoraWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoraWalletController extends Controller
{
    /**
     * Get Mora wallet balance and statistics
     */
    public function index()
    {
        try {
            $wallet = MoraWallet::getInstance();
            
            $statistics = [
                'balance' => (float) $wallet->balance,
                'total_credited' => (float) $wallet->total_credited,
                'total_debited' => (float) $wallet->total_debited,
                'currency' => $wallet->currency,
                'total_transactions' => $wallet->transactions()->count(),
                'credits_count' => $wallet->transactions()->credits()->count(),
                'debits_count' => $wallet->transactions()->debits()->count(),
                'last_transaction_at' => $wallet->transactions()->latest()->first()?->created_at,
            ];

            return response()->json($statistics);
        } catch (\Exception $e) {
            \Log::error('Mora wallet index error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch wallet data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get paginated transaction history
     */
    public function transactions(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 20);
            $type = $request->get('type'); // credit, debit
            $transactionType = $request->get('transaction_type'); // balance_approval, loan_repayment, manual_adjustment
            $search = $request->get('search');

            $query = MoraWalletTransaction::with(['balanceRequest.storeBranch', 'initiator'])
                ->orderBy('created_at', 'desc');

            if ($type) {
                $query->where('type', $type);
            }

            if ($transactionType) {
                $query->where('transaction_type', $transactionType);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('reference_number', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $transactions = $query->paginate($perPage);

            $transactions->getCollection()->transform(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'reference_number' => $transaction->reference_number,
                    'type' => $transaction->type,
                    'transaction_type' => $transaction->transaction_type,
                    'amount' => (float) $transaction->amount,
                    'balance_before' => (float) $transaction->balance_before,
                    'balance_after' => (float) $transaction->balance_after,
                    'description' => $transaction->description,
                    'balance_request' => $transaction->balanceRequest ? [
                        'id' => $transaction->balanceRequest->id,
                        'request_number' => $transaction->balanceRequest->request_number,
                        'branch_name' => $transaction->balanceRequest->storeBranch?->name,
                    ] : null,
                    'initiated_by' => $transaction->initiator?->name,
                    'metadata' => $transaction->metadata,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json($transactions);
        } catch (\Exception $e) {
            \Log::error('Mora wallet transactions error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch transactions',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manually credit money to Mora wallet
     */
    public function credit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $wallet = MoraWallet::getInstance();
            
            $transaction = $wallet->credit(
                amount: $request->amount,
                transactionType: MoraWalletTransaction::TRANSACTION_TYPE_MANUAL_ADJUSTMENT,
                description: $request->description,
                initiatedBy: auth()->user(),
                metadata: [
                    'action' => 'manual_credit',
                    'admin_id' => auth()->id(),
                ]
            );

            return response()->json([
                'message' => 'Money credited successfully',
                'transaction' => [
                    'id' => $transaction->id,
                    'reference_number' => $transaction->reference_number,
                    'amount' => (float) $transaction->amount,
                    'balance_after' => (float) $transaction->balance_after,
                ],
                'wallet_balance' => (float) $wallet->fresh()->balance,
            ]);
        } catch (\Exception $e) {
            \Log::error('Mora wallet credit error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to credit wallet',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manually debit money from Mora wallet
     */
    public function debit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $wallet = MoraWallet::getInstance();

            if (!$wallet->hasEnoughBalance($request->amount)) {
                return response()->json([
                    'error' => 'Insufficient balance in Mora wallet',
                ], 400);
            }
            
            $transaction = $wallet->debit(
                amount: $request->amount,
                transactionType: MoraWalletTransaction::TRANSACTION_TYPE_MANUAL_ADJUSTMENT,
                description: $request->description,
                initiatedBy: auth()->user(),
                metadata: [
                    'action' => 'manual_debit',
                    'admin_id' => auth()->id(),
                ]
            );

            return response()->json([
                'message' => 'Money debited successfully',
                'transaction' => [
                    'id' => $transaction->id,
                    'reference_number' => $transaction->reference_number,
                    'amount' => (float) $transaction->amount,
                    'balance_after' => (float) $transaction->balance_after,
                ],
                'wallet_balance' => (float) $wallet->fresh()->balance,
            ]);
        } catch (\Exception $e) {
            \Log::error('Mora wallet debit error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to debit wallet',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
