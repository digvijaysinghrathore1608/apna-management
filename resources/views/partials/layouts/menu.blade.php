<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        @include('partials.logo._main')

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>


    @can('microfinance')
        <ul class="menu-inner py-1">
            <!-- Apps & Pages -->
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">{{ translate('micro_finance') }}</span>
            </li>
            <li class="menu-item {{ Route::is('microfinance.dashboard') ? 'active' : '' }}">
                <a href="{{ route('microfinance.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                    <div class="text-truncate" data-i18n="{{ translate('dashboard') }}">{{ translate('dashboard') }}</div>
                </a>
            </li>
            <li class="menu-item {{ Route::is('microfinance.customers.*') ? 'active' : '' }}">
                <a href="{{ route('microfinance.customers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user"></i>
                    <div class="text-truncate" data-i18n="{{ translate('customer_management') }}">
                        {{ translate('customer_management') }}</div>
                </a>
            </li>
            <li class="menu-item {{ Route::is('microfinance.loanapplication.*') ? 'active' : '' }}">
                <a href="{{ route('microfinance.loanapplication.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-hand-holding-dollar"></i>
                    <div class="text-truncate" data-i18n="{{ translate('loan_applications') }}">
                        {{ translate('loan_applications') }}</div>
                </a>
            </li>

            <li class="menu-item {{ Route::is('microfinance.branch.*') ? 'active' : '' }}">
                <a href="{{ route('microfinance.branch.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-building-columns"></i>
                    <div class="text-truncate" data-i18n="{{ translate('branches') }}">
                        {{ translate('branches') }}</div>
                </a>
            </li>

            <li class="menu-item {{ Route::is('microfinance.groups.*') ? 'active' : '' }}">
                <a href="{{ route('microfinance.groups.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-people-group"></i>
                    <div class="text-truncate" data-i18n="{{ translate('groups') }}">
                        {{ translate('groups') }}</div>
                </a>
            </li>

            <li class="menu-item {{ Route::is('microfinance.schema.*') ? 'active' : '' }}">
                <a href="{{ route('microfinance.schema.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-diagram-project"></i>
                    <div class="text-truncate" data-i18n="{{ translate('loan_schemas') }}">
                        {{ translate('loan_schemas') }}</div>
                </a>
            </li>

        </ul>
    @endcan

</aside>
<!-- / Menu -->
