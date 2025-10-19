@extends('layouts.metronic.base')

@push('styles')
    <!-- Admin specific styles -->
    <link href="{{ asset('metronic_theme/assets/css/demo1/pages/general/wizard/wizard-1.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('breadcrumbs')
    @if(request()->segment(2))
        <a href="{{ route('admin.dashboard') }}" class="kt-subheader__breadcrumbs-link">
            @lang('messages.admin.breadcrumbs.admin')
        </a>
        <span class="kt-subheader__breadcrumbs-separator"></span>
        @if(request()->segment(3))
            <a href="{{ route('admin.' . request()->segment(2) . '.index') }}" class="kt-subheader__breadcrumbs-link">
                {{ ucfirst(request()->segment(2)) }}
            </a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
                {{ ucfirst(request()->segment(3)) }}
            </span>
        @else
            <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
                {{ ucfirst(request()->segment(2)) }}
            </span>
        @endif
    @else
        <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">
            @lang('messages.admin.breadcrumbs.dashboard')
        </span>
    @endif
@endsection

@push('scripts')
    <!-- Admin specific scripts -->
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
@endpush
