<div>
    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <p class="text-sm text-gray-500">{{ __('Analytics') }}</p>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Product Analytics') }}</h1>
                </div>
                <div class="flex space-x-3">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:click="exportAnalytics">
                        <i class="ti ti-download mr-2"></i>
                        {{ __('Export Analytics') }}
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150" wire:click="loadAnalytics">
                        <i class="ti ti-refresh mr-2"></i>
                        {{ __('Refresh') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Product') }}</label>
                            <x-select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="productId">
                                <option value="">{{ __('Select Product') }}</option>
                                @foreach($this->products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date From') }}</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="dateFrom">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date To') }}</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="dateTo">
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150" wire:click="resetFilters">
                                <i class="ti ti-x mr-2"></i>
                                {{ __('Reset') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @if($productId && !empty($analyticsData))
                <!-- Product Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-600 rounded-md flex items-center justify-center">
                                        <i class="ti ti-shopping-cart text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">{{ __('Total Sales') }}</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($analyticsData['total_quantity'] ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-600 rounded-md flex items-center justify-center">
                                        <i class="ti ti-currency-dollar text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">{{ __('Total Revenue') }}</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ format_currency($analyticsData['total_revenue'] ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-indigo-600 rounded-md flex items-center justify-center">
                                        <i class="ti ti-chart-line text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">{{ __('Avg Price') }}</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ format_currency($analyticsData['average_price'] ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-600 rounded-md flex items-center justify-center">
                                        <i class="ti ti-trending-up text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">{{ __('Growth Rate') }}</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($analyticsData['growth_rate'] ?? 0, 2) }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Sales Trend Chart -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Sales Trend') }}</h3>
                            </div>
                            <div class="p-6">
                                @if(!empty($analyticsData['daily_sales']))
                                    <div id="sales-trend-chart" style="height: 300px;"></div>
                                @else
                                    <div class="text-center py-12">
                                        <div class="mx-auto h-32 w-32 text-gray-400">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </div>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('No sales data available') }}</h3>
                                        <p class="mt-2 text-sm text-gray-500">
                                            {{ __('Sales data will appear here once you have sales for this product.') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Price Trend Chart -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Price History') }}</h3>
                            </div>
                            <div class="p-6">
                                @if(!empty($priceTrends['price_history']))
                                    <div id="price-history-chart" style="height: 300px;"></div>
                                @else
                                    <div class="text-center py-12">
                                        <h3 class="text-lg font-medium text-gray-900">{{ __('No price history available') }}</h3>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comparison Section -->
                @if($showComparison)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Product Comparison') }}</h3>
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" wire:click="toggleComparison">
                                    <i class="ti ti-x mr-2"></i>
                                    {{ __('Hide Comparison') }}
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Add Comparison Product -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="md:col-span-2">
                                    <x-select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model="newComparisonProduct">
                                        <option value="">{{ __('Select product to compare') }}</option>
                                        @foreach($products as $product)
                                            @if($product->id != $productId && !in_array($product->id, $comparisonProducts))
                                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                                            @endif
                                        @endforeach
                                    </x-select>
                                </div>
                                <div>
                                    <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50" wire:click="addComparisonProduct" @if(!$newComparisonProduct) disabled @endif>
                                        <i class="ti ti-plus mr-2"></i>
                                        {{ __('Add to Comparison') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Comparison Table -->
                            @if(!empty($comparisonData))
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Product') }}</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Sales') }}</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Revenue') }}</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Avg Price') }}</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Growth Rate') }}</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($comparisonData as $data)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $data['product_name'] }}</div>
                                                        <div class="text-sm text-gray-500">{{ $data['product_code'] }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($data['total_quantity'] ?? 0) }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($data['total_revenue'] ?? 0) }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($data['average_price'] ?? 0) }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @php
                                                            $growth = $data['growth_rate'] ?? 0;
                                                            $growthClass = $growth > 0 ? 'text-green-600' : ($growth < 0 ? 'text-red-600' : 'text-gray-500');
                                                        @endphp
                                                        <span class="{{ $growthClass }} font-medium">
                                                            {{ number_format($growth, 2) }}%
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        @if($data['product_id'] != $productId)
                                                            <button type="button" class="inline-flex items-center px-2 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" wire:click="removeComparisonProduct({{ $data['product_id'] }})">
                                                                <i class="ti ti-x"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                        <div class="p-6 text-center">
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150" wire:click="toggleComparison">
                                <i class="ti ti-chart-dots mr-2"></i>
                                {{ __('Compare with Other Products') }}
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Detailed Analytics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Detailed Analytics') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-base font-medium text-gray-900 mb-4">{{ __('Performance Metrics') }}</h4>
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Best Selling Day') }}:</dt>
                                        <dd class="text-sm text-gray-900">{{ $analyticsData['best_selling_day'] ?? '-' }}</dd>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Peak Sales Period') }}:</dt>
                                        <dd class="text-sm text-gray-900">{{ $analyticsData['peak_period'] ?? '-' }}</dd>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Conversion Rate') }}:</dt>
                                        <dd class="text-sm text-gray-900">{{ number_format($analyticsData['conversion_rate'] ?? 0, 2) }}%</dd>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Return Rate') }}:</dt>
                                        <dd class="text-sm text-gray-900">{{ number_format($analyticsData['return_rate'] ?? 0, 2) }}%</dd>
                                    </div>
                                </dl>
                            </div>
                            <div>
                                <h4 class="text-base font-medium text-gray-900 mb-4">{{ __('Price Analysis') }}</h4>
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Current Price') }}:</dt>
                                        <dd class="text-sm text-gray-900">{{ format_currency($priceTrends['current_price'] ?? 0) }}</dd>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Highest Price') }}:</dt>
                                        <dd class="text-sm text-gray-900">{{ format_currency($priceTrends['highest_price'] ?? 0) }}</dd>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Lowest Price') }}:</dt>
                                        <dd class="text-sm text-gray-900">{{ format_currency($priceTrends['lowest_price'] ?? 0) }}</dd>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Price Volatility') }}:</dt>
                                        <dd class="text-sm text-gray-900">{{ number_format($priceTrends['volatility'] ?? 0, 2) }}%</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="mx-auto h-32 w-32 text-gray-400 mb-6">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Select a product to view analytics') }}</h3>
                        <p class="text-sm text-gray-500">
                            {{ __('Choose a product from the dropdown above to see detailed analytics and insights.') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        @if($productId && !empty($analyticsData['daily_sales']))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Sales Trend Chart
                    const salesData = @js($analyticsData['daily_sales']);
                    const salesDates = Object.keys(salesData);
                    const salesValues = Object.values(salesData);

                    const salesOptions = {
                        series: [{
                            name: '{{ __('Sales Quantity') }}',
                            data: salesValues
                        }],
                        chart: {
                            type: 'line',
                            height: 300,
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        xaxis: {
                            categories: salesDates,
                            type: 'datetime'
                        },
                        colors: ['#206bc4']
                    };

                    const salesChart = new ApexCharts(document.querySelector('#sales-trend-chart'), salesOptions);
                    salesChart.render();

                    @if(!empty($priceTrends['price_history']))
                        // Price History Chart
                        const priceData = @js($priceTrends['price_history']);
                        const priceDates = Object.keys(priceData);
                        const priceValues = Object.values(priceData);

                        const priceOptions = {
                            series: [{
                                name: '{{ __('Price') }}',
                                data: priceValues
                            }],
                            chart: {
                                type: 'area',
                                height: 300,
                                toolbar: {
                                    show: false
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'smooth',
                                width: 2
                            },
                            xaxis: {
                                categories: priceDates,
                                type: 'datetime'
                            },
                            yaxis: {
                                labels: {
                                    formatter: function (val) {
                                        return new Intl.NumberFormat('en-US', {
                                            style: 'currency',
                                            currency: 'USD'
                                        }).format(val);
                                    }
                                }
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 1,
                                    opacityFrom: 0.7,
                                    opacityTo: 0.3,
                                    stops: [0, 90, 100]
                                }
                            },
                            colors: ['#f59f00']
                        };

                        const priceChart = new ApexCharts(document.querySelector('#price-history-chart'), priceOptions);
                        priceChart.render();
                    @endif
                });
            </script>
        @endif
    @endpush
</div>
