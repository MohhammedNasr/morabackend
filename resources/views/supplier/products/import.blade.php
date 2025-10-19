@extends('layouts.metronic.supplier')

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">    {{ __('messages.products.import') }}</h3>
        </div>
    </div>

    <div class="kt-portlet__body">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('import_errors'))
            <div class="alert alert-danger">
                <h4>    {{ __('messages.products.Import Errors') }}:</h4>
                <ul>
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('supplier.products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>{{ __('messages.products.choose_excel') }}</label>
                <input type="file" name="file" class="form-control" required>
                <span class="form-text text-muted">{{ __('messages.products.allowed_file_types') }}: xlsx, xls, csv</span>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('messages.products.import') }}</button>
            <a href="{{ route('supplier.products.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
