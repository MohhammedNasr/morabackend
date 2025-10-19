@extends('layouts.metronic.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="text-align:right;">@lang('cities.update')</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cities.update', $city->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name_ar">@lang('cities.name_ar')</label>
                    <input type="text" name="name_ar" id="name_ar" class="form-control"
                        value="{{ old('name_ar', $city->name_ar) }}" required>
                </div>
                <div class="form-group">
                    <label for="name_en">@lang('cities.name_en')</label>
                    <input type="text" name="name_en" id="name_en" class="form-control"
                        value="{{ old('name_en', $city->name_en) }}" required>
                </div>
                <div class="form-group">
                    <label for="code">@lang('cities.code')</label>
                    <input type="text" name="code" id="code" class="form-control"
                        value="{{ old('code', $city->code) }}" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">@lang('cities.update')</button>
                    @if (session('success'))
                        <div class="alert alert-success">
                            @lang('cities.update_success')
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            @lang('cities.update_error')
                        </div>
                    @endif
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-default">@lang('cities.cancel')</a>
                </div>
            </form>
        </div>
    </div>
@endsection
