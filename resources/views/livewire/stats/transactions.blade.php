<div>    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-chart-bar text-blue-600 dark:text-blue-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Sales/Purchases') }}</h3>
            </div>
            <div id="chart" class="min-h-[350px]"></div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Monthly Cash Flow (Payment Sent & Received)') }}</h3>
            </div>
            <div id="monthly-chart" class="min-h-[350px]"></div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600 dark:text-purple-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Daily Sales and Purchases') }}</h3>
            </div>
            <div id="daily-chart" class="min-h-[350px]"></div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <i class="fas fa-credit-card text-orange-600 dark:text-orange-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Payment Chart') }}</h3>
            </div>
            <div id="payment-chart" class="min-h-[350px]"></div>
        </div>
    </div>
    <!-- Recent Sales Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="py-4 px-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-shopping-cart text-blue-600 dark:text-blue-400 text-sm"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Recent Sale') }}</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>{{ __('Customer') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-dollar-sign mr-2"></i>{{ __('Total') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>{{ __('Date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>{{ __('Status') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($lastSales as $sale)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $sale->customer?->name }}</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ $sale->reference }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                {{ format_currency($sale->total_amount) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $sale->date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $type = $sale->status->getBadgeType();
                                @endphp
                                <x-badge :type="$type" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium">
                                    {{ $sale->status }}
                                </x-badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-shopping-cart text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('No recent sales found') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Sales will appear here once created') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Recent Purchases Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="py-4 px-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-truck text-green-600 dark:text-green-400 text-sm"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Recent Purchase') }}</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-building mr-2"></i>{{ __('Supplier') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-dollar-sign mr-2"></i>{{ __('Total') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>{{ __('Date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>{{ __('Status') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($lastPurchases as $purchase)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <a href="{{ route('supplier.details', $purchase->supplier->id) }}"
                                        class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                        {{ $purchase->supplier->name }}
                                    </a>
                                    <p class="text-xs text-green-600 dark:text-green-400 font-medium">{{ $purchase->reference }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                {{ format_currency($purchase->total_amount) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $purchase->date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeType = $purchase->status->getBadgeType();
                                @endphp
                                <x-badge :type="$badgeType" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium">
                                    {{ $purchase->status }}
                                </x-badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-truck text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('No recent purchases found') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Purchases will appear here once created') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex flex-wrap gap-y-4">
        <!-- Top Sellers Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="py-4 px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <i class="fas fa-trophy text-yellow-600 dark:text-yellow-400 text-sm"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Top 5 Sellers in') }} {{ now()->format('F') }}</h3>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>{{ __('Seller') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-dollar-sign mr-2"></i>{{ __('Profit') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>{{ __('Customer') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>{{ __('Sale Date') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($bestSales as $sale)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" wire:key="{{ $sale->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $sale->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ format_currency($sale->total_amount) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $sale->customer->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $sale->created_at->format('Y-m-d') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-trophy text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('No top sellers found') }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Top sellers will appear here once sales are made') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Products Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="py-4 px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <i class="fas fa-star text-purple-600 dark:text-purple-400 text-sm"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Top Products in') }} {{ now()->format('F') }}</h3>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-box mr-2"></i>{{ __('Product Name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-barcode mr-2"></i>{{ __('Code') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-sort-numeric-up mr-2"></i>{{ __('Total Quantity') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-dollar-sign mr-2"></i>{{ __('Total Sales') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-warehouse mr-2"></i>{{ __('Warehouse') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($this->topProducts as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" wire:key="{{ $product->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $product->code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $product->qtyItem }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ format_currency($product->totalSalesAmount) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge danger class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium">
                                        {{-- {{ \App\Helpers::warehouseName($product->warehouse_id) }}   --}}
                                    </x-badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-star text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('No top products found') }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Top products will appear here once sales are made') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Top Customers Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="py-4 px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                        <i class="fas fa-users text-indigo-600 dark:text-indigo-400 text-sm"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Top Customers by Warehouse') }}</h3>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-hashtag mr-2"></i>{{ __('#') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-warehouse mr-2"></i>{{ __('Warehouse') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>{{ __('Top Customer') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-dollar-sign mr-2"></i>{{ __('Total Sales') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($this->topCustomers as $index => $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" wire:key="{{ $index }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge danger class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium">
                                        {{-- {{ \App\Helpers::warehouseName($customer->warehouse_id) }} --}}
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $customer->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ format_currency($customer->totalSalesAmount) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('No top customers found') }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Top customers will appear here once sales are made') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @script
        <script>
            document.addEventListener('livewire:init', () => {
                // Function to get current theme
                function isDarkMode() {
                    return document.documentElement.classList.contains('dark') || 
                           (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
                }

                // Function to get theme colors
                function getThemeColors() {
                    const dark = isDarkMode();
                    return {
                        textColor: dark ? '#e5e7eb' : '#374151',
                        gridColor: dark ? '#374151' : '#e5e7eb',
                        backgroundColor: dark ? '#1f2937' : '#ffffff'
                    };
                }

                // Enhanced chart options with dark mode support
                function getEnhancedOptions(baseOptions) {
                    const colors = getThemeColors();
                    return {
                        ...baseOptions,
                        theme: {
                            mode: isDarkMode() ? 'dark' : 'light'
                        },
                        chart: {
                            ...baseOptions.chart,
                            background: 'transparent',
                            foreColor: colors.textColor,
                            toolbar: {
                                show: true,
                                tools: {
                                    download: true,
                                    selection: true,
                                    zoom: true,
                                    zoomin: true,
                                    zoomout: true,
                                    pan: true,
                                    reset: true
                                }
                            }
                        },
                        grid: {
                            borderColor: colors.gridColor,
                            strokeDashArray: 3
                        },
                        xaxis: {
                            ...baseOptions.xaxis,
                            labels: {
                                style: {
                                    colors: colors.textColor
                                }
                            }
                        },
                        yaxis: {
                            ...baseOptions.yaxis,
                            labels: {
                                style: {
                                    colors: colors.textColor
                                }
                            }
                        },
                        legend: {
                            ...baseOptions.legend,
                            labels: {
                                colors: colors.textColor
                            }
                        }
                    };
                }

                // Render daily chart with enhanced options
                const dailyOptions = getEnhancedOptions(@json($this->dailyChartOptions));
                var dailyChart = new ApexCharts(document.querySelector("#daily-chart"), dailyOptions);
                dailyChart.render();

                // Render monthly chart with enhanced options
                const monthlyOptions = getEnhancedOptions(@json($this->monthlyChartOptions));
                var monthlyChart = new ApexCharts(document.querySelector("#monthly-chart"), monthlyOptions);
                monthlyChart.render();

                // Render payment chart with enhanced options
                const paymentOptions = getEnhancedOptions(@json($this->paymentChart));
                var paymentChart = new ApexCharts(document.querySelector("#payment-chart"), paymentOptions);
                paymentChart.render();

                // Enhanced chart function with dark mode support
                function chart(data, selector) {
                    let tes = data;
                    const colors = getThemeColors();
                    
                    let options = {
                        series: [{
                                name: "Sales Total Amount",
                                data: tes.total.sales,
                                color: '#10b981'
                            },
                            {
                                name: "Sales Due Amount",
                                data: tes.due_amount.sales,
                                color: '#f59e0b'
                            },
                            {
                                name: "Purchase Total Amount",
                                data: tes.total.purchase,
                                color: '#3b82f6'
                            },
                            {
                                name: "Purchase Due Amount",
                                data: tes.due_amount.purchase,
                                color: '#ef4444'
                            }
                        ],
                        chart: {
                            height: 350,
                            width: '100%',
                            type: "bar",
                            background: 'transparent',
                            foreColor: colors.textColor,
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: true,
                                tools: {
                                    download: true,
                                    selection: true,
                                    zoom: true,
                                    zoomin: true,
                                    zoomout: true,
                                    pan: true,
                                    reset: true
                                }
                            }
                        },
                        theme: {
                            mode: isDarkMode() ? 'dark' : 'light'
                        },
                        responsive: [{
                            breakpoint: 768,
                            options: {
                                chart: {
                                    height: 300
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }],
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                borderRadius: 4,
                                dataLabels: {
                                    position: "top"
                                },
                                columnWidth: '60%'
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        grid: {
                            borderColor: colors.gridColor,
                            strokeDashArray: 3
                        },
                        xaxis: {
                            categories: tes.labels,
                            labels: {
                                style: {
                                    colors: colors.textColor
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: "Amount",
                                style: {
                                    color: colors.textColor
                                }
                            },
                            labels: {
                                style: {
                                    colors: colors.textColor
                                },
                                formatter: function(val) {
                                    return '$' + val.toLocaleString();
                                }
                            }
                        },
                        tooltip: {
                            theme: isDarkMode() ? 'dark' : 'light',
                            y: {
                                formatter: function(val) {
                                    return "$" + val.toLocaleString();
                                }
                            }
                        },
                        legend: {
                            position: "top",
                            horizontalAlign: "center",
                            offsetY: -10,
                            labels: {
                                colors: colors.textColor
                            }
                        }
                    };
                    
                    const chart = new ApexCharts(document.querySelector(selector), options);
                    chart.render();
                    
                    // Listen for theme changes and update chart
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'class') {
                                const newColors = getThemeColors();
                                chart.updateOptions({
                                    theme: {
                                        mode: isDarkMode() ? 'dark' : 'light'
                                    },
                                    chart: {
                                        foreColor: newColors.textColor
                                    },
                                    grid: {
                                        borderColor: newColors.gridColor
                                    },
                                    xaxis: {
                                        labels: {
                                            style: {
                                                colors: newColors.textColor
                                            }
                                        }
                                    },
                                    yaxis: {
                                        labels: {
                                            style: {
                                                colors: newColors.textColor
                                            }
                                        }
                                    },
                                    legend: {
                                        labels: {
                                            colors: newColors.textColor
                                        }
                                    }
                                });
                            }
                        });
                    });
                    
                    observer.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                }
                
                chart({!! $charts !!}, '#chart');
            })
        </script>
    @endscript

</div>
