<!-- begin:: Header -->
<div id="kt_header" class="kt-header kt-grid__item kt-header--fixed">
    <!-- begin:: Header Menu -->
    <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
        <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile kt-header-menu--layout-default">
            <ul class="kt-menu__nav">
                <li class="kt-menu__item kt-menu__item--active">
                    <a href="{{ route('dashboard') }}" class="kt-menu__link">
                        <span class="kt-menu__link-text">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- end:: Header Menu -->

    <!-- begin:: Header Topbar -->
    <div class="kt-header__topbar">
        <!-- begin: Language Switcher -->
        <div class="kt-header__topbar-item">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                <div class="kt-header__topbar-icon">
                    @if (app()->getLocale() === 'ar')
                        <img src="{{ asset('images/flags/sa.svg') }}" alt="Arabic" class="h-4 w-6">
                    @else
                        <img src="{{ asset('images/flags/gb.svg') }}" alt="English" class="h-4 w-6">
                    @endif
                </div>
            </div>
            <div
                class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround">
                <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                    <li class="kt-nav__item">
                        <a href="{{ route('language.switch', 'en') }}" class="kt-nav__link">
                            <span class="kt-nav__link-icon"><img src="{{ asset('images/flags/gb.svg') }}" alt="English"
                                    class="h-4 w-6"></span>
                            <span class="kt-nav__link-text">English</span>
                        </a>
                    </li>
                    <li class="kt-nav__item">
                        <a href="{{ route('language.switch', 'ar') }}" class="kt-nav__link">
                            <span class="kt-nav__link-icon"><img src="{{ asset('images/flags/sa.svg') }}" alt="Arabic"
                                    class="h-4 w-6"></span>
                            <span class="kt-nav__link-text">العربية</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end: Language Switcher -->

        <!-- begin: User bar -->
        <div class="kt-header__topbar-item kt-header__topbar-item--user">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                <div class="kt-header__topbar-user">
                    <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                    <span class="kt-header__topbar-username kt-hidden-mobile">{{ Auth::user()->name }}</span>
                    <img alt="Pic" src="{{ asset('metronic_theme/assets/media/users/default.jpg') }}" />
                </div>
            </div>
            <div
                class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x"
                    style="background-image: url({{ asset('metronic_theme/assets/media/misc/bg-1.jpg') }})">
                    <div class="kt-user-card__avatar">
                        <img alt="Pic" src="{{ asset('metronic_theme/assets/media/users/default.jpg') }}" />
                    </div>
                    <div class="kt-user-card__name">
                        {{ Auth::user()->name }}
                    </div>
                </div>
                <div class="kt-notification">
                    <a href="{{ route(Auth::user()->role->slug . '.profile.edit') }}" class="kt-notification__item">
                        <div class="kt-notification__item-icon">
                            <i class="flaticon2-calendar-3 kt-font-success"></i>
                        </div>
                        <div class="kt-notification__item-details">
                            <div class="kt-notification__item-title kt-font-bold">
                                My Profile
                            </div>
                            <div class="kt-notification__item-time">
                                Account settings and more
                            </div>
                        </div>
                    </a>
                    <div class="kt-notification__custom kt-space-between">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign
                                Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: User bar -->
    </div>
    <!-- end:: Header Topbar -->
</div>
<!-- end:: Header -->
