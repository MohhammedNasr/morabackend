@if (auth()->guard('supplier-web')->user())
    <li class="kt-menu__item @if(Request::is('supplier/dashboard')) kt-menu__item--active @endif" aria-haspopup="true">
        <a href="{{ route('supplier.dashboard') }}" class="kt-menu__link">
            <i class="kt-menu__link-icon flaticon2-analytics-2"></i>
            <span class="kt-menu__link-text">@lang('messages.supplier_dashboard.navigation.dashboard')</span>
        </a>
    </li>
    <li class="kt-menu__item @if(Request::is('supplier/products*')) kt-menu__item--active @endif" aria-haspopup="true">
        <a href="{{ route('supplier.products.index') }}" class="kt-menu__link">
            <i class="kt-menu__link-icon flaticon2-box"></i>
            <span class="kt-menu__link-text">@lang('messages.supplier_dashboard.navigation.products')</span>
        </a>
    </li>
    <li class="kt-menu__item @if(Request::is('supplier/orders*')) kt-menu__item--active @endif" aria-haspopup="true">
        <a href="{{ route('supplier.orders.index') }}" class="kt-menu__link">
            <i class="kt-menu__link-icon flaticon2-list-1"></i>
            <span class="kt-menu__link-text">@lang('messages.supplier_dashboard.navigation.orders')</span>
        </a>
    </li>
    {{-- <li class="kt-menu__item @if(Request::is('supplier/sub-orders*')) kt-menu__item--active @endif" aria-haspopup="true">
        <a href="{{ route('supplier.sub-orders.index') }}" class="kt-menu__link">
            <i class="kt-menu__link-icon flaticon2-list-3"></i>
            <span class="kt-menu__link-text">Sub-Orders</span>
        </a>
    </li> --}}
    <li class="kt-menu__item @if(Request::is('supplier/representatives*')) kt-menu__item--active @endif" aria-haspopup="true">
        <a href="{{ route('supplier.representatives.index') }}" class="kt-menu__link">
            <i class="kt-menu__link-icon flaticon2-user"></i>
            <span class="kt-menu__link-text">@lang('messages.supplier_dashboard.navigation.representatives')</span>
        </a>
    </li>
    <li class="kt-menu__item @if(Request::is('supplier/profile*')) kt-menu__item--active @endif" aria-haspopup="true">
        <a href="{{ route('supplier.profile.edit') }}" class="kt-menu__link">
            <i class="kt-menu__link-icon flaticon2-user-outline-symbol"></i>
            <span class="kt-menu__link-text">@lang('messages.supplier_dashboard.navigation.profile')</span>
        </a>
    </li>
@endif
