@extends('layouts.metronic.admin')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="{{ app()->getLocale() === 'ar' ? 'text-align: right' : '' }}">@lang('messages.areas.table_headers.edit') @lang('messages.areas.title')</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.areas.update', $area->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name_ar">@lang('messages.areas.table_headers.name_ar')</label>
                <input type="text" name="name_ar" id="name_ar"
                       class="form-control" value="{{ old('name_ar', $area->name_ar) }}" required>
            </div>
            <div class="form-group">
                <label for="name_en">@lang('messages.areas.table_headers.name_en')</label>
                <input type="text" name="name_en" id="name_en"
                       class="form-control" value="{{ old('name_en', $area->name_en) }}" required>
            </div>
            <div class="form-group">
                <label for="code">@lang('messages.areas.table_headers.code')</label>
                <input type="text" name="code" id="code"
                       class="form-control" value="{{ old('code', $area->code) }}" required>
            </div>
            <div class="form-group">
                <label for="city_id">@lang('messages.areas.table_headers.city')</label>
                <select name="city_id" id="city_id" class="form-control" required>
                    <option value="">@lang('messages.areas.table_headers.select') @lang('messages.city')</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}"
                            {{ $city->id == old('city_id', $area->city_id) ? 'selected' : '' }}>
                            {{ $city->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">@lang('messages.areas.table_headers.update')</button>
                <a href="{{ route('admin.areas.index') }}" class="btn btn-default">@lang('messages.areas.table_headers.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
