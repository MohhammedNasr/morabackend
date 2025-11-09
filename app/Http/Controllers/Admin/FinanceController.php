<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MoraWallet;
use App\Models\MoraWalletTransaction;
use App\Models\SupplierTransaction;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Get financial statistics
     */
    public function stats()
    {
        try {
            $moraWallet = MoraWallet::getInstance();
            
            // Calculate total revenue (all credits to Mora wallet)
            $totalRevenue = MoraWalletTransaction::where('type', 'credit')->sum('amount');
            
            // Count all transactions
            $totalTransactions = MoraWalletTransaction::count();
            
            // Pending settlements (supplier transactions pending settlement)
            $pendingSettlements = SupplierTransaction::where('status', 'pending_settlement')
                ->sum('amount');
            
            // Completed payments this month
            $completedPayments = SupplierTransaction::where('status', 'settled')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
            
            // Commission earned (could be calculated based on transaction fees)
            $commissionEarned = 0; // TODO: Implement commission logic
            
            return response()->json([
                'total_revenue' => (float) $totalRevenue,
                'total_transactions' => $totalTransactions,
                'pending_settlements' => (float) $pendingSettlements,
                'completed_payments' => (float) $completedPayments,
                'mora_wallet_balance' => (float) $moraWallet->balance,
                'commission_earned' => (float) $commissionEarned,
            ]);
        } catch (\Exception $e) {
            \Log::error('Finance stats error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch finance statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction list with filters
     */
    public function transactions(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 50);
            $type = $request->get('type'); // credit, debit
            $from = $request->get('from');
            $to = $request->get('to');
            
            $query = MoraWalletTransaction::with(['balanceRequest.storeBranch.store'])
                ->orderBy('created_at', 'desc');
            
            // Filter by type
            if ($type && $type !== 'all') {
                $query->where('type', $type);
            }
            
            // Filter by date range
            if ($from) {
                $query->whereDate('created_at', '>=', $from);
            }
            if ($to) {
                $query->whereDate('created_at', '<=', $to);
            }
            
            $transactions = $query->paginate($perPage);
            
            $transactions->getCollection()->transform(function ($txn) {
                return [
                    'id' => $txn->id,
                    'reference_number' => $txn->reference_number,
                    'type' => $txn->type,
                    'amount' => (float) $txn->amount,
                    'description' => $txn->description,
                    'transaction_type' => $txn->transaction_type,
                    'status' => 'completed', // Mora wallet transactions are always completed
                    'store_name' => $txn->balanceRequest?->storeBranch?->store?->name,
                    'balance_before' => (float) $txn->balance_before,
                    'balance_after' => (float) $txn->balance_after,
                    'created_at' => $txn->created_at->toIso8601String(),
                ];
            });
            
            return response()->json($transactions);
        } catch (\Exception $e) {
            \Log::error('Finance transactions error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch transactions',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export transactions (CSV/Excel)
     */
    public function export(Request $request)
    {
        // TODO: Implement export functionality
        return response()->json([
            'message' => 'Export feature coming soon'
        ]);
    }
}
