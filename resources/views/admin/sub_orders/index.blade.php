@extends('layouts.metronic.admin')

@section('content')
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order ID</th>
                            <th>Product ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subOrders as $subOrder)
                            <tr>
                                <td>#{{ $subOrder->id }}</td>
                                <td>{{ $subOrder->order_id }}</td>
                                <td>{{ $subOrder->product_id }}</td>
                                <td nowrap>
                                    <a href="{{ route('admin.sub_orders.show', $subOrder) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                                        <i class="la la-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.sub_orders.edit', $subOrder) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.sub_orders.destroy', $subOrder) }}" method="POST" class="d-inline" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" onclick="return confirm('Are you sure you want to delete this sub-order?')">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No sub-orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
