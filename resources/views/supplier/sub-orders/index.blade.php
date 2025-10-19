@extends('layouts.metronic.supplier')

@section('title', 'Sub Orders')

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Sub Orders
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Store</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subOrders as $subOrder)
                    <tr>
                        <td>{{ $subOrder->order->reference_number }}</td>
                        <td>{{ $subOrder->created_at->format('Y-m-d') }}</td>
                        <td>{{ $subOrder->order->store->name }}</td>
                        <td>{{ $subOrder->total_amount }}</td>
                        <td>
                            <span class="kt-badge kt-badge--{{ $subOrder->status_color }} kt-badge--inline">
                                {{ $subOrder->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('supplier.sub-orders.show', $subOrder) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                                <i class="la la-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No sub orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $subOrders->links() }}
        </div>
    </div>
</div>
@endsection
