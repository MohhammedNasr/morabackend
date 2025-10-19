@extends('layouts.metronic.store')

@section('title', __('profile.settings'))

@section('breadcrumbs')
    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
        @lang('profile.settings')
    </span>
@endsection

@section('content')
<div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                            @lang('profile.information')
                        </h3>
                    </div>
                </div>

                <form method="post" action="{{ route('store.profile.update') }}" class="kt-form">
                    @csrf
                    @method('put')

                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('profile.owner_name')</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required autofocus>
                                    @error('name')
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('profile.email')</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('profile.store_name')</label>
                                    <input type="text" name="store_name" class="form-control"
                                        value="{{ old('store_name', $store->name) }}" required>
                                    @error('store_name')
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('profile.phone')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+966</span>
                                        </div>
                                        <input type="text" class="form-control"
                                            value="{{ $store->phone }}" disabled>
                                    </div>
                                    <span class="form-text text-muted">@lang('profile.phone_cannot_change')</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('profile.commercial_record')</label>
                                    <input type="text" class="form-control"
                                        value="{{ $store->commercial_record }}" disabled>
                                    <span class="form-text text-muted">@lang('profile.commercial_cannot_change')</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('profile.tax_record')</label>
                                    <input type="text" name="tax_record" class="form-control"
                                        value="{{ old('tax_record', $store->tax_record) }}" required>
                                    @error('tax_record')
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('profile.credit_limit')</label>
                            <input type="text" class="form-control"
                                value="{{ number_format($store->credit_limit, 2) }} SAR" disabled>
                            <span class="form-text text-muted">@lang('profile.credit_limit_managed')</span>
                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="la la-check"></i> @lang('profile.save_changes')
                            </button>
                            @if (session('success'))
                                <span class="ml-3 text-success">
                                    <i class="la la-check-circle"></i> {{ session('success') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
