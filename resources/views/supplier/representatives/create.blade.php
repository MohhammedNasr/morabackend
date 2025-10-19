@extends('layouts.metronic.supplier')

@section('title', __('messages.representatives.create_title'))

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                @lang('messages.representatives.create_title')
            </h3>
        </div>
    </div>
    <form action="{{ route('supplier.representatives.store') }}" method="POST">
        @csrf
        <div class="kt-portlet__body">
            <div class="form-group">
                <label>@lang('messages.representatives.table_headers.name')</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>@lang('messages.representatives.table_headers.email')</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>@lang('messages.representatives.table_headers.phone')</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label>@lang('messages.password')</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>@lang('messages.representatives.table_headers.status')</label>
                <select name="is_active" class="form-control">
                    <option value="1">@lang('messages.representatives.status.active')</option>
                    <option value="0">@lang('messages.representatives.status.inactive')</option>
                </select>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <button type="submit" class="btn btn-primary">@lang('messages.submit')</button>
            <a href="{{ route('supplier.representatives.index') }}" class="btn btn-secondary">@lang('messages.cancel')</a>
        </div>
    </form>
</div>
@endsection
