<x-perfect-scrollbar as="nav" aria-label="main" wire:navigate:scroll class="flex flex-col flex-1 gap-1 px-3 py-4 md:gap-2 lg:gap-3">

    <x-sidebar.link title="{{ __('Dashboard') }}" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')" :icon="'<i class=\'fas fa-th-large w-5 h-5\'></i>'" />

    @can('product_access')
    <x-sidebar.dropdown title="{{ __('Products') }}" :active="request()->routeIs([
            'products.*',
            'product-categories.index',
            'products.barcode-print',
            'brands.index',
            'warehouses.index',
            'adjustments.index',
        ])" :icon="'<i class=\'fas fa-boxes w-5 h-5\'></i>'">

        @can('category_access')
        <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('product-categories.index') }}" :active="request()->routeIs('product-categories.index')" :icon="'<i class=\'fas fa-tags w-4 h-4\'></i>'" />
        @endcan
        @can('product_access')
        <x-sidebar.sublink title="{{ __('All Products') }}" href="{{ route('products.index') }}" :active="request()->routeIs('products.index')" :icon="'<i class=\'fas fa-box w-4 h-4\'></i>'" />
        @endcan
        @can('print_barcodes')
        <x-sidebar.sublink title="{{ __('Print Barcode') }}" href="{{ route('products.barcode-print') }}" :active="request()->routeIs('products.barcode-print')" :icon="'<i class=\'fas fa-barcode w-4 h-4\'></i>'" />
        @endcan
        @can('brand_access')
        <x-sidebar.sublink title="{{ __('Brands') }}" href="{{ route('brands.index') }}" :active="request()->routeIs('brands.index')" :icon="'<i class=\'fas fa-copyright w-4 h-4\'></i>'" />
        @endcan
        @can('warehouse_access')
        <x-sidebar.sublink title="{{ __('Warehouses') }}" href="{{ route('warehouses.index') }}" :active="request()->routeIs('warehouses.index')" :icon="'<i class=\'fas fa-warehouse w-4 h-4\'></i>'" />
        @endcan
        @can('adjustment_access')
        <x-sidebar.sublink title="{{ __('Stock adjustments') }}" href="{{ route('adjustments.index') }}" :active="request()->routeIs('adjustments.index')" :icon="'<i class=\'fas fa-sliders-h w-4 h-4\'></i>'" />
        @endcan

    </x-sidebar.dropdown>
    @endcan

    @can('quotation_access')
    <x-sidebar.dropdown title="{{ __('Quotations') }}" :active="request()->routeIs('quotations.index')" :icon="'<i class=\'fas fa-file-invoice-dollar w-5 h-5\'></i>'">
        <x-sidebar.sublink title="{{ __('All Quotations') }}" href="{{ route('quotations.index') }}" :active="request()->routeIs('quotations.index')" :icon="'<i class=\'fas fa-file-alt w-4 h-4\'></i>'" />
    </x-sidebar.dropdown>
    @endcan

    @can('purchase_access')
    <x-sidebar.dropdown title="{{ __('Purchases') }}" :active="request()->routeIs('purchases.index') || request()->routeIs('purchase-returns.index')" :icon="'<i class=\'fas fa-shopping-cart w-5 h-5\'></i>'">
        <x-sidebar.sublink title="{{ __('All Purchases') }}" href="{{ route('purchases.index') }}" :active="request()->routeIs('purchases.index')" :icon="'<i class=\'fas fa-file-invoice w-4 h-4\'></i>'" />
        @can('purchase_return_access')
        <x-sidebar.sublink title="{{ __('All Purchase Returns') }}" href="{{ route('purchase-returns.index') }}" :active="request()->routeIs('purchase-returns.index')" :icon="'<i class=\'fas fa-undo w-4 h-4\'></i>'" />
        @endcan
    </x-sidebar.dropdown>
    @endcan

    @can('sale_access')
    <x-sidebar.dropdown title="{{ __('Sales') }}" :active="request()->routeIs(['sales.index', 'sale-returns.index'])" :icon="'<i class=\'fas fa-shopping-bag w-5 h-5\'></i>'">
        <x-sidebar.sublink title="{{ __('All Sales') }}" href="{{ route('sales.index') }}" :active="request()->routeIs('sales.index')" :icon="'<i class=\'fas fa-file-invoice w-4 h-4\'></i>'" />
        @can('sale_return_access')
        <x-sidebar.sublink title="{{ __('All Sale Returns') }}" href="{{ route('sale-returns.index') }}" :active="request()->routeIs('sale-returns.index')" :icon="'<i class=\'fas fa-undo w-4 h-4\'></i>'" />
        @endcan
    </x-sidebar.dropdown>
    @endcan

    @can('expense_access')
    <x-sidebar.dropdown title="{{ __('Expenses') }}" :active="request()->routeIs(['expenses.index', 'expense-categories.index'])" :icon="'<i class=\'fas fa-money-bill-wave w-5 h-5\'></i>'">
        @can('expense_categories_access')
        <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('expense-categories.index') }}" :active="request()->routeIs('expense-categories.index')" :icon="'<i class=\'fas fa-folder w-4 h-4\'></i>'" />
        @endcan
        <x-sidebar.sublink title="{{ __('All Expenses') }}" href="{{ route('expenses.index') }}" :active="request()->routeIs('expenses.index')" :icon="'<i class=\'fas fa-receipt w-4 h-4\'></i>'" />
    </x-sidebar.dropdown>
    @endcan

    @can('report_access')
    <x-sidebar.dropdown title="{{ __('Reports') }}" :active="request()->routeIs([
            'purchases-report.index',
            'sales-report.index',
            'sales-return-report.index',
            'payments-report.index',
            'purchases-return-report.index',
            'profit-loss-report.index',
            'stock-alert-report.index',
            'customers-report.index',
            'suppliers-report.index',
        ])" :icon="'<i class=\'fas fa-chart-line w-5 h-5\'></i>'">
        <x-sidebar.sublink title="{{ __('Purchases Report') }}" href="{{ route('purchases-report.index') }}" :active="request()->routeIs('purchases-report.index')" :icon="'<i class=\'fas fa-file-alt w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Purchases Return Report') }}" href="{{ route('purchases-return-report.index') }}" :active="request()->routeIs('purchases-return-report.index')" :icon="'<i class=\'fas fa-undo w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Supplier Report') }}" href="{{ route('suppliers-report.index') }}" :active="request()->routeIs('suppliers-report.index')" :icon="'<i class=\'fas fa-user w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Sale Report') }}" href="{{ route('sales-report.index') }}" :active="request()->routeIs('sales-report.index')" :icon="'<i class=\'fas fa-chart-bar w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Sale Return Report') }}" href="{{ route('sales-return-report.index') }}" :active="request()->routeIs('sales-return-report.index')" :icon="'<i class=\'fas fa-undo-alt w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Customers Report') }}" href="{{ route('customers-report.index') }}" :active="request()->routeIs('customers-report.index')" :icon="'<i class=\'fas fa-user w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Payment Report') }}" href="{{ route('payments-report.index') }}" :active="request()->routeIs('payments-report.index')" :icon="'<i class=\'fas fa-credit-card w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Profit Report') }}" href="{{ route('profit-loss-report.index') }}" :active="request()->routeIs('profit-loss-report.index')" :icon="'<i class=\'fas fa-chart-pie w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Stock Alert Report') }}" href="{{ route('stock-alert-report.index') }}" :active="request()->routeIs('stock-alert-report.index')" :icon="'<i class=\'fas fa-bell w-4 h-4\'></i>'" />
    </x-sidebar.dropdown>
    @endcan

    @can('report_access')
    <x-sidebar.dropdown title="{{ __('Analytics') }}" :active="request()->routeIs(['analytics.dashboard', 'analytics.product', 'analytics.revenue'])" :icon="'<i class=\'fas fa-chart-bar w-5 h-5\'></i>'">
        <x-sidebar.sublink title="{{ __('Analytics Dashboard') }}" href="{{ route('analytics.dashboard') }}" :active="request()->routeIs('analytics.dashboard')" :icon="'<i class=\'fas fa-tachometer-alt w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Product Analytics') }}" href="{{ route('analytics.product') }}" :active="request()->routeIs('analytics.product')" :icon="'<i class=\'fas fa-boxes w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Revenue Reports') }}" href="{{ route('analytics.revenue') }}" :active="request()->routeIs('analytics.revenue')" :icon="'<i class=\'fas fa-dollar-sign w-4 h-4\'></i>'" />
    </x-sidebar.dropdown>
    @endcan

    @can('report_access')
    <x-sidebar.dropdown title="{{ __('Finance') }}" :active="request()->routeIs(['finance.dashboard', 'finance.kpi', 'finance.breakeven'])" :icon="'<i class=\'fas fa-calculator w-5 h-5\'></i>'">
        <x-sidebar.sublink title="{{ __('Financial Dashboard') }}" href="{{ route('finance.dashboard') }}" :active="request()->routeIs('finance.dashboard')" :icon="'<i class=\'fas fa-tachometer-alt w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('KPI Tracking') }}" href="{{ route('finance.kpi') }}" :active="request()->routeIs('finance.kpi')" :icon="'<i class=\'fas fa-bullseye w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Break Even Analysis') }}" href="{{ route('finance.breakeven') }}" :active="request()->routeIs('finance.breakeven')" :icon="'<i class=\'fas fa-equals w-4 h-4\'></i>'" />
    </x-sidebar.dropdown>
    @endcan

    @can('user_access')
    <x-sidebar.dropdown title="{{ __('People') }}" :active="request()->routeIs('customers.*') ||
            request()->routeIs('customer-group.*') ||
            request()->routeIs('suppliers.*') ||
            request()->routeIs('users.*') ||
            request()->routeIs('roles.*') ||
            request()->routeIs('permissions.*')" :icon="'<i class=\'fas fa-users w-5 h-5\'></i>'">
        <x-sidebar.sublink title="{{ __('Users') }}" href="{{ route('users.index') }}" :active="request()->routeIs('users.index')" :icon="'<i class=\'fas fa-user w-4 h-4\'></i>'" />
        @can('customer_access')
        <x-sidebar.sublink title="{{ __('Customers') }}" href="{{ route('customers.index') }}" :active="request()->routeIs('customers.index')" :icon="'<i class=\'fas fa-user-tie w-4 h-4\'></i>'" />
        @endcan
        @can('customer_group_access')
        <x-sidebar.sublink title="{{ __('Customer Groups') }}" href="{{ route('customer-group.index') }}" :active="request()->routeIs('customer-group.index')" :icon="'<i class=\'fas fa-users-cog w-4 h-4\'></i>'" />
        @endcan
        @can('suppliers_access')
        <x-sidebar.sublink title="{{ __('Suppliers') }}" href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.index')" :icon="'<i class=\'fas fa-truck w-4 h-4\'></i>'" />
        @endcan
        @can('access_roles')
        <x-sidebar.sublink title="{{ __('Roles') }}" href="{{ route('roles.index') }}" :active="request()->routeIs('roles.index')" :icon="'<i class=\'fas fa-theater-masks w-4 h-4\'></i>'" />
        @endcan
        @can('access_permissions')
        <x-sidebar.sublink title="{{ __('Permissions') }}" href="{{ route('permissions.index') }}" :active="request()->routeIs('permissions.index')" :icon="'<i class=\'fas fa-key w-4 h-4\'></i>'" />
        @endcan
    </x-sidebar.dropdown>
    @endcan

    @can('setting_access')
    <x-sidebar.dropdown title="{{ __('Settings') }}" :active="request()->routeIs('settings.*') || request()->routeIs('currencies.*') || request()->routeIs('notifications.manager') || request()->routeIs('units.*')" :icon="'<i class=\'fas fa-cog w-5 h-5\'></i>'">
        <x-sidebar.sublink title="{{ __('Notification Manager') }}" href="{{ route('notifications.manager') }}" :active="request()->routeIs('notifications.manager')" :icon="'<i class=\'fas fa-bells w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('General Settings') }}" href="{{ route('settings.index') }}" :active="request()->routeIs('settings.index')" :icon="'<i class=\'fas fa-sliders-h w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Currencies') }}" href="{{ route('currencies.index') }}" :active="request()->routeIs('currencies.index')" :icon="'<i class=\'fas fa-coins w-4 h-4\'></i>'" />
        <x-sidebar.sublink title="{{ __('Languages') }}" href="{{ route('languages.index') }}" :active="request()->routeIs('languages.index')" :icon="'<i class=\'fas fa-ruler w-4 h-4\'></i>'" />
        {{-- <x-sidebar.sublink title="{{ __('Units') }}" href="{{ route('units.index') }}" :active="request()->routeIs('units.index')" :icon="'<i class=\'fas fa-ruler w-4 h-4\'></i>'" /> --}}
    </x-sidebar.dropdown>
    @endcan

</x-perfect-scrollbar>