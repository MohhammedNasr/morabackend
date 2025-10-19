<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $store = Store::where('owner_id', $user->id)->first();

        $query = Order::where('store_id', $store->id)
            ->with(['items.product', 'subOrders.supplier']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier')) {
            $query->whereHas('subOrders', function($q) use ($request) {
                $q->where('supplier_id', $request->supplier);
            });
        }

        $orders = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $suppliers = Supplier::orderBy('name')->get();

        return view('store.orders.index', compact('orders', 'suppliers'));
    }

    /**
     * Show the form for creating a new order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $suppliers = Supplier::with(['products' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        return view('store.orders.create', compact('suppliers'));
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $store = $request->user()->store;
        $supplier = Supplier::findOrFail($validated['supplier_id']);

        // Calculate total amount
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $supplierProduct = $product->suppliers()->where('supplier_id', $supplier->id)->first();

            if (!$supplierProduct || !$supplierProduct->pivot->is_active) {
                return back()->withErrors(['items' => __('One or more products are not available.')]);
            }

            $totalAmount += $supplierProduct->pivot->price * $item['quantity'];
        }

        // Check credit limit
        if ($totalAmount > $store->credit_limit) {
            return back()->withErrors(['credit' => __('Order amount exceeds credit limit.')]);
        }

        DB::transaction(function () use ($store, $supplier, $validated, $totalAmount) {
            $order = Order::create([
                'store_id' => $store->id,
                'supplier_id' => $supplier->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_due_date' => now()->addDays(30), // TODO: Make this configurable
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $supplierProduct = $product->suppliers()->where('supplier_id', $supplier->id)->first();

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $supplierProduct->pivot->price,
                ]);
            }
        });

        return redirect()
            ->route('store.orders.index')
            ->with('success', __('Order created successfully.'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product', 'subOrders.supplier', 'storeBranch']);
        return view('store.orders.show', compact('order'));
    }

    /**
     * Cancel the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Verify the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return back()->with('error', __('Only pending orders can be verified.'));
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'status' => 'verified',
            ]);
        });

        return back()->with('success', __('Order verified successfully.'));
    }

    /**
     * Cancel the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return back()->with('error', __('Order cannot be cancelled.'));
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'status' => 'cancelled',
            ]);
        });

        return back()->with('success', __('Order cancelled successfully.'));
    }

    /**
     * Get orders data for DataTables
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        $user = $request->user();
        $store = Store::where('owner_id', $user->id)->first();

        $query = Order::where('store_id', $store->id)
            ->with(['storeBranch', 'subOrders.supplier']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('branch')) {
            $query->where('store_branch_id', $request->branch);
        }

        return datatables()->eloquent($query)
            ->addColumn('branch_name', function($order) {
                return $order->storeBranch ? $order->storeBranch->name : '-';
            })
            ->addColumn('suppliers', function($order) {
                return $order->subOrders->map(function($subOrder) {
                    return $subOrder->supplier->name;
                })->unique()->toArray();
            })
            ->addColumn('status_color', function($order) {
                return [
                    'pending' => 'warning',
                    'verified' => 'success',
                    'cancelled' => 'danger',
                    'approved' => 'primary',
                    'rejected' => 'danger',
                    'delivered' => 'success',
                ][$order->status] ?? 'secondary';
            })
            ->addColumn('actions', function($order) {
                $html = '<a href="'.route('store.orders.show', $order).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                    <i class="la la-eye"></i>
                </a>';

                if ($order->status === 'pending') {
                    $html .= '<form method="POST" action="'.route('store.orders.verify', $order).'" class="d-inline">
                        '.csrf_field().'
                        <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Verify">
                            <i class="la la-check-circle"></i>
                        </button>
                    </form>';

                    $html .= '<form method="POST" action="'.route('store.orders.cancel', $order).'" class="d-inline">
                        '.csrf_field().'
                        <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Cancel" onclick="return confirm(\'Are you sure?\')">
                            <i class="la la-trash"></i>
                        </button>
                    </form>';
                }

                return $html;
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
}
