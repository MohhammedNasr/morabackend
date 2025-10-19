@extends('layouts.metronic.base')

@push('styles')
    <!-- Store specific styles -->
    <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/css/demo1/pages/invoices/invoice-1.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('breadcrumbs')
    @if(request()->segment(2))
        <a href="{{ route('store.dashboard') }}" class="kt-subheader__breadcrumbs-link">
            Store
        </a>
        <span class="kt-subheader__breadcrumbs-separator"></span>
        @if(request()->segment(3))
            <a href="{{ route('store.' . request()->segment(2) . '.index') }}" class="kt-subheader__breadcrumbs-link">
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
            Store Dashboard
        </span>
    @endif
@endsection

@push('scripts')
    <!-- Store specific scripts -->
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/components/calendar/basic.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
@endpush
