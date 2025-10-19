@extends('layouts.metronic.base')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Import Products</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('suppliers.import-products') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Select Excel File</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                            <small class="form-text text-muted">
                                File must be in .xlsx, .xls, or .csv format
                            </small>
                        </div>
                        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                        <button type="submit" class="btn btn-primary">Import</button>
                        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
