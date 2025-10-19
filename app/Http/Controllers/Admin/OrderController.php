<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $stores = Store::all();
        $suppliers = Supplier::all();

        return view('admin.orders.index', compact('stores', 'suppliers'));
    }

    public function show(Order $order)
    {
        $order->load([
            'store.users' => function ($query) {
                $query->wherePivot('is_primary', true);
            },
            'subOrders.supplier',
            'items.product',
            'timeline'
        ]);
        return view('admin.orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        $order->load([
            'store.users' => function ($query) {
                $query->wherePivot('is_primary', true);
            },
            'subOrders.supplier',
            'items.product',
            'timeline'
        ]);

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));

        $filename = 'invoice-' . $order->reference_number . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadInvoice(Order $order)
    {
        $order->load([
            'store.users' => function ($query) {
                $query->wherePivot('is_primary', true);
            },
            'subOrders.supplier',
            'items.product',
            'timeline'
        ]);

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));

        $filename = 'invoice-' . $order->reference_number . '.pdf';

        return $pdf->stream($filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function approve(Request $request, Order $order)
    {
        if (!$order->requires_mora_approval) {
            return back()->with('error', 'This order does not require Mora approval.');
        }

        if (!$order->isPending()) {
            return back()->with('error', 'Only pending orders can be approved.');
        }

        try {
            DB::beginTransaction();

            $order->update([
                'status' => Order::STATUS_VERIFIED,
            ]);

            // TODO: Send notification to supplier and store

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reject(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if (!$order->requires_mora_approval) {
            return back()->with('error', 'This order does not require Mora approval.');
        }

        if (!$order->isPending()) {
            return back()->with('error', 'Only pending orders can be rejected.');
        }

        try {
            DB::beginTransaction();

            $order->update([
               // 'status' => Order::STATUS_REJECTED,
                'cancellation_reason' => $request->reason,
            ]);

            // TODO: Send notification to store

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Order rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Order $order)
    {
        if (!$order->isPending()) {
            return back()->with('error', 'Only pending orders can be deleted.');
        }

        $order->items()->delete();
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:order_payments,id',
            'status' => 'required|in:paid,due_to_pay,failed'
        ]);

        try {
            $payment = \App\Models\OrderPayment::findOrFail($request->payment_id);
            $payment->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => __('orders.payment_status_updated')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function datatable(Request $request)
    {
        $orders = Order::query()
            ->with([
                'store.users' => function ($query) {
                    $query->wherePivot('is_primary', true);
                },
                'subOrders.supplier',
                'items.product.supplier'
            ])
            ->select([
                'id',
                'reference_number',
                'store_id',
                'total_amount',
                'status',
                'created_at'
            ]);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $orders->where('status', $request->status);
        }

        if ($request->has('store_id') && $request->store_id) {
            $orders->where('store_id', $request->store_id);
        }

        if ($request->has('supplier_id') && $request->supplier_id) {
            $orders->whereHas('subOrders', function ($query) use ($request) {
                $query->where('supplier_id', $request->supplier_id);
            });
        }

        if ($request->has('date_range') && $request->date_range) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $orders->whereBetween('created_at', [
                    \Carbon\Carbon::parse($dates[0])->startOfDay(),
                    \Carbon\Carbon::parse($dates[1])->endOfDay()
                ]);
            }
        }

        return datatables()->eloquent($orders)
            ->addColumn('store', function ($order) {
                return $order->store->name . ' (' .
                    $order->store->users->first()->name . ')';
            })
            ->addColumn('supplier', function ($order) {
                return $order->subOrders->unique('supplier_id')
                    ->map(function ($subOrder) {
                        return $subOrder->supplier->name . ' (' .
                            number_format($subOrder->total_amount, 2) . ')';
                    })->implode('<br>');
            })
            ->addColumn('status', function ($order) {
                return view('admin.orders.partials.status', compact('order'));
            })
            ->addColumn('total_price', function ($order) {
                return number_format($order->total_amount, 2);
            })
            ->addColumn('actions', function ($order) {
                return view('admin.orders.partials.actions', compact('order'));
            })
            ->editColumn('created_at', function ($order) {
                return $order->created_at->format('Y-m-d H:i');
            })
            ->rawColumns(['status', 'actions', 'supplier'])
            ->make(true);
    }
}
