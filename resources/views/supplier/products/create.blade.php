@extends('layouts.metronic.supplier')

@section('content')
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.supplier_dashboard.products.add_product')</h3>
                <div class="kt-subheader__breadcrumbs">
                    <a href="{{ route('supplier.products.index') }}" class="kt-subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="{{ route('supplier.products.index') }}" class="kt-subheader__breadcrumbs-link">
                        @lang('messages.supplier_dashboard.navigation.products')
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                        <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
                        @lang('messages.products.add_new')
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">@lang('messages.supplier_dashboard.products.page_title')</h3>
                </div>
            </div>

            <form action="{{ route('supplier.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="kt-portlet__body">
                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.category')</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">@lang('messages.products.select_category')</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">@lang($category->{'name_' . app()->getLocale()})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.name_en')</label>
                        <input type="text" name="name_en" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.name_ar')</label>
                        <input type="text" name="name_ar" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.products.description_en')</label>
                        <textarea name="description_en" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.products.description_ar')</label>
                        <textarea name="description_ar" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.sku')</label>
                        <input type="text" name="sku" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.price')</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.products.product_image')</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="productImage" name="image" accept="image/*">
                            <label class="custom-file-label" for="productImage">@lang('messages.products.choose_file')</label>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">@lang('messages.submit')</button>
                        <a href="{{ route('supplier.products.index') }}" class="btn btn-secondary">@lang('messages.cancel')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Update file input label
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName || '@lang('messages.choose_file')');
            });
        });
    </script>
@endpush
