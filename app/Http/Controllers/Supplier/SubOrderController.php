<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subOrders = SubOrder::where('supplier_id', Auth::user()->supplier->id)
            ->with(['order', 'representative'])
            ->latest()
            ->paginate(10);

        return view('supplier.sub-orders.index', compact('subOrders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SubOrder $subOrder)
    {
        $this->authorize('view', $subOrder);

        $subOrder->load([
            'order.customer',
            'representative',
            'items.product',
            'items.product.category',
            'store',
            'store.branches',
            'timeline',
            'payments'
        ]);

        $representatives = Auth::user()->supplier->representatives()->active()->get();
        $statuses = config('order.statuses');

        return view('supplier.sub-orders.show', compact('subOrder', 'representatives', 'statuses'));
    }

    /**
     * Accept a sub-order.
     */
    public function accept(SubOrder $subOrder)
    {
        $this->authorize('modifySubOrder', $subOrder);

        $subOrder->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Sub-order accepted successfully');
    }

    /**
     * Reject a sub-order.
     */
    public function reject(SubOrder $subOrder)
    {
        $this->authorize('modifySubOrder', $subOrder);

        $subOrder->update([
            'status' => 'rejected',
            'rejected_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Sub-order rejected successfully');
    }

    /**
     * Update sub-order status
     */
    public function updateStatus(Request $request, SubOrder $subOrder)
    {
        $this->authorize('modifySubOrder', $subOrder);

        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', array_keys(config('order.statuses')))
        ]);

        $subOrder->update([
            'status' => $validated['status']
        ]);

        // Add to timeline
        $subOrder->timeline()->create([
            'status' => $validated['status'],
            'notes' => 'Status updated manually'
        ]);

        return redirect()->back()
            ->with('success', 'Status updated successfully');
    }

    /**
     * Assign representative to sub-order
     */
    public function assignRepresentative(Request $request, SubOrder $subOrder)
    {
        $this->authorize('modifySubOrder', $subOrder);

        $validated = $request->validate([
            'representative_id' => 'nullable|exists:representatives,id'
        ]);

        $subOrder->update([
            'representative_id' => $validated['representative_id']
        ]);

        return redirect()->back()
            ->with('success', 'Representative assigned successfully');
    }
}
