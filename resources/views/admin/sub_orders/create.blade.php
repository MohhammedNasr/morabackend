@extends('layouts.metronic.admin')

@section('content')
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form method="POST" action="{{ route('admin.sub_orders.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="order_id">Order ID</label>
                        <input type="text" class="form-control" id="order_id" name="order_id" required>
                    </div>
                    <div class="form-group">
                        <label for="product_id">Product ID</label>
                        <input type="text" class="form-control" id="product_id" name="product_id" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create SubOrder</button>
                </form>
            </div>
        </div>
    </div>
@endsection
