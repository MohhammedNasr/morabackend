{{-- @php
  app()->setLocale('ar');
@endphp --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
    class="{{ app()->getLocale() == 'ar' ? 'kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading' : '' }}">

<!-- begin::Head -->

<head>
    {{-- {{ app()->getLocale() }} --}}
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }} | @yield('title', __('common.dashboard'))</title>
    <meta name="description" content="{{ __('common.dashboard') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const locale = typeof app !== 'undefined' && app().getLocale ? app().getLocale() : 'en';
                const fontFamilies = locale === 'ar' ? ["Tajawal:300,400,500,600,700",
                    "Poppins:300,400,500,600,700"
                ] : ["Poppins:300,400,500,600,700",
                    "Roboto:300,400,500,600,700"
                ];

                if (typeof WebFont !== 'undefined') {
                    WebFont.load({
                        google: {
                            families: fontFamilies
                        },
                        active: function() {
                            if (typeof sessionStorage !== 'undefined') {
                                sessionStorage.fonts = true;
                            }
                        }
                    });
                }
            } catch (e) {
                console.error('WebFont loading error:', e);
            }
        });
    </script>
    <!--end::Fonts -->

    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('metronic_theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') }}"
        rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles -->

    <!--begin:: Global Mandatory Vendors -->
    <link href="{{ asset('metronic_theme/assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css') }}"
        rel="stylesheet" type="text/css" />
    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <link href="{{ asset('metronic_theme/assets/vendors/general/tether/dist/css/tether.css') }}" rel="stylesheet"
        type="text/css" />
    <link
        href="{{ asset('metronic_theme/assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{ asset('metronic_theme/assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css') }}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{ asset('metronic_theme/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css') }}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{ asset('metronic_theme/assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/select2/dist/css/select2.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/nouislider/distribute/nouislider.css') }}"
        rel="stylesheet" type="text/css" />
    <link href rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/dropzone/dist/dropzone.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/summernote/dist/summernote.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/animate.css/animate.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/morris.js/morris.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/sweetalert2/dist/sweetalert2.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/socicon/css/socicon.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/custom/vendors/line-awesome/css/line-awesome.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/custom/vendors/flaticon/flaticon.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/custom/vendors/flaticon2/flaticon.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic_theme/assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css') }}"
        rel="stylesheet" type="text/css" />
    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Styles(used by all pages) -->
    @if (app()->getLocale() == 'ar')
        <link href="{{ asset('css_ar/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{ asset('metronic_theme/assets/css/demo1/style.bundle.css') }}" rel="stylesheet"
            type="text/css" />
    @endif
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    @if (app()->getLocale() == 'ar')
        <link href="{{ asset('css_ar/skins/header/base/light.rtl.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css_ar/skins/header/menu/light.rtl.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css_ar/skins/brand/dark.rtl.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css_ar/skins/aside/dark.rtl.css') }}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{ asset('metronic_theme/assets/css/demo1/skins/header/base/light.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('metronic_theme/assets/css/demo1/skins/header/menu/light.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('metronic_theme/assets/css/demo1/skins/brand/dark.css') }}" rel="stylesheet"
            type="text/css" />
        <link href="{{ asset('metronic_theme/assets/css/demo1/skins/aside/dark.css') }}" rel="stylesheet"
            type="text/css" />
    @endif
    <!--end::Layout Skins -->

    <!-- Language Switcher Styles -->
    <link href="{{ asset('css/language-switcher.css') }}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ asset('metronic_theme/assets/media/logos/favicon.ico') }}" />

    @stack('styles')
</head>
<!-- end::Head -->

<!-- begin::Body -->

<body
    class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading {{ app()->getLocale() === 'ar' ? 'rtl text-right' : '' }}">

    <!-- begin:: Page -->

    <!-- begin:: Header Mobile -->
    <div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed">
        <div class="kt-header-mobile__logo">
            <a href="{{ url('/') }}">
                <img alt="Logo" src="{{ asset('images/brand/mora.png') }}" style="height:100px;weight:100px;" />
            </a>
        </div>

        <div class="kt-header-mobile__toolbar">
            <!-- Language Switcher Mobile -->
            <div class="kt-header-mobile__toolbar-wrapper">
                <div class="dropdown">
                    <button class="btn btn-icon btn-clean btn-lg btn-dropdown" type="button" data-toggle="dropdown">
                        @if (app()->getLocale() === 'ar')
                            <img src="{{ asset('images/flags/sa.svg') }}" alt="Arabic" class="h-4 w-6">
                        @else
                            <img src="{{ asset('images/flags/gb.svg') }}" alt="English" class="h-4 w-6">
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">

                        <a href="{{ route('language.switch', 'en') }}" class="dropdown-item">
                            {{-- <img src="{{ asset('images/flags/gb.svg') }}" alt="English" class="h-4 w-6 me-2"> --}}
                            <span>English</span>
                        </a>
                        <a href="{{ route('language.switch', 'ar') }}" class="dropdown-item">
                            {{-- <img src="{{ asset('images/flags/sa.svg') }}" alt="Arabic" class="h-4 w-6 me-2"> --}}
                            <span>العربية</span>
                        </a>
                    </div>
                </div>
            </div>
            <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left"
                id="kt_aside_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i
                    class="flaticon-more"></i></button>
        </div>
    </div>
    <!-- end:: Header Mobile -->

    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

            <!-- begin:: Aside -->
            <button class="kt-aside-close" id="kt_aside_close_btn"><i class="la la-close"></i></button>
            <div class="kt-aside kt-aside--fixed kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop"
                id="kt_aside">

                <!-- begin:: Aside -->
                <div class="kt-aside__brand kt-grid__item" id="kt_aside_brand">
                    <div class="kt-aside__brand-logo">
                        <a href="{{ url('/') }}">
                            <img alt="Logo" src="{{ asset('images/brand/mora.png') }}"
                                style="height:100px;weight:100px;" />
                        </a>
                    </div>
                    <div class="kt-aside__brand-tools">
                        <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1"
                                    class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                                            id="Path-94" fill="#000000" fill-rule="nonzero"
                                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                                        <path
                                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                                            id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                                    </g>
                                </svg>
                            </span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1"
                                    class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                            id="Path-94" fill="#000000" fill-rule="nonzero" />
                                        <path
                                            d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                            id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                            transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999)" />
                                    </g>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
                <!-- end:: Aside -->

                <!-- begin:: Aside Menu -->
                <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
                    <div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1"
                        data-ktmenu-dropdown-timeout="500">
                        <ul class="kt-menu__nav">
                            <li class="kt-menu__item kt-menu__item--active" aria-haspopup="true">
                                <a href="{{ url('/dashboard') }}" class="kt-menu__link">
                                    <span class="kt-menu__link-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon id="Bound" points="0 0 24 0 24 24 0 24" />
                                                <path
                                                    d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z"
                                                    id="Shape" fill="#000000" fill-rule="nonzero" />
                                                <path
                                                    d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z"
                                                    id="Path" fill="#000000" opacity="0.3" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="kt-menu__link-text">@lang('messages.Dashboard')</span>
                                </a>
                            </li>

                            <!-- Admin Menu Items -->
                            @if (auth()->check() && auth()->user()->role->slug === 'admin')
                                <li class="kt-menu__section">
                                    <h4 class="kt-menu__section-text">@lang('messages.Admin Management')</h4>
                                    <i class="kt-menu__section-icon flaticon-more-v2"></i>
                                </li>
                                <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true"
                                    data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon">
                                            <i class="flaticon2-user-1"></i>
                                        </span>
                                        <span class="kt-menu__link-text">@lang('messages.Users')</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>


                                    <div class="kt-menu__submenu">
                                        <span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">

                                            {{-- <li class="kt-menu__item" aria-haspopup="true">
                                                <a href="{{ route('admin.roles.index') }}" class="kt-menu__link">
                                                    <i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Roles</span>
                                                </a> --}}
                                </li>
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.users.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.Admins')</span>
                                    </a>
                                </li>

                        </ul>
                    </div>
                    </li>
                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon">

                                <i class="flaticon2-list-2"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Stores')</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>

                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                {{-- <li class="kt-menu__item" aria-haspopup="true">
                                                <a href="{{ route('admin.store_users.index') }}"
                                                    class="kt-menu__link">
                                                    <i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Store Users</span>
                                                </a>
                                            </li> --}}
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.stores.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.All Stores')</span>
                                    </a>
                                </li>
                                {{-- <li class="kt-menu__item" aria-haspopup="true">
                                                <a href="{{ route('admin.store_branches.index') }}"
                                                    class="kt-menu__link">
                                                    <i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Store Branches</span>
                                                </a>
                                            </li> --}}
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon">

                                <i class="flaticon2-list-2"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Suppliers')</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.suppliers.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.All Suppliers')</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon">
                                <i class="flaticon2-box-1"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Categories')</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                {{-- <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.products.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.All Products')</span>
                                    </a>
                                </li> --}}
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.categories.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.Categories')</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon">
                                {{-- <i class="flaticon2-sale-1"></i> --}}
                                <i class="flaticon2-list-2"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Promotions')</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.promotions.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.Promotions')</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon">
                                <i class="flaticon2-list-2"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Orders')</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.orders.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.All Orders')</span>
                                    </a>
                                </li>
                                {{-- <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.sub_orders.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.Sub Orders')</span>
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon">
                                {{-- <i class="flaticon2-wallet-1"></i> --}}
                                <i class="flaticon2-list-2"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Finance')</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.wallets.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.Wallets')</span>
                                    </a>
                                </li>
                                {{-- <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.wallet_transactions.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.transactions')</span>
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon">
                                <i class="flaticon2-user"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Representatives')</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('admin.representatives.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">@lang('messages.All Representatives')</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item" aria-haspopup="true">
                        <a href="{{ route('admin.cities.index') }}" class="kt-menu__link">
                            <span class="kt-menu__link-icon">
                                <i class="flaticon2-map"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Cities')</span>
                        </a>
                    </li>

                    <li class="kt-menu__item" aria-haspopup="true">
                        <a href="{{ route('admin.areas.index') }}" class="kt-menu__link">
                            <span class="kt-menu__link-icon">
                                {{-- <i class="flaticon2-map-1"></i> --}}
                                <i class="flaticon2-map"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Areas')</span>
                        </a>
                    </li>

                    <li class="kt-menu__item" aria-haspopup="true">
                        <a href="{{ route('admin.banners.index') }}" class="kt-menu__link">
                            <span class="kt-menu__link-icon">
                                <i class="flaticon2-image-file"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Banners')</span>
                        </a>
                    </li>
                    <li class="kt-menu__item" aria-haspopup="true">
                        <a href="{{ route('admin.settings.edit') }}" class="kt-menu__link">
                            <span class="kt-menu__link-icon">
                                <i class="flaticon2-gear"></i>
                            </span>
                            <span class="kt-menu__link-text">@lang('messages.Settings')</span>
                        </a>
                    </li>
                    @endif

                    <!-- Store Owner Menu Items -->
                    {{-- @if (auth()->check() && auth()->user()->role->slug === 'store-owner')
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true"
                            data-ktmenu-submenu-toggle="hover">
                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon">
                                    <i class="flaticon2-box"></i>
                                </span>
                                <span class="kt-menu__link-text">Products</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="kt-menu__submenu">
                                <span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item" aria-haspopup="true">
                                        <a href="{{ route('admin.products.index') }}" class="kt-menu__link">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Store Managment</span>
                                        </a>
                                    </li>
                                    <li class="kt-menu__item" aria-haspopup="true">
                                        <a href="{{ route('admin.categories.index') }}" class="kt-menu__link">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Categories</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif --}}

                    @if (auth()->check() && auth()->user()->role->slug === 'store-owner')
                        @include('store.partials.store_menu')
                    @endif

                    <!-- Supplier Menu Items -->
                    @if (auth()->user()->role->slug == 'supplier')
                        <li class="kt-menu__section">
                            <h4 class="kt-menu__section-text">@lang('messages.suppliers.title')</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        @include('supplier.partials.representatives_menu')

                        {{-- <li class="kt-menu__item" aria-haspopup="true">
                                    <a href="{{ route('supplier.wallet') }}" class="kt-menu__link">
                                        <span class="kt-menu__link-icon">
                                            <i class="flaticon2-wallet"></i>
                                        </span>
                                        <span class="kt-menu__link-text">Wallet</span>
                                    </a>
                                </li> --}}
                    @endif
                    </ul>
                </div>
            </div>
            <!-- end:: Aside Menu -->
        </div>
        <!-- end:: Aside -->

        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

            <!-- begin:: Header -->
            <div id="kt_header" class="kt-header kt-grid__item kt-header--fixed">

                <!-- begin:: Header Menu -->
                <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i
                        class="la la-close"></i></button>
                <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
                    <div id="kt_header_menu"
                        class="kt-header-menu kt-header-menu-mobile kt-header-menu--layout-default">
                        <ul class="kt-menu__nav">
                            @php
                                $currentRoute = request()->route()->getName();
                                $menuItems = [
                                    'dashboard' => [
                                        'route' => 'dashboard',
                                        'text' => __('messages.Dashboard'),
                                        'icon' => 'flaticon2-analytics-2',
                                    ],
                                    'products' => [
                                        'route' => 'products.index',
                                        'text' => '@lang(\'messages.Products\')',
                                        'icon' => 'flaticon2-box',
                                    ],
                                    'orders' => [
                                        'route' => 'orders.index',
                                        'text' => '@lang(\'messages.Orders\')',
                                        'icon' => 'flaticon2-list-1',
                                    ],
                                    'reports' => [
                                        'route' => 'reports.index',
                                        'text' => '@lang(\'messages.Reports\')',
                                        'icon' => 'flaticon2-graph',
                                    ],
                                ];
                            @endphp

                            @foreach ($menuItems as $key => $item)
                                @if (Route::has($item['route']))
                                    <li
                                        class="kt-menu__item @if (str_starts_with($currentRoute, $key)) kt-menu__item--active @endif">
                                        <a href="{{ route($item['route']) }}" class="kt-menu__link">
                                            <span class="kt-menu__link-icon">
                                                <i class="{{ $item['icon'] }}"></i>
                                            </span>
                                            <span class="kt-menu__link-text">{{ $item['text'] }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- end:: Header Menu -->

                <!-- begin:: Header Topbar -->
                <div class="kt-header__topbar">
                    <!-- Language Switcher -->
                    <div class="kt-header__topbar-item kt-header__topbar-item--langs">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                            <span class="kt-header__topbar-icon">
                                @if (app()->getLocale() === 'ar')
                                    <img src="{{ asset('images/flags/sa.svg') }}" alt="Arabic" class="h-4 w-6">
                                @else
                                    <img src="{{ asset('images/flags/gb.svg') }}" alt="English" class="h-4 w-6">
                                @endif
                            </span>
                        </div>
                        <div
                            class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround">
                            <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                                <li class="kt-nav__item">
                                    <a href="{{ route('language.switch', 'en') }}" class="kt-nav__link">
                                        <span class="kt-nav__link-icon">
                                            <img src="{{ asset('images/flags/gb.svg') }}" alt="English"
                                                class="h-4 w-6">
                                        </span>
                                        <span class="kt-nav__link-text">English</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="{{ route('language.switch', 'ar') }}" class="kt-nav__link">
                                        <span class="kt-nav__link-icon">
                                            <img src="{{ asset('images/flags/sa.svg') }}" alt="Arabic"
                                                class="h-4 w-6">
                                        </span>
                                        <span class="kt-nav__link-text">العربية</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--end: Search -->

                    <!--begin: Notifications -->
                    {{-- <div class="kt-header__topbar-item dropdown">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px">
                            <span class="kt-header__topbar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1"
                                    class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect id="bound" x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                                            id="Combined-Shape" fill="#000000" opacity="0.3" />
                                        <path
                                            d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                                            id="Combined-Shape" fill="#000000" />
                                    </g>
                                </svg>
                            </span>
                            <span
                                class="kt-header__topbar-badge kt-badge kt-badge--dot kt-badge--notify kt-badge--sm"></span>
                        </div>
                        <div
                            class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg">
                            <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true"
                                data-height="300" data-mobile-height="200">
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-shopping-cart kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            @lang('messages.notifications.new_order')
                                        </div>
                                        <div class="kt-notification__item-time">
                                            @lang('messages.notifications.time_ago', ['time' => '2 hrs'])
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-box-1 kt-font-brand"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            @lang('messages.notifications.new_customer')
                                        </div>
                                        <div class="kt-notification__item-time">
                                            @lang('messages.notifications.time_ago', ['time' => '3 hrs'])
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div> --}}
                    <!--end: Notifications -->

                    <!--begin: User Bar -->
                    <div class="kt-header__topbar-item kt-header__topbar-item--user">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                            <div class="kt-header__topbar-user">
                                <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                                <span
                                    class="kt-header__topbar-username kt-hidden-mobile">{{ auth()->check() ? auth()->user()->name : 'User' }}</span>
                                <img class="kt-hidden" alt="Pic"
                                    src="{{ asset('metronic_theme/assets/media/users/300_25.jpg') }}" />
                                <span
                                    class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">{{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}</span>
                            </div>
                        </div>
                        <div
                            class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                            <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x"
                                style="background-image: url({{ asset('metronic_theme/assets/media/misc/bg-1.jpg') }})">
                                <div class="kt-user-card__avatar">
                                    <img class="kt-hidden" alt="Pic"
                                        src="{{ asset('metronic_theme/assets/media/users/300_25.jpg') }}" />
                                    <span
                                        class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="kt-user-card__name">
                                    {{ auth()->user()->name ?? 'User' }}
                                </div>
                            </div>
                            <div class="kt-notification">
                                @php
                                    $role = Auth::user()->role->slug;
                                    if (Auth::user()->role->slug == 'store-owner') {
                                        $role = 'store';
                                    }
                                @endphp
                                <a href="{{ route($role . '.profile.edit') }}" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-calendar-3 kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">
                                            @lang('messages.profile.my_profile')
                                        </div>
                                        <div class="kt-notification__item-time">
                                            @lang('messages.profile.account_settings')
                                        </div>
                                    </div>
                                </a>
                                @php
                                    $role = Auth::user()->role->slug;
                                @endphp
                                @if ($role == 'admin' || $role == 'store-owner')
                                    <div class="kt-notification__custom kt-space-between">
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                            class="btn btn-label btn-label-brand btn-sm btn-bold">@lang('messages.profile.sign_out')</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                @elseif($role == 'supplier')

                                    <div class="kt-notification__custom kt-space-between">
                                        @if(auth()->guard('supplier-web')->check())
                                        <form method="POST" action="{{ route('supplier.logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-label btn-label-brand btn-sm btn-bold">
                                                @lang('messages.profile.sign_out')
                                            </button>
                                        </form>
                                        @else
                                        <a href="{{ route('supplier.logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form2').submit();"
                                            class="btn btn-label btn-label-brand btn-sm btn-bold">@lang('messages.profile.sign_out')</a>
                                        @endif
                                        <form id="logout-form2" action="{{ route('supplier.logout') }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--end: User Bar -->
                </div>
                <!-- end:: Header Topbar -->
            </div>
            <!-- end:: Header -->

            <!-- begin:: Content -->
            <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                <!-- begin:: Subheader -->
                <div class="kt-subheader kt-grid__item" id="kt_subheader">
                    <div class="kt-container kt-container--fluid">
                        <div class="kt-subheader__main">
                            <h3 class="kt-subheader__title">@yield('title', __('common.dashboard'))</h3>
                            <div class="kt-subheader__breadcrumbs">
                                <a href="{{ url('/') }}" class="kt-subheader__breadcrumbs-home"><i
                                        class="flaticon2-shelter"></i></a>
                                <span class="kt-subheader__breadcrumbs-separator"></span>
                                @yield('breadcrumbs')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end:: Subheader -->

                <!-- begin:: Content Body -->
                <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
                    @yield('content')
                </div>
                <!-- end:: Content Body -->
            </div>
            <!-- end:: Content -->

            <!-- begin:: Footer -->
            <div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
                <div class="kt-container kt-container--fluid">
                    <div class="kt-footer__copyright">
                        {{ date('Y') }}&nbsp;&copy;&nbsp;<a href="{{ url('/') }}"
                            class="kt-link">{{ config('app.name', 'Laravel') }}</a>
                    </div>
                    <div class="kt-footer__menu">
                        <a href="#" class="kt-footer__menu-link kt-link">@lang('messages.About')</a>
                        <a href="#" class="kt-footer__menu-link kt-link">@lang('messages.Team')</a>
                        <a href="#" class="kt-footer__menu-link kt-link">@lang('messages.Contact')</a>
                    </div>
                </div>
            </div>
            <!-- end:: Footer -->
        </div>
    </div>
    </div>
    <!-- end:: Page -->

    <!-- begin::Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>
    <!-- end::Scrolltop -->

    <!-- begin::Global Config(global config for global JS scripts) -->
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };
    </script>
    <!-- end::Global Config -->

    <!--begin:: Global Mandatory Vendors -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet"
        type="text/css" />
    <script src="{{ asset('metronic_theme/assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript">
    </script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/popper.js/dist/umd/popper.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/js-cookie/src/js.cookie.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/moment/min/moment.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/sticky-js/dist/sticky.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/wnumb/wNumb.js') }}" type="text/javascript"></script>
    <!--end:: Global Mandatory Vendors -->

    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="{{ asset('metronic_theme/assets/js/demo1/scripts.bundle.js') }}" type="text/javascript"></script>
    <!--end::Global Theme Bundle -->

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ asset('metronic_theme/assets/js/demo1/pages/dashboard.js') }}" type="text/javascript"></script>
    <!--end::Page Scripts -->

    @stack('scripts')
</body>
<!-- end::Body -->

</html>
