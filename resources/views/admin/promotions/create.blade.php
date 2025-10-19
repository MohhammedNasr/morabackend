@extends('layouts.metronic.admin')

@section('content')
    <div class="kt-subheader kt-grid__item">
        <div class="kt-container">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    <a href="{{ route('admin.dashboard') }}">@lang('promotions.dashboard')</a>
                </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="{{ route('admin.dashboard') }}" class="kt-subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="{{ route('admin.promotions.index') }}" class="kt-subheader__breadcrumbs-link">
                        @lang('promotions.admin')
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="{{ route('admin.promotions.index') }}" class="kt-subheader__breadcrumbs-link">
                        @lang('promotions.promotions')
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="{{ route('admin.promotions.create') }}" class="kt-subheader__breadcrumbs-link">
                        @lang('promotions.create_title')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form method="POST" action="{{ route('admin.promotions.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="code">@lang('promotions.name')</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>

                    <div class="form-group">
                        <label for="description">@lang('promotions.description')</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="discount_type">@lang('promotions.discount_type')</label>
                        <select class="form-control" id="discount_type" name="discount_type" required>
                            <option value="percentage">@lang('promotions.percentage')</option>
                            <option value="fixed">@lang('promotions.fixed')</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="discount_value">@lang('promotions.discount')</label>
                        <input type="number" class="form-control" id="discount_value" name="discount_value" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="minimum_order_amount">@lang('promotions.minimum_order')</label>
                        <input type="number" class="form-control" id="minimum_order_amount" name="minimum_order_amount" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="maximum_discount_amount">@lang('promotions.maximum_discount')</label>
                        <input type="number" class="form-control" id="maximum_discount_amount" name="maximum_discount_amount" step="0.01">
                    </div>

                    <div class="form-group">
                        <label for="start_date">@lang('promotions.start_date')</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="end_date">@lang('promotions.end_date')</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                    </div>

                    <div class="form-group">
                        <label for="usage_limit">@lang('promotions.usage_limit')</label>
                        <input type="number" class="form-control" id="usage_limit" name="usage_limit">
                    </div>

                    <div class="form-group">
                        <label for="status">@lang('promotions.status')</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1">@lang('promotions.active')</option>
                            <option value="0">@lang('promotions.inactive')</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('promotions.create_button')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
