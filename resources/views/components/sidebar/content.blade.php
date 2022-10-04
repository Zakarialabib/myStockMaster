<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3 overflow-auto">

    <x-sidebar.link title="{{ __('Dashboard') }}" href="{{ route('home') }}" :isActive="request()->routeIs('admin.dashboard')">
        {{-- <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-8 h-8 block my-0 mx-auto" aria-hidden="true" />
        </x-slot> --}}
    </x-sidebar.link>

    <x-sidebar.dropdown title="{{ __('Products') }}" :active="Str::startsWith(
        request()
            ->route()
            ->uri(),
        'Products',
    )">
        @can('access_product_categories')
            <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('product-categories.index') }}"
                :active="request()->routeIs('product-categories.index')" />
        @endcan
        @can('create_products')
            <x-sidebar.sublink title="{{ __('Create') }}" href="{{ route('products.create') }}" :active="request()->routeIs('products.create')" />
        @endcan
        <x-sidebar.sublink title="{{ __('All Products') }}" href="{{ route('products.index') }}" :active="request()->routeIs('products.index')" />
        @can('print_barcodes')
            <x-sidebar.sublink title="{{ __('Print Barcode') }}" href="{{ route('barcode.print') }}" :active="request()->routeIs('barcode.print')" />
        @endcan
    </x-sidebar.dropdown>
    
    @can('access_adjustments')
        <x-sidebar.dropdown title="{{ __('Adjustments') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Adjustments',
        )">
            @can('create_adjustments')
                <x-sidebar.sublink title="{{ __('Create') }}" href="{{ route('adjustments.create') }}" :active="request()->routeIs('adjustments.create')" />
            @endcan
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
            @can('create_quotations')
                <x-sidebar.sublink title="{{ __('Create') }}" href="{{ route('quotations.create') }}" :active="request()->routeIs('quotations.create')" />
            @endcan
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
            @can('create_purchases')
                <x-sidebar.sublink title="{{ __('Create') }}" href="{{ route('purchases.create') }}" :active="request()->routeIs('purchases.create')" />
            @endcan
            <x-sidebar.sublink title="{{ __('All Purchases') }}" href="{{ route('purchases.index') }}"
                :active="request()->routeIs('purchases.index')" />
        </x-sidebar.dropdown>
    @endcan
    @can('access_sales')
        <x-sidebar.dropdown title="{{ __('Sales') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Sales',
        )">
            @can('create_sales')
                <x-sidebar.sublink title="{{ __('Create') }}" href="{{ route('sales.create') }}" :active="request()->routeIs('sales.create')" />
            @endcan
            <x-sidebar.sublink title="{{ __('All Sales') }}" href="{{ route('sales.index') }}" :active="request()->routeIs('sales.index')" />
        </x-sidebar.dropdown>
    @endcan
    @can('access_purchase_returns')
        <x-sidebar.dropdown title="{{ __('Purchase Returns') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Purchase Returns',
        )">
            @can('create_purchase_returns')
                <x-sidebar.sublink title="{{ __('Create') }}" href="{{ route('purchase-returns.create') }}"
                    :active="request()->routeIs('purchase-returns.create')" />
            @endcan

            <x-sidebar.sublink title="{{ __('All Purchase Returns') }}" href="{{ route('purchase-returns.index') }}"
                :active="request()->routeIs('purchase-returns.index')" />
        </x-sidebar.dropdown>
    @endcan
    @can('access_sale_returns')
        <x-sidebar.dropdown title="{{ __('Sale Returns') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Sale Returns',
        )">
            @can('create_sale_returns')
                <x-sidebar.sublink title="{{ __('Create') }}" href="{{ route('sale-returns.create') }}" :active="request()->routeIs('sale-returns.create')" />
            @endcan
            <x-sidebar.sublink title="{{ __('All Sale Returns') }}" href="{{ route('sale-returns.index') }}"
                :active="request()->routeIs('sale-returns.index')" />
        </x-sidebar.dropdown>
    @endcan
    @can('access_expenses')
        <x-sidebar.dropdown title="{{ __('Expenses') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Expenses',
        )">
            @can('access_expense_categories')
                <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('expense-categories.index') }}"
                    :active="request()->routeIs('expense-categories.index')" />
            @endcan
            @can('create_expenses')
                <x-sidebar.sublink title="{{ __('Create') }}" href="{{ route('expenses.create') }}" :active="request()->routeIs('expenses.create')" />
            @endcan
            <x-sidebar.sublink title="{{ __('All Expenses') }}" href="{{ route('expenses.index') }}" :active="request()->routeIs('expenses.index')" />
        </x-sidebar.dropdown>
    @endcan
    @can('access_customers|access_suppliers')
        <x-sidebar.dropdown title="{{ __('People') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'People',
        )">
            @can('access_customers')
                <x-sidebar.sublink title="{{ __('Customers') }}" href="{{ route('customers.index') }}" :active="request()->routeIs('customers.index')" />
            @endcan
            @can('access_suppliers')
                <x-sidebar.sublink title="{{ __('Suppliers') }}" href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.index')" />
            @endcan
        </x-sidebar.dropdown>
    @endcan
    @can('access_reports')
        <x-sidebar.dropdown title="{{ __('Reports') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'Reports',
        )">
            <x-sidebar.sublink title="{{ __('Purchase Report') }}" href="{{ route('purchases-report.index') }}"
                :active="request()->routeIs('purchases-report.index')" />
            <x-sidebar.sublink title="{{ __('Sale Report') }}" href="{{ route('sales-report.index') }}"
                :active="request()->routeIs('sales-report.index')" />
            <x-sidebar.sublink title="{{ __('Sale Return Report') }}" href="{{ route('sales-return-report.index') }}"
                :active="request()->routeIs('sales-return-report.index')" />
            <x-sidebar.sublink title="{{ __('Payment Report') }}" href="{{ route('payments-report.index') }}"
                :active="request()->routeIs('payments-report.index')" />
            <x-sidebar.sublink title="{{ __('Purchases Return Report') }}" href="{{ route('purchases-return-report.index') }}"
                :active="request()->routeIs('purchases-return-report.index')" />
            <x-sidebar.sublink title="{{ __('Profit Report') }}" href="{{ route('profit-loss-report.index') }}"
                :active="request()->routeIs('profit-loss-report.index')" />

        </x-sidebar.dropdown>
    @endcan

    @can('access_user_management')
        <x-sidebar.dropdown title="{{ __('User Management') }}" :active="Str::startsWith(
            request()
                ->route()
                ->uri(),
            'User Management',
        )">
            @can('access_users')
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
            @can('access_currencies')
                <x-sidebar.sublink title="{{ __('Currencies') }}" href="{{ route('currencies.index') }}" :active="request()->routeIs('currencies.index')" />
            @endcan
            @can('access_settings')
                <x-sidebar.sublink title="{{ __('Settings') }}" href="{{ route('settings.index') }}" :active="request()->routeIs('settings.index')" />
            @endcan
        </x-sidebar.dropdown>
    @endcan

    <x-sidebar.link title="{{ __('Logout') }}" onclick="event.preventDefault();
                        document.getElementById('logoutform').submit();" href="#">
        {{-- <x-slot name="icon">
            <x-heroicon-o-logout class="flex-shrink-0 w-8 h-8 block my-0 mx-auto" aria-hidden="true" />
        </x-slot> --}}
    </x-sidebar.link>

</x-perfect-scrollbar>
