@extends('layouts.metronic.supplier')

@section('title', __('messages.representatives.view_title'))

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                @lang('messages.representatives.details_title')
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <a href="{{ route('supplier.representatives.edit', $representative) }}" class="btn btn-sm btn-brand">
                <i class="la la-edit"></i> @lang('messages.edit')
            </a>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-section">
            <div class="kt-section__content">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">@lang('messages.representatives.table_headers.name')</th>
                        <td>{{ $representative->name }}</td>
                    </tr>
                    <tr>
                        <th>@lang('messages.representatives.table_headers.email')</th>
                        <td>{{ $representative->email }}</td>
                    </tr>
                    <tr>
                        <th>@lang('messages.representatives.table_headers.phone')</th>
                        <td>{{ $representative->phone }}</td>
                    </tr>
                    <tr>
                        <th>@lang('messages.representatives.table_headers.status')</th>
                        <td>
                            <span class="kt-badge kt-badge--{{ $representative->is_active ? 'success' : 'danger' }} kt-badge--inline">
                                @lang($representative->is_active ? 'messages.representatives.status.active' : 'messages.representatives.status.inactive')
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('messages.created_at')</th>
                        <td>{{ $representative->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>@lang('messages.updated_at')</th>
                        <td>{{ $representative->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
