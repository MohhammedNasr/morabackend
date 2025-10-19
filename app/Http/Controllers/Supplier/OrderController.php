<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('supplier.orders.index');
    }

    public function datatable(Request $request)
    {
        $supplier = Auth::user();
        // dd($supplier->representatives());
        $query = SubOrder::with(['order.store'])
            ->where('supplier_id', $supplier->id);

        return DataTables::eloquent($query)
            ->addColumn('store', function ($order) {
                return $order->order->store->name ?? 'N/A';
            })
            ->addColumn('status', function ($order) {
                return '<span class="kt-badge kt-badge--inline kt-badge--pill">' . ucfirst($order->status) . '</span>';
            })
            ->addColumn('created_at', function ($order) {
                return $order->created_at->format('Y-m-d H:i');
            })
            ->addColumn('actions', function ($order) {
                return '
                    <a href="' . route('supplier.orders.show', $order) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        <i class="la la-eye"></i>
                    </a>';
            })
            ->filter(function ($query) use ($request) {
                // Handle search
                if ($request->filled('search.value')) {
                    $search = $request->input('search.value');
                    $query->where(function ($q) use ($search) {
                        $q->whereHas('order', function ($q) use ($search) {
                            $q->where('reference_number', 'like', "%{$search}%");
                        })
                            ->orWhere('status', 'like', "%{$search}%");
                    });
                }

                // Handle status filter
                if ($request->filled('status')) {
                    $query->where('status', $request->input('status'));
                }

                // Handle date range filter
                if ($request->filled('date_range')) {
                    $dates = explode(' - ', $request->date_range);
                    if (count($dates) === 2) {
                        $query->whereBetween('created_at', [
                            \Carbon\Carbon::parse($dates[0])->startOfDay(),
                            \Carbon\Carbon::parse($dates[1])->endOfDay()
                        ]);
                    }
                }
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(SubOrder $subOrder)
    {

        $supplier = Auth::user();

        # $request->user()->supplier;
        // dd($supplier->id . "_________" . $subOrder->supplier_id);
        // if ($subOrder->supplier_id !== $supplier->id) {
        //     abort(403, 'Unauthorized access');
        // }
        $subOrder = SubOrder::first();

        $subOrder->load(['order.store', 'order.items.product']);
        return view('supplier.orders.show', compact('subOrder'));
    }

    /**
     * Approve the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, SubOrder $subOrder)
    {
        $supplier = Auth::user();
        if ($subOrder->supplier_id !== $supplier->id) {
            abort(403);
        }

        if ($subOrder->status !== SubOrder::STATUS_PENDING) {
            return back()->with('error', __('Order cannot be approved.'));
        }

        DB::transaction(function () use ($subOrder) {
            $subOrder->update([
                'status' => SubOrder::STATUS_ACCEPTED_BY_SUPPLIER,
            ]);
        });

        return back()->with('success', __('Order approved successfully.'));
    }

    /**
     * Reject the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, SubOrder $subOrder)
    {
        $this->authorize('reject', $subOrder);

        if ($subOrder->status !== SubOrder::STATUS_PENDING) {
            return back()->with('error', __('Order cannot be rejected.'));
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($subOrder, $validated) {
            $subOrder->update([
                'status' => SubOrder::STATUS_REJECTED_BY_SUPPLIER,
                'rejection_reason' => $validated['reason'],
            ]);
        });

        return back()->with('success', __('Order rejected successfully.'));
    }

    /**
     * Mark the specified order as delivered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deliver(Request $request, SubOrder $subOrder)
    {
        $supplier = Auth::user();
        if ($subOrder->supplier_id !== $supplier->id) {
            abort(403);
        }

        if ($subOrder->status !== SubOrder::STATUS_ACCEPTED_BY_SUPPLIER) {
            return back()->with('error', __('Order cannot be marked as delivered.'));
        }

        DB::transaction(function () use ($subOrder) {
            $subOrder->update([
                'status' => SubOrder::STATUS_DELIVERED,
                'delivered_at' => now(),
            ]);
        });

        return back()->with('success', __('Order marked as delivered successfully.'));
    }

    /**
     * Display a listing of pending orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function pending(Request $request)
    {
        $supplier = Auth::user();

        $orders = SubOrder::query()
            ->where('supplier_id', $supplier->id)
            ->where('status', 'pending')
            ->with(['order.store'])
            ->latest()
            ->paginate(10);

        return view('supplier.orders.index', compact('orders'));
    }
}
