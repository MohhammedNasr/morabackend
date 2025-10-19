@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.promotions.title')</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="{{ route('admin.promotions.index') }}" class="kt-subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="{{ route('admin.promotions.index') }}" class="kt-subheader__breadcrumbs-link">@lang('messages.promotions.title')</a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">@lang('common.edit')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">@lang('messages.promotions.edit_promotion')</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <form method="POST" action="{{ route('admin.promotions.destroy', $promotion->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('@lang('common.delete_confirmation')')">
                                    <i class="la la-trash"></i> @lang('common.delete')
                                </button>
                            </form>
                        </div>
                    </div>

                    <form class="kt-form" method="POST" action="{{ route('admin.promotions.update', $promotion->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>@lang('messages.promotions.table_headers.code') *</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                               name="code" value="{{ old('code', $promotion->code) }}" required>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>@lang('messages.promotions.table_headers.discount_type') *</label>
                                        <select class="form-control @error('discount_type') is-invalid @enderror" name="discount_type" required>
                                            <option value="percentage" {{ old('discount_type', $promotion->discount_type) === 'percentage' ? 'selected' : '' }}>
                                                @lang('messages.promotions.percentage')
                                            </option>
                                            <option value="fixed" {{ old('discount_type', $promotion->discount_type) === 'fixed' ? 'selected' : '' }}>
                                                @lang('messages.promotions.fixed')
                                            </option>
                                        </select>
                                        @error('discount_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>@lang('messages.promotions.table_headers.discount') *</label>
                                        <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror"
                                               name="discount_value" value="{{ old('discount_value', $promotion->discount_value) }}" required>
                                        @error('discount_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>@lang('messages.promotions.table_headers.min_order')</label>
                                        <input type="number" step="0.01" class="form-control @error('minimum_order_amount') is-invalid @enderror"
                                               name="minimum_order_amount" value="{{ old('minimum_order_amount', $promotion->minimum_order_amount) }}">
                                        @error('minimum_order_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>@lang('messages.promotions.table_headers.max_discount')</label>
                                        <input type="number" step="0.01" class="form-control @error('maximum_discount_amount') is-invalid @enderror"
                                               name="maximum_discount_amount" value="{{ old('maximum_discount_amount', $promotion->maximum_discount_amount) }}">
                                        @error('maximum_discount_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>@lang('messages.promotions.table_headers.usage_limit')</label>
                                        <input type="number" class="form-control @error('usage_limit') is-invalid @enderror"
                                               name="usage_limit" value="{{ old('usage_limit', $promotion->usage_limit) }}">
                                        @error('usage_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>@lang('messages.promotions.table_headers.start_date') *</label>
                                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror"
                                               name="start_date" value="{{ old('start_date', $promotion->start_date->format('Y-m-d\TH:i')) }}" required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>@lang('messages.promotions.table_headers.end_date') *</label>
                                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                               name="end_date" value="{{ old('end_date', $promotion->end_date->format('Y-m-d\TH:i')) }}" required>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label>@lang('messages.promotions.table_headers.description')</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  name="description" rows="3">{{ old('description', $promotion->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.promotions.table_headers.status') *</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="active" {{ old('status', $promotion->status) === 'active' ? 'selected' : '' }}>
                                        @lang('messages.promotions.filters.active')
                                    </option>
                                    <option value="inactive" {{ old('status', $promotion->status) === 'inactive' ? 'selected' : '' }}>
                                        @lang('messages.promotions.filters.inactive')
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success">@lang('common.update')</button>
                                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">@lang('common.cancel')</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection
