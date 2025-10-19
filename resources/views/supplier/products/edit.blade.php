@extends('layouts.metronic.supplier')

@section('content')
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.supplier_dashboard.products.edit_product')</h3>
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
                        @lang('messages.edit')
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">@lang('messages.supplier_dashboard.products.edit_product_info')</h3>
                </div>
            </div>

            <form action="{{ route('supplier.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="kt-portlet__body">
                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.category')</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">@lang('messages.select_category')</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected($category->id == $product->category_id)>
                                    {{ app()->getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.name_en')</label>
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $product->name_en) }}" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.name_ar')</label>
                        <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $product->name_ar) }}" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.products.description_en')</label>
                        <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $product->description_en) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.products.description_ar')</label>
                        <textarea name="description_ar" class="form-control" rows="3">{{ old('description_ar', $product->description_ar) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.sku')</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.supplier_dashboard.products.table_headers.price')</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('messages.products.product_image')</label>
                        @if($product->image)
                            <div class="mb-3">
                                <img src="{{ asset($product->image) }}" alt="Current Product Image" style="max-height: 100px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image" id="removeImage">
                                    <label class="form-check-label" for="removeImage">
                                        @lang('messages.remove_current_image')
                                    </label>
                                </div>
                            </div>
                        @endif
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="productImage" name="image" accept="image/*">
                            <label class="custom-file-label" for="productImage">@lang('messages.choose_new_image')</label>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">@lang('messages.products.update')</button>
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
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
@endpush
