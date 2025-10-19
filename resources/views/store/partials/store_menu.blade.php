<nav class="kt-aside-menu">
    <ul class="kt-menu__nav">
        <li class="kt-menu__item">
            <a href="{{ route('store.dashboard') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon flaticon-dashboard"></i>
                <span class="kt-menu__link-text">@lang('messages.store_menu.dashboard')</span>
            </a>
        </li>
        <li class="kt-menu__item">
            <a href="{{ route('store.orders.index') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon flaticon-cart"></i>
                <span class="kt-menu__link-text">@lang('messages.store_menu.orders')</span>
            </a>
        </li>
        <li class="kt-menu__item">
            <a href="{{ route('store.wallet.index') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon fas fa-wallet"></i>
                <span class="kt-menu__link-text">@lang('messages.store_menu.wallet')</span>
            </a>
        </li>
        <li class="kt-menu__item">
            <a href="{{ route('store.branches.index') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon flaticon-map"></i>
                <span class="kt-menu__link-text">@lang('messages.store_menu.branches')</span>
            </a>
        </li>
        <li class="kt-menu__item">
            <a href="{{ route('store.profile.edit') }}" class="kt-menu__link">
                <i class="kt-menu__link-icon flaticon-user"></i>
                <span class="kt-menu__link-text">@lang('messages.store_menu.profile')</span>
            </a>
        </li>
    </ul>
</nav>
