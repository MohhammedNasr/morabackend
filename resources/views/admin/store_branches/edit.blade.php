@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Edit Branch</h3>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Edit Branch Details
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <form method="POST" action="{{ route('admin.store_branches.update', ['store' => $store->id, 'store_branch' => $store_branch->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Branch Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $store_branch->name }}" required>
                    </div>

                    <div class="form-group">
                        <label>City</label>
                        <select class="form-control" name="city_id" required>
                            @foreach(\App\Models\City::all() as $city)
                                <option value="{{ $city->id }}" {{ $store_branch->city_id == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Area</label>
                        <select class="form-control" name="area_id" required>
                            @foreach(\App\Models\Area::where('city_id', $store_branch->city_id)->get() as $area)
                                <option value="{{ $area->id }}" {{ $store_branch->area_id == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Street Name</label>
                        <input type="text" class="form-control" name="street_name"
                               value="{{ $store_branch->street_name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{ $store_branch->phone }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="number" step="0.000001" class="form-control" name="latitude" value="{{ $store_branch->latitude }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Longitude</label>
                                <input type="number" step="0.000001" class="form-control" name="longitude" value="{{ $store_branch->longitude }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="kt-checkbox-inline">
                            <label class="kt-checkbox">
                                <input type="checkbox" name="is_main" value="1" {{ $store_branch->is_main ? 'checked' : '' }}> Main Branch
                                <span></span>
                            </label>
                            <label class="kt-checkbox">
                                <input type="checkbox" name="is_active" value="1" {{ $store_branch->is_active ? 'checked' : '' }}> Active
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">Update Branch</button>
                        <a href="{{ route('admin.store_branches.index', $store) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection
