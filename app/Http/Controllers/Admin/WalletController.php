<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function index()
    {
        return view('admin.wallets.index');
    }

    public function datatable(Request $request)
    {
        $wallets = Wallet::with('store:id,name')
            ->select('wallets.*');
        
        return DataTables::of($wallets)
            ->addColumn('user_name', function($wallet) {
                return $wallet->store->name ?? '';
            })
            ->addColumn('actions', function($wallet) {
                return view('admin.wallets.partials.actions', compact('wallet'))->render();
            })
            ->editColumn('created_at', function($wallet) {
                return $wallet->created_at?->format('Y-m-d H:i') ?? '';
            })
            ->editColumn('balance', function($wallet) {
                return number_format($wallet->balance, 2);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.wallets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'balance' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        Wallet::create([
            'user_id' => $request->user_id,
            'balance' => $request->balance,
            'status' => $request->status
        ]);

        return redirect()->route('admin.wallets.index')
            ->with('success', 'Wallet created successfully');
    }

    public function edit(Wallet $wallet)
    {
        return view('admin.wallets.edit', compact('wallet'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        $request->validate([
            'balance' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        $wallet->update([
            'balance' => $request->balance,
            'status' => $request->status
        ]);

        return redirect()->route('admin.wallets.index')
            ->with('success', 'Wallet updated successfully');
    }

    public function destroy(Wallet $wallet)
    {
        $wallet->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        return view('admin.wallets.show', compact('wallet'));
    }

    public function toggleStatus(Wallet $wallet)
    {
        $wallet->status = $wallet->status === 'active' ? 'inactive' : 'active';
        $wallet->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet status updated successfully',
            'new_status' => $wallet->status
        ]);
    }
}
