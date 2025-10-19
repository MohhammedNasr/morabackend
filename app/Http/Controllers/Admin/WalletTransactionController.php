<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WalletTransactionController extends Controller
{
    public function index(Request $request)
    {
        $walletId = $request->query('wallet_id');
        return view('admin.wallet_transactions.index', compact('walletId'));
    }

    public function datatable(Request $request)
    {
        $transactions = WalletTransaction::with(['wallet.store'])
            ->select('wallet_transactions.*');

        if ($request->has('wallet_id')) {
            $transactions->where('wallet_id', $request->wallet_id);
        }

        return DataTables::of($transactions)
            ->addColumn('store', function ($transaction) {
                return $transaction->wallet->store->name;
            })
            ->addColumn('amount', function ($transaction) {
                return number_format($transaction->amount, 2);
            })
            ->addColumn('status', function ($transaction) {
                return $transaction->status;
            })
            ->addColumn('balance_before', function ($transaction) {
                return number_format($transaction->balance_before, 2);
            })
            ->addColumn('balance_after', function ($transaction) {
                return $transaction->balance_after 
                    ? number_format($transaction->balance_after, 2)
                    : '-';
            })
            ->addColumn('created_at', function ($transaction) {
                return $transaction->created_at->format('Y-m-d H:i');
            })
            ->addColumn('actions', function ($transaction) {
                return view('admin.wallet_transactions.partials.actions', compact('transaction'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.wallet_transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'wallet_id' => 'required|exists:wallets,id',
            'type' => 'required|in:credit,debit',
        ]);

        $wallet = \App\Models\Wallet::findOrFail($request->wallet_id);
        $amount = $request->amount;
        
        if ($request->type === 'credit') {
            $balanceAfter = $wallet->balance + $amount;
        } else {
            $balanceAfter = $wallet->balance - $amount;
        }

        $data = $request->all();
        $data['balance_after'] = $balanceAfter;
        $data['reference_number'] = \Illuminate\Support\Str::uuid()->toString();

        WalletTransaction::create($data);
        
        // Update wallet balance
        $wallet->update(['balance' => $balanceAfter]);

        return redirect()->route('admin.wallet_transactions.index')->with('success', 'Wallet Transaction created successfully.');
    }

    public function show(WalletTransaction $walletTransaction)
    {
        return view('admin.wallet_transactions.show', compact('walletTransaction'));
    }

    public function edit(WalletTransaction $walletTransaction)
    {
        return view('admin.wallet_transactions.edit', compact('walletTransaction'));
    }

    public function update(Request $request, WalletTransaction $walletTransaction)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'wallet_id' => 'required|exists:wallets,id',
        ]);

        $walletTransaction->update($request->all());
        return redirect()->route('admin.wallet_transactions.index')->with('success', 'Wallet Transaction updated successfully.');
    }

    public function destroy(WalletTransaction $walletTransaction)
    {
        $walletTransaction->delete();
        return redirect()->route('admin.wallet_transactions.index')->with('success', 'Wallet Transaction deleted successfully.');
    }

    public function approve(WalletTransaction $walletTransaction)
    {
        if ($walletTransaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending transactions can be approved');
        }

        $wallet = $walletTransaction->wallet;
        
        // Update wallet balance
        if ($walletTransaction->type === 'credit') {
            $wallet->balance += $walletTransaction->amount;
        } else {
            $wallet->balance -= $walletTransaction->amount;
        }
        
        $wallet->save();

        // Update transaction status
        $walletTransaction->update([
            'status' => 'approved',
            'balance_after' => $wallet->balance
        ]);

        return redirect()->route('admin.wallet_transactions.index')
            ->with('success', 'Transaction approved successfully');
    }

    public function reject(WalletTransaction $walletTransaction)
    {
        if ($walletTransaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending transactions can be rejected');
        }

        $walletTransaction->update([
            'status' => 'rejected'
        ]);

        return redirect()->route('admin.wallet_transactions.index')
            ->with('success', 'Transaction rejected successfully');
    }

    public function updateStatus(Request $request, WalletTransaction $wallet_transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,reversed'
        ]);

        $wallet_transaction->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction status updated successfully'
        ]);
    }
}
