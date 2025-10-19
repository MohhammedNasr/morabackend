@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('Import Products') - {{ $store->name }}</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.stores.index') }}" class="btn btn-default btn-elevate btn-icon-sm">
                        <i class="la la-arrow-left"></i> @lang('Back')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        @lang('Import Products')
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.stores.import-products.store', $store) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>@lang('Products File')</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="products_file" name="products_file" accept=".xlsx,.xls,.csv" required>
                            <label class="custom-file-label" for="products_file">@lang('Choose file')</label>
                        </div>
                        <small class="form-text text-muted">
                            @lang('Supported formats: .xlsx, .xls, .csv')
                        </small>
                    </div>

                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">@lang('Import')</button>
                    </div>
                </form>

                <div class="mt-5">
                    <h4>@lang('Import Instructions')</h4>
                    <p>@lang('Download the template file and fill in your product data:')</p>
                    <a href="{{ asset('templates/products-import-template.xlsx') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-download"></i> @lang('Download Template')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
@endpush
