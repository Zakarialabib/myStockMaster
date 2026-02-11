<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-1 px-3 py-4 md:gap-2 lg:gap-3">

    <x-sidebar.link title="{{ __('Dashboard') }}" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')" class="group">
        <x-slot name="icon">
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                <x-icons.dashboard
                    class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"
                    aria-hidden="true" />
            </span>
        </x-slot>
    </x-sidebar.link>
    @can('product_access')

        <x-sidebar.dropdown title="{{ __('Products') }}" :active="request()->routeIs([
            'products.*',
            'product-categories.index',
            'products.barcode-print',
            'brands.index',
            'warehouses.index',
            'adjustments.index',
        ])" class="group">

            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-boxes w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>

            @can('category_access')
                <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('product-categories.index') }}"
                    :active="request()->routeIs('product-categories.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('product_access')
                <x-sidebar.sublink title="{{ __('All Products') }}" href="{{ route('products.index') }}" :active="request()->routeIs('products.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('print_barcodes')
                <x-sidebar.sublink title="{{ __('Print Barcode') }}" href="{{ route('products.barcode-print') }}"
                    :active="request()->routeIs('products.barcode-print')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('brand_access')
                <x-sidebar.sublink title="{{ __('Brands') }}" href="{{ route('brands.index') }}" :active="request()->routeIs('brands.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('warehouse_access')
                <x-sidebar.sublink title="{{ __('Warehouses') }}" href="{{ route('warehouses.index') }}" :active="request()->routeIs('warehouses.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('adjustment_access')
                <x-sidebar.sublink title="{{ __('Stock adjustments') }}" href="{{ route('adjustments.index') }}"
                    :active="request()->routeIs('adjustments.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan

        </x-sidebar.dropdown>
    @endcan

    @can('quotation_access')
        <x-sidebar.dropdown title="{{ __('Quotations') }}" :active="request()->routeIs('quotations.index')" class="group">

            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-file-invoice-dollar w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('All Quotations') }}" href="{{ route('quotations.index') }}" :active="request()->routeIs('quotations.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
        </x-sidebar.dropdown>
    @endcan

    @can('purchase_access')
        <x-sidebar.dropdown title="{{ __('Purchases') }}" :active="request()->routeIs('purchases.index') || request()->routeIs('purchase-returns.index')" class="group">
            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-shopping-cart w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('All Purchases') }}" href="{{ route('purchases.index') }}" :active="request()->routeIs('purchases.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @can('purchase_return_access')
                <x-sidebar.sublink title="{{ __('All Purchase Returns') }}" href="{{ route('purchase-returns.index') }}"
                    :active="request()->routeIs('purchase-returns.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
        </x-sidebar.dropdown>
    @endcan
    @can('sale_access')
        <x-sidebar.dropdown title="{{ __('Sales') }}" :active="request()->routeIs(['sales.index', 'sale-returns.index'])" class="group">
            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-shopping-bag w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('All Sales') }}" href="{{ route('sales.index') }}" :active="request()->routeIs('sales.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />

            @can('sale_return_access')
                <x-sidebar.sublink title="{{ __('All Sale Returns') }}" href="{{ route('sale-returns.index') }}"
                    :active="request()->routeIs('sale-returns.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
        </x-sidebar.dropdown>
    @endcan


    @can('expense_access')
        <x-sidebar.dropdown title="{{ __('Expenses') }}" :active="request()->routeIs(['expenses.index', 'expense-categories.index'])" class="group">
            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-money-bill-alt w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>

            @can('expense_categories_access')
                <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('expense-categories.index') }}"
                    :active="request()->routeIs('expense-categories.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            <x-sidebar.sublink title="{{ __('All Expenses') }}" href="{{ route('expenses.index') }}" :active="request()->routeIs('expenses.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
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
        ])" class="group">
            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-chart-line w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('Purchases Report') }}" href="{{ route('purchases-report.index') }}"
                :active="request()->routeIs('purchases-report.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Sale Report') }}" href="{{ route('sales-report.index') }}" :active="request()->routeIs('sales-report.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Sale Return Report') }}" href="{{ route('sales-return-report.index') }}"
                :active="request()->routeIs('sales-return-report.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Payment Report') }}" href="{{ route('payments-report.index') }}"
                :active="request()->routeIs('payments-report.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Purchases Return Report') }}"
                href="{{ route('purchases-return-report.index') }}" :active="request()->routeIs('purchases-return-report.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Profit Report') }}" href="{{ route('profit-loss-report.index') }}"
                :active="request()->routeIs('profit-loss-report.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Stock Alert Report') }}" href="{{ route('stock-alert-report.index') }}"
                :active="request()->routeIs('stock-alert-report.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />

        </x-sidebar.dropdown>
    @endcan

    @can('report_access')
        <x-sidebar.dropdown title="{{ __('Analytics') }}" :active="request()->routeIs(['analytics.dashboard', 'analytics.product', 'analytics.revenue'])" class="group">
            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-chart-bar w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('Analytics Dashboard') }}" href="{{ route('analytics.dashboard') }}"
                :active="request()->routeIs('analytics.dashboard')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Product Analytics') }}" href="{{ route('analytics.product') }}"
                :active="request()->routeIs('analytics.product')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Revenue Reports') }}" href="{{ route('analytics.revenue') }}"
                :active="request()->routeIs('analytics.revenue')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />

        </x-sidebar.dropdown>
    @endcan

    @can('report_access')
        <x-sidebar.dropdown title="{{ __('Finance') }}" :active="request()->routeIs(['finance.dashboard', 'finance.kpi', 'finance.breakeven'])" class="group">
            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-calculator w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('Financial Dashboard') }}" href="{{ route('finance.dashboard') }}"
                :active="request()->routeIs('finance.dashboard')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('KPI Tracking') }}" href="{{ route('finance.kpi') }}" :active="request()->routeIs('finance.kpi')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Break Even Analysis') }}" href="{{ route('finance.breakeven') }}"
                :active="request()->routeIs('finance.breakeven')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />

        </x-sidebar.dropdown>
    @endcan

    @can('user_access')
        <x-sidebar.dropdown title="{{ __('Notifications') }}" :active="request()->routeIs(['notifications.bell', 'notifications.manager'])" class="group">
            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-bell w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('Notification Bell') }}" href="{{ route('notifications.bell') }}"
                :active="request()->routeIs('notifications.bell')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            <x-sidebar.sublink title="{{ __('Notification Manager') }}" href="{{ route('notifications.manager') }}"
                :active="request()->routeIs('notifications.manager')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />

        </x-sidebar.dropdown>
    @endcan

    @can('user_access')
        <x-sidebar.dropdown title="{{ __('People') }}" :active="request()->routeIs('customers.*') ||
            request()->routeIs('customer-group.*') ||
            request()->routeIs('suppliers.*') ||
            request()->routeIs('users.*') ||
            request()->routeIs('roles.*') ||
            request()->routeIs('permissions.*')" class="group">
            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-users w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('Users') }}" href="{{ route('users.index') }}" :active="request()->routeIs('users.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @can('customer_access')
                <x-sidebar.sublink title="{{ __('Customers') }}" href="{{ route('customers.index') }}" :active="request()->routeIs('customers.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('customer_group_access')
                <x-sidebar.sublink title="{{ __('Customer Groups') }}" href="{{ route('customer-group.index') }}"
                    :active="request()->routeIs('customer-group.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('suppliers_access')
                <x-sidebar.sublink title="{{ __('Suppliers') }}" href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('access_roles')
                <x-sidebar.sublink title="{{ __('Roles') }}" href="{{ route('roles.index') }}" :active="request()->routeIs('roles.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('access_permissions')
                <x-sidebar.sublink title="{{ __('Permissions') }}" href="{{ route('permissions.index') }}"
                    :active="request()->routeIs('permissions.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
        </x-sidebar.dropdown>
    @endcan
    @can('access_settings')
        <x-sidebar.dropdown title="{{ __('Settings') }}" :active="request()->routeIs([
            'settings.index',
            'logs.index',
            'currencies.index',
            'languages.index',
            'backup.index',
        ])" class="group">
            <x-slot name="icon">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-gray-100 dark:group-hover:bg-gray-800">
                    <i
                        class="fas fa-cog w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100 transition-colors"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('Settings') }}" href="{{ route('settings.index') }}" :active="request()->routeIs('settings.index')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @can('log_access')
                <x-sidebar.sublink title="{{ __('Logs') }}" href="{{ route('logs.index') }}" :active="request()->routeIs('logs.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('currency_access')
                <x-sidebar.sublink title="{{ __('Currencies') }}" href="{{ route('currencies.index') }}" :active="request()->routeIs('currencies.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('language_access')
                <x-sidebar.sublink title="{{ __('Languages') }}" href="{{ route('languages.index') }}" :active="request()->routeIs('languages.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            @can('backup_access')
                <x-sidebar.sublink title="{{ __('Backup') }}" href="{{ route('backup.index') }}" :active="request()->routeIs('backup.index')"
                    class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />
            @endcan
            <x-sidebar.sublink title="{{ __('Components documentation') }}" href="{{ route('components.documentation') }}" :active="request()->routeIs('components.documentation')"
                class="text-sm font-medium py-2.5 px-4 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100" />

        </x-sidebar.dropdown>
    @endcan

    <x-sidebar.link title="{{ __('Logout') }}"
        onclick="event.preventDefault();
                        document.getElementById('logoutform').submit();"
        href="#" class="group mt-auto">
        <x-slot name="icon">
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-colors group-hover:bg-red-50 dark:group-hover:bg-red-900/20">
                <i class="fas fa-sign-out-alt w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors"
                    aria-hidden="true"></i>
            </span>
        </x-slot>
    </x-sidebar.link>

</x-perfect-scrollbar>
