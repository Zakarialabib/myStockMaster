<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-3 px-3">

    <x-sidebar.link title="{{ __('Dashboard') }}" href="{{ route('home') }}" :isActive="request()->routeIs('home')">
        <x-slot name="icon">
            <span class="inline-block mx-4">
                <x-icons.dashboard class="w-5 h-5" aria-hidden="true" />
            </span>
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.dropdown title="{{ __('Products') }}" :active="Str::startsWith(
        request()
            ->route()
            ->uri(),
        'Products',
    )">
        <x-slot name="icon">
            <span class="inline-block mx-4">
                <i class="fas fa-boxes w-5 h-5"></i>
            </span>
        </x-slot>
      
        @can('category_access')
            <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('product-categories.index') }}"
                :active="request()->routeIs('product-categories.index')" />
        @endcan
        <x-sidebar.sublink title="{{ __('All Products') }}" href="{{ route('products.index') }}" :active="request()->routeIs('products.index')" />
        @can('print_barcodes')
            <x-sidebar.sublink title="{{ __('Print Barcode') }}" href="{{ route('barcode.print') }}" :active="request()->routeIs('barcode.print')" />
        @endcan
        @can('brand_access')
            <x-sidebar.sublink title="{{ __('Brands') }}" href="{{ route('brands.index') }}" :active="request()->routeIs('product-brands.index')" />
        @endcan
        @can('access_warehouse')
            <x-sidebar.sublink title="{{ __('Warehouses') }}" href="{{ route('warehouses.index') }}" :active="request()->routeIs('warehouses.index')" />
        @endcan

       
    </x-sidebar.dropdown>

    @can('adjustment_access')
        <x-sidebar.dropdown title="{{ __('Adjustments') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Adjustments',
        )">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-adjust w-5 h-5"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('All Adjustments') }}" href="{{ route('adjustments.index') }}"
                :active="request()->routeIs('adjustments.index')" />
        </x-sidebar.dropdown>
    @endcan

    @can('access_quotations')
        <x-sidebar.dropdown title="{{ __('Quotations') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Quotations',
        )">

            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-file-invoice-dollar w-5 h-5"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('All Quotations') }}" href="{{ route('quotations.index') }}"
                :active="request()->routeIs('quotations.index')" />
        </x-sidebar.dropdown>
    @endcan

    @can('access_purchases')
        <x-sidebar.dropdown title="{{ __('Purchases') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Purchases',
        )">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-shopping-cart w-5 h-5"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('All Purchases') }}" href="{{ route('purchases.index') }}"
                :active="request()->routeIs('purchases.index')" />
            @can('access_purchase_returns')
                <x-sidebar.sublink title="{{ __('All Purchase Returns') }}" href="{{ route('purchase-returns.index') }}"
                    :active="request()->routeIs('purchase-returns.index')" />
            @endcan
        </x-sidebar.dropdown>
    @endcan
    @can('access_sales')
        <x-sidebar.dropdown title="{{ __('Sales') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Sales',
        )">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-shopping-bag w-5 h-5"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('All Sales') }}" href="{{ route('sales.index') }}" :active="request()->routeIs('sales.index')" />
            @can('access_sale_returns')
                <x-sidebar.sublink title="{{ __('All Sale Returns') }}" href="{{ route('sale-returns.index') }}"
                    :active="request()->routeIs('sale-returns.index')" />
            @endcan
        </x-sidebar.dropdown>
    @endcan


    @can('access_expenses')
        <x-sidebar.dropdown title="{{ __('Expenses') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Expenses',
        )">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-money-bill-alt w-5 h-5"></i>
                </span>
            </x-slot>

            @can('access_expense_categories')
                <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('expense-categories.index') }}"
                    :active="request()->routeIs('expense-categories.index')" />
            @endcan
            <x-sidebar.sublink title="{{ __('All Expenses') }}" href="{{ route('expenses.index') }}" :active="request()->routeIs('expenses.index')" />
        </x-sidebar.dropdown>
    @endcan

    @can('access_reports')
        <x-sidebar.dropdown title="{{ __('Reports') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Reports',
        )">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-chart-line w-5 h-5"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('Purchases Report') }}" href="{{ route('purchases-report.index') }}"
                :active="request()->routeIs('purchases-report.index')" />
            <x-sidebar.sublink title="{{ __('Sale Report') }}" href="{{ route('sales-report.index') }}"
                :active="request()->routeIs('sales-report.index')" />
            <x-sidebar.sublink title="{{ __('Sale Return Report') }}" href="{{ route('sales-return-report.index') }}"
                :active="request()->routeIs('sales-return-report.index')" />
            <x-sidebar.sublink title="{{ __('Payment Report') }}" href="{{ route('payments-report.index') }}"
                :active="request()->routeIs('payments-report.index')" />
            <x-sidebar.sublink title="{{ __('Purchases Return Report') }}"
                href="{{ route('purchases-return-report.index') }}" :active="request()->routeIs('purchases-return-report.index')" />
            <x-sidebar.sublink title="{{ __('Profit Report') }}" href="{{ route('profit-loss-report.index') }}"
                :active="request()->routeIs('profit-loss-report.index')" />

        </x-sidebar.dropdown>
    @endcan

    @can('user_access')
        <x-sidebar.dropdown title="{{ __('People') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'people',
        )">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-users w-5 h-5"></i>
                </span>
            </x-slot>
            @can('customer_access')
                <x-sidebar.sublink title="{{ __('Customers') }}" href="{{ route('customers.index') }}" :active="request()->routeIs('customers.index')" />
            @endcan
            @can('suppliers_access')
                <x-sidebar.sublink title="{{ __('Suppliers') }}" href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.index')" />
            @endcan
            @can('user_access')
                <x-sidebar.sublink title="{{ __('Users') }}" href="{{ route('users.index') }}" :active="request()->routeIs('users.index')" />
            @endcan
            @can('access_roles')
                <x-sidebar.sublink title="{{ __('Roles') }}" href="{{ route('roles.index') }}" :active="request()->routeIs('roles.index')" />
            @endcan
            @can('access_permissions')
                <x-sidebar.sublink title="{{ __('Permissions') }}" href="{{ route('permissions.index') }}"
                    :active="request()->routeIs('permissions.index')" />
            @endcan
        </x-sidebar.dropdown>
    @endcan
    @can('access_currencies|access_settings')
        <x-sidebar.dropdown title="{{ __('Settings') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Settings',
        )">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-cog w-5 h-5"></i>
                </span>
            </x-slot>
            @can('setting_access')
                <x-sidebar.sublink title="{{ __('Settings') }}" href="{{ route('settings.index') }}" :active="request()->routeIs('settings.index')" />
            @endcan
            @can('access_currencies')
                <x-sidebar.sublink title="{{ __('Currencies') }}" href="{{ route('currencies.index') }}" :active="request()->routeIs('currencies.index')" />
            @endcan
            @can('access_languages')
                <x-sidebar.sublink title="{{ __('Languages') }}" href="{{ route('languages.index') }}" :active="request()->routeIs('languages.index')" />
            @endcan
            @can('access_backup')
                <x-sidebar.sublink title="{{ __('Backup') }}" href="{{ route('backup.index') }}" :active="request()->routeIs('backup.index')" />
            @endcan

        </x-sidebar.dropdown>
    @endcan

    <x-sidebar.link title="{{ __('Logout') }}"
        onclick="event.preventDefault();
                        document.getElementById('logoutform').submit();"
        href="#">
        <x-slot name="icon">
            <span class="inline-block mx-4">
                <i class="fas fa-sign-out-alt w-5 h-5" aria-hidden="true"></i>
            </span>
        </x-slot>
    </x-sidebar.link>

</x-perfect-scrollbar>
