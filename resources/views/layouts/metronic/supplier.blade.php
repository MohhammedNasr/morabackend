@extends('layouts.metronic.base')

@section('menu')
    <ul class="kt-menu__nav">
        <li class="kt-menu__item {{ request()->is('supplier') ? 'kt-menu__item--active' : '' }}" aria-haspopup="true">
            <a href="{{ route('supplier.dashboard') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon flaticon2-dashboard"></i>
                <span class="kt-menu__link-text">Dashboard</span>
            </a>
        </li>
        <li class="kt-menu__item {{ request()->is('supplier/products*') ? 'kt-menu__item--active' : '' }}" aria-haspopup="true">
            <a href="{{ route('supplier.products.index') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon flaticon2-box"></i>
                <span class="kt-menu__link-text">Products</span>
            </a>
        </li>
        <li class="kt-menu__item {{ request()->is('supplier/sub-orders*') ? 'kt-menu__item--active' : '' }}" aria-haspopup="true">
            <a href="{{ route('supplier.sub-orders.index') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon flaticon2-shopping-cart"></i>
                <span class="kt-menu__link-text">Sub Orders</span>
            </a>
        </li>
        <li class="kt-menu__item {{ request()->is('supplier/representatives*') ? 'kt-menu__item--active' : '' }}" aria-haspopup="true">
            <a href="{{ route('supplier.representatives.index') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon flaticon2-user"></i>
                <span class="kt-menu__link-text">Representatives</span>
            </a>
        </li>
        <li class="kt-menu__item {{ request()->is('supplier/profile*') ? 'kt-menu__item--active' : '' }}" aria-haspopup="true">
            <a href="{{ route('supplier.profile.edit') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon flaticon2-user-outline-symbol"></i>
                <span class="kt-menu__link-text">Profile</span>
            </a>
        </li>
    </ul>
@endsection

@push('styles')
    <!-- Supplier specific styles -->
    <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/css/demo1/pages/invoices/invoice-2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/custom/uppy/uppy.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('breadcrumbs')
    @if(request()->segment(2))
        <a href="{{ route('supplier.dashboard') }}" class="kt-subheader__breadcrumbs-link">
            Supplier
        </a>
        <span class="kt-subheader__breadcrumbs-separator"></span>
        @if(request()->segment(3))
            <a href="{{ route('supplier.' . request()->segment(2) . '.index') }}" class="kt-subheader__breadcrumbs-link">
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
            Supplier Dashboard
        </span>


    @endif
@endsection

@push('scripts')
    <!-- Supplier specific scripts -->
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/vendors/custom/uppy/uppy.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/file-upload/uppy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
@endpush
