@extends('layouts.metronic.admin')

@section('content')
    <div class="card">
        <div class="card-header" @if (app()->getLocale() == 'ar') {{ 'style = text-align:right' }} @endif>
            <h3 class="card-title">@lang('messages.banners.title')</h3>
            <div class="card-tools">
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> @lang('messages.banners.buttons.add')
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                <thead>
                    <tr>
                        <th>@lang('messages.banners.table_headers.image')</th>
                        <th>@lang('messages.banners.table_headers.link')</th>
                        <th>@lang('messages.banners.table_headers.status')</th>
                        <th>@lang('messages.banners.table_headers.actions')</th>
                    </tr>
                </thead>
                <tbody @if (app()->getLocale() == 'ar') {{ 'style = text-align:right' }} @endif>
                    @foreach ($banners as $banner)
                        <tr>
                            <td>
                                <img src="{{ asset($banner->image) }}" alt="Banner" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                            </td>
                            <td>{{ $banner->link }}</td>
                            <td>
                                @if ($banner->is_active)
                                    <span class="badge badge-success">@lang('messages.banners.status.active')</span>
                                @else
                                    <span class="badge badge-danger">@lang('messages.banners.status.inactive')</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('@lang('messages.banners.messages.delete_confirm')')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
