<div>
    <div class="print:hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <div>
                    <div class="text-sm text-gray-500 mb-1">
                        {{ __('Finance') }}
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ __('KPI Tracking') }}
                    </h2>
                </div>
                <div class="print:hidden">
                    <div class="flex items-center space-x-2">
                        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium flex items-center" wire:click="exportKpis">
                            <i class="ti ti-download mr-1"></i>
                            {{ __('Export KPIs') }}
                        </button>
                        <button type="button" class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md font-medium flex items-center" wire:click="refreshKpis">
                            <i class="ti ti-refresh mr-1"></i>
                            {{ __('Refresh') }}
                        </button>
                        <div class="flex items-center ml-2">
                            <input class="sr-only" type="checkbox" wire:model.live="autoRefresh" id="autoRefresh">
                            <label class="relative inline-flex items-center cursor-pointer" for="autoRefresh">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-hidden peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900">{{ __('Auto Refresh') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date From') }}</label>
                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="dateFrom">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date To') }}</label>
                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="dateTo">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('KPI Type') }}</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="kpiType">
                                    <option value="all">{{ __('All KPIs') }}</option>
                                    <option value="revenue">{{ __('Revenue KPIs') }}</option>
                                    <option value="profitability">{{ __('Profitability KPIs') }}</option>
                                    <option value="efficiency">{{ __('Efficiency KPIs') }}</option>
                                    <option value="growth">{{ __('Growth KPIs') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Comparison Period') }}</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="comparisonPeriod">
                                    <option value="none">{{ __('No Comparison') }}</option>
                                    <option value="previous_month">{{ __('Previous Month') }}</option>
                                    <option value="previous_quarter">{{ __('Previous Quarter') }}</option>
                                    <option value="previous_year">{{ __('Previous Year') }}</option>
                                    <option value="same_period_last_year">{{ __('Same Period Last Year') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($loading)
                <div class="flex justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600" role="status">
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </div>
                </div>
            @else
                <!-- Revenue KPIs -->
                @if($kpiType === 'all' || $kpiType === 'revenue')
                    <div class="mb-6">
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <i class="ti ti-currency-dollar mr-2"></i>
                                    {{ __('Revenue KPIs') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                                    <i class="ti ti-cash text-white"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">
                                                    {{ __('Total Revenue') }}
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ format_currency($revenueKpis['total_revenue'] ?? 0) }}
                                                </div>
                                                @if($comparisonData && isset($comparisonData['revenue']['total_revenue']))
                                                    @php
                                                        $change = $revenueKpis['total_revenue'] - $comparisonData['revenue']['total_revenue'];
                                                        $changePercent = $comparisonData['revenue']['total_revenue'] > 0 ? ($change / $comparisonData['revenue']['total_revenue']) * 100 : 0;
                                                        $changeClass = $change >= 0 ? 'text-green-600' : 'text-red-600';
                                                        $changeIcon = $change >= 0 ? 'ti-trending-up' : 'ti-trending-down';
                                                    @endphp
                                                    <div class="{{ $changeClass }} text-sm flex items-center">
                                                        <i class="ti {{ $changeIcon }} mr-1"></i>
                                                        {{ number_format(abs($changePercent), 1) }}%
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="bg-white rounded-lg shadow p-6">
                                                <div class="flex items-center">
                                                    <div class="shrink-0">
                                                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                                            <i class="ti ti-chart-bar text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ __('Average Order Value') }}
                                                        </div>
                                                        <div class="text-2xl font-bold text-gray-900">
                                                            {{ format_currency($revenueKpis['avg_order_value'] ?? 0) }}
                                                        </div>
                                                        @if($comparisonData && isset($comparisonData['revenue']['avg_order_value']))
                                                            @php
                                                                $change = $revenueKpis['avg_order_value'] - $comparisonData['revenue']['avg_order_value'];
                                                                $changePercent = $comparisonData['revenue']['avg_order_value'] > 0 ? ($change / $comparisonData['revenue']['avg_order_value']) * 100 : 0;
                                                                $changeClass = $change >= 0 ? 'text-green-600' : 'text-red-600';
                                                                $changeIcon = $change >= 0 ? 'ti-trending-up' : 'ti-trending-down';
                                                            @endphp
                                                            <div class="{{ $changeClass }} text-sm flex items-center">
                                                                <i class="ti {{ $changeIcon }} mr-1"></i>
                                                                {{ number_format(abs($changePercent), 1) }}%
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-1">
                                            <div class="bg-white rounded-lg shadow p-6">
                                                <div class="flex items-center">
                                                    <div class="shrink-0">
                                                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                                            <i class="ti ti-shopping-cart text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ __('Total Orders') }}
                                                        </div>
                                                        <div class="text-2xl font-bold text-gray-900">
                                                            {{ number_format($revenueKpis['total_orders'] ?? 0) }}
                                                        </div>
                                                        @if($comparisonData && isset($comparisonData['revenue']['total_orders']))
                                                            @php
                                                                $change = $revenueKpis['total_orders'] - $comparisonData['revenue']['total_orders'];
                                                                $changePercent = $comparisonData['revenue']['total_orders'] > 0 ? ($change / $comparisonData['revenue']['total_orders']) * 100 : 0;
                                                                $changeClass = $change >= 0 ? 'text-green-600' : 'text-red-600';
                                                                $changeIcon = $change >= 0 ? 'ti-trending-up' : 'ti-trending-down';
                                                            @endphp
                                                            <div class="{{ $changeClass }} text-sm flex items-center">
                                                                <i class="ti {{ $changeIcon }} mr-1"></i>
                                                                {{ number_format(abs($changePercent), 1) }}%
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-1">
                                            <div class="bg-white rounded-lg shadow p-6">
                                                <div class="flex items-center">
                                                    <div class="shrink-0">
                                                        <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                                            <i class="ti ti-calendar text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ __('Daily Average') }}
                                                        </div>
                                                        <div class="text-2xl font-bold text-gray-900">
                                                            {{ format_currency($revenueKpis['daily_average'] ?? 0) }}
                                                        </div>
                                                        @if($comparisonData && isset($comparisonData['revenue']['daily_average']))
                                                            @php
                                                                $change = $revenueKpis['daily_average'] - $comparisonData['revenue']['daily_average'];
                                                                $changePercent = $comparisonData['revenue']['daily_average'] > 0 ? ($change / $comparisonData['revenue']['daily_average']) * 100 : 0;
                                                                $changeClass = $change >= 0 ? 'text-green-600' : 'text-red-600';
                                                                $changeIcon = $change >= 0 ? 'ti-trending-up' : 'ti-trending-down';
                                                            @endphp
                                                            <div class="{{ $changeClass }} text-sm flex items-center">
                                                                <i class="ti {{ $changeIcon }} mr-1"></i>
                                                                {{ number_format(abs($changePercent), 1) }}%
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Profitability KPIs -->
                @if($kpiType === 'all' || $kpiType === 'profitability')
                    <div class="mb-8">
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <i class="ti ti-chart-line mr-2"></i>
                                    {{ __('Profitability KPIs') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                                    <i class="ti ti-trending-up text-white"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ __('Gross Profit') }}
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ format_currency($profitabilityKpis['gross_profit'] ?? 0) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                                    <i class="ti ti-percentage text-white"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ __('Gross Margin') }}
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ number_format($profitabilityKpis['gross_margin'] ?? 0, 2) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                                    <i class="ti ti-calculator text-white"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ __('Net Profit') }}
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ format_currency($profitabilityKpis['net_profit'] ?? 0) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                                    <i class="ti ti-target text-white"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ __('ROI') }}
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ number_format($profitabilityKpis['roi'] ?? 0, 2) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Efficiency KPIs -->
                @if($kpiType === 'all' || $kpiType === 'efficiency')
                    <div class="mb-8">
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <i class="ti ti-gauge mr-2"></i>
                                    {{ __('Efficiency KPIs') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-purple-600 text-white rounded-full flex items-center justify-center">
                                                    <i class="ti ti-rotate"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">
                                                    {{ __('Inventory Turnover') }}
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ number_format($efficiencyKpis['inventory_turnover'] ?? 0, 2) }}x
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-teal-600 text-white rounded-full flex items-center justify-center">
                                                    <i class="ti ti-clock"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">
                                                    {{ __('Days Sales Outstanding') }}
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ number_format($efficiencyKpis['days_sales_outstanding'] ?? 0, 1) }} {{ __('days') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-orange-600 text-white rounded-full flex items-center justify-center">
                                                    <i class="ti ti-package"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">
                                                    {{ __('Stock Efficiency') }}
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ number_format($efficiencyKpis['stock_efficiency'] ?? 0, 2) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center">
                                                    <i class="ti ti-currency-dollar"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">
                                                    {{ __('Cost per Sale') }}
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ format_currency($efficiencyKpis['cost_per_sale'] ?? 0) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Growth KPIs -->
                @if($kpiType === 'all' || $kpiType === 'growth')
                    <div class="mb-8">
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <i class="ti ti-trending-up mr-2"></i>
                                    {{ __('Growth KPIs') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center">
                                                    <i class="ti ti-arrow-up"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">
                                                    {{ __('Revenue Growth') }}
                                                </div>
                                                <div class="text-2xl font-bold">
                                                    @php
                                                        $growth = $growthKpis['revenue_growth'] ?? 0;
                                                        $growthClass = $growth >= 0 ? 'text-green-600' : 'text-red-600';
                                                    @endphp
                                                    <span class="{{ $growthClass }}">
                                                        {{ number_format($growth, 2) }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center">
                                                    <i class="ti ti-users"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">
                                                    {{ __('Customer Growth') }}
                                                </div>
                                                <div class="text-2xl font-bold">
                                                    @php
                                                        $growth = $growthKpis['customer_growth'] ?? 0;
                                                        $growthClass = $growth >= 0 ? 'text-green-600' : 'text-red-600';
                                                    @endphp
                                                    <span class="{{ $growthClass }}">
                                                        {{ number_format($growth, 2) }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center">
                                                    <i class="ti ti-shopping-bag"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">
                                                    {{ __('Order Growth') }}
                                                </div>
                                                <div class="text-2xl font-bold">
                                                    @php
                                                        $growth = $growthKpis['order_growth'] ?? 0;
                                                        $growthClass = $growth >= 0 ? 'text-green-600' : 'text-red-600';
                                                    @endphp
                                                    <span class="{{ $growthClass }}">
                                                        {{ number_format($growth, 2) }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow p-6">
                                        <div class="flex items-center">
                                            <div class="shrink-0">
                                                <div class="w-12 h-12 bg-pink-600 text-white rounded-full flex items-center justify-center">
                                                    <i class="ti ti-chart-dots"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">
                                                    {{ __('Profit Growth') }}
                                                </div>
                                                <div class="text-2xl font-bold">
                                                    @php
                                                        $growth = $growthKpis['profit_growth'] ?? 0;
                                                        $growthClass = $growth >= 0 ? 'text-green-600' : 'text-red-600';
                                                    @endphp
                                                    <span class="{{ $growthClass }}">
                                                        {{ number_format($growth, 2) }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- KPI Trends Chart -->
                @if(!empty($kpiTrends))
                    <div class="mb-8">
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('KPI Trends') }}</h3>
                            </div>
                            <div class="p-6">
                                <div id="kpi-trends-chart" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Detailed KPI Table -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Detailed KPI Analysis') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('KPI Category') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Metric') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Current Value') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Target') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Performance') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Trend') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!-- Revenue KPIs -->
                                    @if($kpiType === 'all' || $kpiType === 'revenue')
                                        <tr>
                                            <td rowspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 align-middle">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ __('Revenue') }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ __('Total Revenue') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($revenueKpis['total_revenue'] ?? 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($revenueKpis['revenue_target'] ?? 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @php
                                                    $performance = ($revenueKpis['revenue_target'] ?? 0) > 0 ? (($revenueKpis['total_revenue'] ?? 0) / $revenueKpis['revenue_target']) * 100 : 0;
                                                    $performanceClass = $performance >= 100 ? 'bg-green-100 text-green-800' : ($performance >= 80 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $performanceClass }}">
                                                    {{ number_format($performance, 1) }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($comparisonData && isset($comparisonData['revenue']['total_revenue']))
                                                    @php
                                                        $change = $revenueKpis['total_revenue'] - $comparisonData['revenue']['total_revenue'];
                                                        $trendClass = $change >= 0 ? 'text-green-600' : 'text-red-600';
                                                        $trendIcon = $change >= 0 ? 'ti-trending-up' : 'ti-trending-down';
                                                    @endphp
                                                    <i class="ti {{ $trendIcon }} {{ $trendClass }}"></i>
                                                @else
                                                    <i class="ti ti-minus text-gray-400"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ __('Average Order Value') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($revenueKpis['avg_order_value'] ?? 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($revenueKpis['aov_target'] ?? 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @php
                                                    $performance = ($revenueKpis['aov_target'] ?? 0) > 0 ? (($revenueKpis['avg_order_value'] ?? 0) / $revenueKpis['aov_target']) * 100 : 0;
                                                    $performanceClass = $performance >= 100 ? 'bg-green-100 text-green-800' : ($performance >= 80 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $performanceClass }}">
                                                    {{ number_format($performance, 1) }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($comparisonData && isset($comparisonData['revenue']['avg_order_value']))
                                                    @php
                                                        $change = $revenueKpis['avg_order_value'] - $comparisonData['revenue']['avg_order_value'];
                                                        $trendClass = $change >= 0 ? 'text-green-600' : 'text-red-600';
                                                        $trendIcon = $change >= 0 ? 'ti-trending-up' : 'ti-trending-down';
                                                    @endphp
                                                    <i class="ti {{ $trendIcon }} {{ $trendClass }}"></i>
                                                @else
                                                    <i class="ti ti-minus text-gray-400"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ __('Total Orders') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($revenueKpis['total_orders'] ?? 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($revenueKpis['orders_target'] ?? 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @php
                                                    $performance = ($revenueKpis['orders_target'] ?? 0) > 0 ? (($revenueKpis['total_orders'] ?? 0) / $revenueKpis['orders_target']) * 100 : 0;
                                                    $performanceClass = $performance >= 100 ? 'bg-green-100 text-green-800' : ($performance >= 80 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $performanceClass }}">
                                                    {{ number_format($performance, 1) }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($comparisonData && isset($comparisonData['revenue']['total_orders']))
                                                    @php
                                                        $change = $revenueKpis['total_orders'] - $comparisonData['revenue']['total_orders'];
                                                        $trendClass = $change >= 0 ? 'text-green-600' : 'text-red-600';
                                                        $trendIcon = $change >= 0 ? 'ti-trending-up' : 'ti-trending-down';
                                                    @endphp
                                                    <i class="ti {{ $trendIcon }} {{ $trendClass }}"></i>
                                                @else
                                                    <i class="ti ti-minus text-gray-400"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ __('Daily Average') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($revenueKpis['daily_average'] ?? 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($revenueKpis['daily_target'] ?? 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @php
                                                    $performance = ($revenueKpis['daily_target'] ?? 0) > 0 ? (($revenueKpis['daily_average'] ?? 0) / $revenueKpis['daily_target']) * 100 : 0;
                                                    $performanceClass = $performance >= 100 ? 'bg-green-100 text-green-800' : ($performance >= 80 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $performanceClass }}">
                                                    {{ number_format($performance, 1) }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($comparisonData && isset($comparisonData['revenue']['daily_average']))
                                                    @php
                                                        $change = $revenueKpis['daily_average'] - $comparisonData['revenue']['daily_average'];
                                                        $trendClass = $change >= 0 ? 'text-green-600' : 'text-red-600';
                                                        $trendIcon = $change >= 0 ? 'ti-trending-up' : 'ti-trending-down';
                                                    @endphp
                                                    <i class="ti {{ $trendIcon }} {{ $trendClass }}"></i>
                                                @else
                                                    <i class="ti ti-minus text-gray-400"></i>
                                                @endif
                                            </td>
                                        </tr>
                                            @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        @if(!empty($kpiTrends))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const kpiTrends = @json($kpiTrends);
                    const dates = Object.keys(kpiTrends);
                    
                    const revenueData = dates.map(date => kpiTrends[date].revenue || 0);
                    const profitData = dates.map(date => kpiTrends[date].profit || 0);
                    const ordersData = dates.map(date => kpiTrends[date].orders || 0);

                    const options = {
                        series: [
                            {
                                name: '{{ __('Revenue') }}',
                                type: 'line',
                                data: revenueData
                            },
                            {
                                name: '{{ __('Profit') }}',
                                type: 'line',
                                data: profitData
                            },
                            {
                                name: '{{ __('Orders') }}',
                                type: 'column',
                                data: ordersData
                            }
                        ],
                        chart: {
                            height: 400,
                            type: 'line',
                            toolbar: {
                                show: false
                            }
                        },
                        stroke: {
                            width: [3, 3, 0],
                            curve: 'smooth'
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '50%'
                            }
                        },
                        xaxis: {
                            categories: dates,
                            type: 'datetime'
                        },
                        yaxis: [
                            {
                                title: {
                                    text: '{{ __('Amount') }}'
                                },
                                labels: {
                                    formatter: function (val) {
                                        return new Intl.NumberFormat('en-US', {
                                            style: 'currency',
                                            currency: 'USD'
                                        }).format(val);
                                    }
                                }
                            },
                            {
                                opposite: true,
                                title: {
                                    text: '{{ __('Orders') }}'
                                }
                            }
                        ],
                        colors: ['#206bc4', '#20c997', '#f59f00'],
                        legend: {
                            position: 'top'
                        }
                    };

                    const chart = new ApexCharts(document.querySelector('#kpi-trends-chart'), options);
                    chart.render();
                });
            </script>
        @endif

        @if($autoRefresh)
            <script>
                setInterval(function() {
                    @this.call('refreshKpis');
                }, 300000); // Refresh every 5 minutes
            </script>
        @endif
    @endpush
</div>