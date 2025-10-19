<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletTransactionController extends Controller
{
public function index()
{
    // Ensure the authenticated user has a store
    $user = auth()->guard('web')->user();
    if (!$user || !$user->store) {
        return redirect()->route('store.wallet.index')->with('error', 'Store not found.');
    }

    // Ensure the store has a wallet
    $store = $user->store;
    if (!$store->wallet) {
        return redirect()->route('store.wallet.index')->with('error', 'Wallet not found.');
    }

    // Fetch wallet transactions with pagination
    $transactions = $store->wallet->transactions()
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    // Pass transactions to the view
    return view('store.wallet.transactions', compact('transactions'));
}
}
