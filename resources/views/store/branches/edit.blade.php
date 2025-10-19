@extends('layouts.metronic.store')

@section('title', __('branches.edit_title'))
@php
    app()->setLocale('ar');
@endphp
@section('breadcrumbs')
    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
        @lang('branches.edit_title')
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
                            @lang('branches.edit_title')
                        </h3>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('store.branches.update', $storeBranch->id) }}" class="kt-form">
                        @csrf
                        @method('PUT')

                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.name')</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $storeBranch->name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.address')</label>
                                        <input type="text" name="address" class="form-control"
                                            value="{{ $storeBranch->address }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.phone')</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ $storeBranch->phone }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.street_name')</label>
                                        <input type="text" name="street_name" class="form-control"
                                            value="{{ $storeBranch->street_name }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.latitude')</label>
                                        <input type="text" name="latitude" class="form-control"
                                            value="{{ $storeBranch->latitude }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.longitude')</label>
                                        <input type="text" name="longitude" class="form-control"
                                            value="{{ $storeBranch->longitude }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.is_main')</label>
                                        <div class="kt-checkbox-inline">
                                            <label class="kt-checkbox">
                                                <input type="checkbox" name="is_main"
                                                    {{ $storeBranch->is_main ? 'checked' : '' }}>
                                                @lang('branches.main_branch.yes')
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.is_active')</label>
                                        <div class="kt-checkbox-inline">
                                            <label class="kt-checkbox">
                                                <input type="checkbox" name="is_active"
                                                    {{ $storeBranch->is_active ? 'checked' : '' }}>
                                                @lang('branches.main_branch.yes')
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.building_number')</label>
                                        <input type="text" name="building_number" class="form-control"
                                            value="{{ $storeBranch->building_number }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.floor_number')</label>
                                        <input type="text" name="floor_number" class="form-control"
                                            value="{{ $storeBranch->floor_number }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.city')</label>
                                        <select name="city_id" class="form-control" required>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ $storeBranch->city_id == $city->id ? 'selected' : '' }}>
                                                    {{ $city->{'name_' . app()->getLocale()} }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('branches.area')</label>
                                        <select name="area_id" class="form-control" required>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    {{ $storeBranch->area_id == $area->id ? 'selected' : '' }}>
                                                    {{ $area->{'name_' . app()->getLocale()}  }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>@lang('branches.notes')</label>
                                <textarea name="notes" class="form-control">{{ $storeBranch->notes }}</textarea>
                            </div>
                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="la la-check"></i> @lang('branches.update')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endsection
