@extends('layouts.metronic.base')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Supplier Products</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("supplier.products.datatable") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'sku', name: 'sku' },
            { data: 'price', name: 'price' },
            { data: 'available_quantity', name: 'available_quantity' },
            { data: 'status', name: 'status' },
