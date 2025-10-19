@extends('layouts.metronic.store')
@section('content')
    <div class="container">
        <h1>@lang('messages.branches.create')</h1>
        <form action="{{ route('store.branches.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">@lang('messages.branches.table_headers.name')</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">@lang('messages.branches.address')</label>
                <input type="text" name="address" id="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">@lang('messages.branches.table_headers.phone')</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="street_name">@lang('messages.branches.table_headers.street')</label>
                <input type="text" name="street_name" id="street_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="latitude">@lang('messages.branches.latitude')</label>
                <input type="text" name="latitude" id="latitude" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="longitude">@lang('messages.branches.longitude')</label>
                <input type="text" name="longitude" id="longitude" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="is_main">@lang('messages.branches.table_headers.main_branch')</label>
                <input type="checkbox" name="is_main" id="is_main" class="form-control">
            </div>
            <div class="form-group">
                <label for="is_active">@lang('messages.branches.table_headers.status')</label>
                <input type="checkbox" name="is_active" id="is_active" class="form-control">
            </div>
            <div class="form-group">
                <label for="building_number">@lang('messages.branches.table_headers.building')</label>
                <input type="text" name="building_number" id="building_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="floor_number">@lang('messages.branches.table_headers.floor')</label>
                <input type="text" name="floor_number" id="floor_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="city_id">@lang('messages.branches.table_headers.city')</label>
                <select name="city_id" id="city_id" class="form-control" required>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->{'name_' . app()->getLocale()} }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="area_id">@lang('messages.branches.table_headers.area')</label>
                <select name="area_id" id="area_id" class="form-control" required>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->{'name_' . app()->getLocale()}  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="notes">@lang('messages.branches.notes')</label>
                <textarea name="notes" id="notes" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">@lang('messages.branches.create')</button>
        </form>
    </div>
@endsection
