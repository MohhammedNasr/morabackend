@extends('layouts.metronic.base')

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Sub-Order #{{ $subOrder->id }}
                <span class="badge badge-{{ $subOrder->status_color }}">{{ ucfirst($subOrder->status) }}</span>
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <a href="{{ route('supplier.sub-orders.index') }}" class="btn btn-secondary">
                    <i class="la la-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="kt-portlet__body">
        <div class="row">
            <div class="col-md-6">
                <div class="kt-section">
                    <div class="kt-section__info">
                        <h4>Order Details</h4>
                        <div class="kt-section__content">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order Date</th>
                                    <td>{{ $subOrder->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $subOrder->status_color }}">
                                            {{ ucfirst($subOrder->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Store</th>
                                    <td>{{ $subOrder->store->name }}</td>
                                </tr>
                                <tr>
                                    <th>Branch</th>
                                    <td>{{ $subOrder->storeBranch->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Representative</th>
                                    <td>
                                        @if($subOrder->representative)
                                            {{ $subOrder->representative->name }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="kt-section">
                    <div class="kt-section__info">
                        <h4>Customer Details</h4>
                        <div class="kt-section__content">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $subOrder->order->customer->name }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $subOrder->order->customer->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $subOrder->order->shipping_address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-section">
            <div class="kt-section__info">
                <h4>Order Items</h4>
                <div class="kt-section__content">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subOrder->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->product->category->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Subtotal</th>
                                <th>{{ number_format($subOrder->subtotal, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Shipping</th>
                                <th>{{ number_format($subOrder->shipping_cost, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Total</th>
                                <th>{{ number_format($subOrder->total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="kt-section">
            <div class="kt-section__info">
                <h4>Status Timeline</h4>
                <div class="kt-section__content">
                    <div class="kt-timeline-2">
                        <div class="kt-timeline-2__items">
                            @foreach($subOrder->timeline as $event)
                            <div class="kt-timeline-2__item">
                                <div class="kt-timeline-2__item-circle">
                                    <div class="kt-bg-{{ $event->status_color }}"></div>
                                </div>
                                <div class="kt-timeline-2__item-content">
                                    <div class="kt-timeline-2__item-title">
                                        {{ ucfirst($event->status) }}
                                    </div>
                                    <div class="kt-timeline-2__item-date">
                                        {{ $event->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-section">
            <div class="kt-section__info">
                <h4>Order Actions</h4>
                <div class="kt-section__content">
                    <form action="{{ route('supplier.sub-orders.update-status', $subOrder) }}" method="POST" class="form-inline mb-3">
                        @csrf
                        <div class="form-group mr-2">
                            <select name="status" class="form-control">
                                @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ $subOrder->status == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>

                    <form action="{{ route('supplier.sub-orders.assign-representative', $subOrder) }}" method="POST" class="form-inline">
                        @csrf
                        <div class="form-group mr-2">
                            <select name="representative_id" class="form-control">
                                <option value="">Select Representative</option>
                                @foreach($representatives as $rep)
                                <option value="{{ $rep->id }}" {{ $subOrder->representative_id == $rep->id ? 'selected' : '' }}>
                                    {{ $rep->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info">Assign Representative</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
