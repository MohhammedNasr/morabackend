@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Edit Product</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="{{ route('admin.products.index') }}" class="kt-subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="{{ route('admin.products.index') }}" class="kt-subheader__breadcrumbs-link">Products</a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Edit Product</span>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Product Details</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" enctype="multipart/form-data">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="la la-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="kt-form" method="POST" action="{{ route('admin.products.update', $product) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="name_en">Name (English) *</label>
                                    <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                        id="name_en" name="name_en" value="{{ old('name_en', $product->name_en) }}"
                                        required autofocus>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="name_ar">Name (Arabic) *</label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror"
                                        id="name_ar" name="name_ar" value="{{ old('name_ar', $product->name_ar) }}"
                                        required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="sku">SKU *</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                        id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                                        required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="price">Price *</label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price', $product->price) }}"
                                        required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="description_en">Description (English)</label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en"
                                        rows="3">{{ old('description_en', $product->description_en) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="description_ar">Description (Arabic)</label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar"
                                        rows="3">{{ old('description_ar', $product->description_ar) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="category_id">Category *</label>
                                    <select class="form-control kt-select2 @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name_en }} / {{ $category->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Supplier *</label>
                                        @if (auth()->user()->hasRole('admin'))
                                            <select name="supplier_id"
                                                class="form-control kt-select2 @error('supplier_id') is-invalid @enderror"
                                                required>
                                                <option value="">Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('supplier_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @else
                                            <input type="hidden" name="supplier_id"
                                                value="{{ auth()->user()->supplier_id }}">
                                            <input type="text" class="form-control"
                                                value="{{ auth()->user()->supplier->name }}" readonly>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="image">Image</label>
                                    <div class="form-group">
                                        @if ($product->image && file_exists(public_path($product->image)))
                                            <div class="mb-3">
                                                <div class="kt-avatar kt-avatar--outline" id="image-preview">
                                                    <div class="kt-avatar__holder"
                                                        style="background-image: url({{ asset($product->image) }}); background-size: cover;">
                                                    </div>
                                                    <div class="kt-avatar__cancel" data-toggle="kt-tooltip" title="Remove image">
                                                        <i class="la la-close"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                                id="image" name="image" accept="image/jpeg,image/png,image/gif">
                                            <label class="custom-file-label" for="image">Choose file (max 2MB)</label>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Allowed types: jpeg, png, gif. Max size: 2MB
                                            </small>
                                        </div>
                                        <input type="hidden" name="remove_image" id="remove-image" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection

@push('styles')
    <link href="{{ asset('metronic_theme/assets/vendors/custom/select2/select2.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('metronic_theme/assets/vendors/custom/select2/select2.bundle.js') }}" type="text/javascript">
    </script>
    <script>
        // Initialize select2
        $('.kt-select2').select2({
            placeholder: "Select an option"
        });

        // Update file input label with selected filename
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Initialize repeater for suppliers
        $('.kt-repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
                $(this).find('.supplier-select').select2({
                    placeholder: "Select Supplier"
                });
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function(setIndexes) {
                $('.supplier-select').select2({
                    placeholder: "Select Supplier"
                });
            }
        });

        // Handle image preview and removal
        $('#image-preview .kt-avatar__cancel').on('click', function() {
            $('#image-preview').remove();
            $('#remove-image').val(1);
        });

        // Initialize Metronic's SweetAlert for delete confirmation
        $(document).ready(function() {
            $('form[action$="destroy"]').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        form.submit();
                    }
                });
            });
        });
