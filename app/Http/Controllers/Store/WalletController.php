<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->guard('web')->user();
        if (!$user || !$user->store) {
            return redirect()->route('store.dashboard')->with('error', 'Store not found');
        }
        $store_id =  $user->store->id;
        $wallet = Wallet::where('store_id', $store_id)->first();
        if (!$wallet)
            $wallet = WalletTransaction::create([
                'store_id' => $store_id,
                'balance' => 0,
                'status' => 'active'
            ]);
        // Fetch recent wallet transactions
        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('store.wallet.index', compact('wallet', 'transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Logic to show the form for creating a new wallet transaction
        return view('store.wallet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Logic to store a new wallet transaction
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        // Store the wallet transaction
        // Example: WalletTransaction::create($request->all());

        return redirect()->route('store.wallet.index')->with('success', 'Wallet transaction created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Logic to display a specific wallet transaction
        // Example: $transaction = WalletTransaction::findOrFail($id);
        return view('store.wallet.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Logic to show the form for editing a wallet transaction
        // Example: $transaction = WalletTransaction::findOrFail($id);
        return view('store.wallet.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Logic to update a wallet transaction
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        // Update the wallet transaction
        // Example: $transaction = WalletTransaction::findOrFail($id);
        // Example: $transaction->update($request->all());

        return redirect()->route('store.wallet.index')->with('success', 'Wallet transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Logic to delete a wallet transaction
        // Example: $transaction = WalletTransaction::findOrFail($id);
        // Example: $transaction->delete();

        return redirect()->route('store.wallet.index')->with('success', 'Wallet transaction deleted successfully.');
    }

    /**
     * Show the form for charging the wallet.
     *
     * @return \Illuminate\Http\Response
     */
    public function charge()
    {
        // Logic to show the form for charging the wallet
        return view('store.wallet.charge');
    }
}
