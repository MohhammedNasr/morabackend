@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Product Details</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet">
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="kt-portlet kt-portlet--bordered">
                            <div class="kt-portlet__body">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name_en }}" class="img-fluid rounded">
                                @else
                                    <div class="text-center text-muted py-5">No image available</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="kt-portlet kt-portlet--bordered">
                            <div class="kt-portlet__body">
                                <h4 class="kt-portlet__head-title">Product Information</h4>
                                <div class="kt-separator kt-separator--space-sm"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>English Name</label>
                                            <p class="form-control-static">{{ $product->name_en }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Arabic Name</label>
                                            <p class="form-control-static">{{ $product->name_ar }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label>SKU</label>
                                            <p class="form-control-static">{{ $product->sku }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price</label>
                                            <p class="form-control-static">{{ number_format($product->price, 2) }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <p class="form-control-static">
                                                {{ $product->category->name_en ?? 'No category' }}
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <p class="form-control-static">
                                                @if($product->deleted_at)
                                                    <span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill">Deleted</span>
                                                @else
                                                    <span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill">Active</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>English Description</label>
                                            <p class="form-control-static">{{ $product->description_en }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Arabic Description</label>
                                            <p class="form-control-static">{{ $product->description_ar }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Suppliers Section -->
                        <div class="kt-portlet kt-portlet--bordered mt-4">
                            <div class="kt-portlet__body">
                                <h4 class="kt-portlet__head-title">Suppliers</h4>
                                <div class="kt-separator kt-separator--space-sm"></div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($product->suppliers)
                                                @forelse ($product->suppliers as $supplier)
                                                <tr>
                                                    <td>{{ $supplier->name }}</td>
                                                    <td>{{ number_format($supplier->pivot->price, 2) }}</td>
                                                    <td>
                                                        @if($supplier->pivot->is_active)
                                                            <span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill">Active</span>
                                                        @else
                                                            <span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill">Inactive</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">No suppliers found</td>
                                                    </tr>
                                                @endforelse
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-center">Suppliers data not available</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection
