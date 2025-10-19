@extends('layouts.metronic.supplier')

@section('title', __('messages.representatives.edit_title'))

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                @lang('messages.representatives.edit_title')
            </h3>
        </div>
    </div>
    <form action="{{ route('supplier.representatives.update', $representative) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="kt-portlet__body">
            <div class="form-group">
                <label>@lang('messages.representatives.table_headers.name')</label>
                <input type="text" name="name" class="form-control" value="{{ $representative->name }}" required>
            </div>
            <div class="form-group">
                <label>@lang('messages.representatives.table_headers.email')</label>
                <input type="email" name="email" class="form-control" value="{{ $representative->email }}" required>
            </div>
            <div class="form-group">
                <label>@lang('messages.representatives.table_headers.phone')</label>
                <input type="text" name="phone" class="form-control" value="{{ $representative->phone }}" required>
            </div>
            <div class="form-group">
                <label>@lang('messages.representatives.password_hint')</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>@lang('messages.representatives.table_headers.status')</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $representative->is_active ? 'selected' : '' }}>@lang('messages.representatives.status.active')</option>
                    <option value="0" {{ !$representative->is_active ? 'selected' : '' }}>@lang('messages.representatives.status.inactive')</option>
                </select>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <a href="{{ route('supplier.representatives.index') }}" class="btn btn-secondary">@lang('messages.cancel')</a>
        </div>
    </form>
</div>
@endsection
