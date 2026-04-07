<div>
    <div class="print:hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <div>
                    <div class="text-sm text-gray-500 mb-1">
                        {{ __('Analytics') }}
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ __('Revenue Reports') }}
                    </h2>
                </div>
                <div class="flex space-x-2">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium flex items-center">
                            <i class="ti ti-download mr-1"></i>
                            {{ __('Export') }}
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            <a href="#" wire:click.prevent="exportReport('json')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <i class="ti ti-file-code mr-2"></i>
                                {{ __('Export as JSON') }}
                            </a>
                            <a href="#" wire:click.prevent="exportReport('csv')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <i class="ti ti-file-spreadsheet mr-2"></i>
                                {{ __('Export as CSV') }}
                            </a>
                        </div>
                    </div>
                    <button type="button" class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md font-medium flex items-center" wire:click="loadRevenueData">
                        <i class="ti ti-refresh mr-1"></i>
                        {{ __('Refresh') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Report Type') }}</label>
                        <x-select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.live="reportType">
                            <option value="daily">{{ __('Daily') }}</option>
                            <option value="weekly">{{ __('Weekly') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                            <option value="yearly">{{ __('Yearly') }}</option>
                        </x-select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date From') }}</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.live="dateFrom">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date To') }}</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.live="dateTo">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Category') }}</label>
                        <x-select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.live="categoryFilter">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($this->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Product') }}</label>
                        <x-select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.live="productFilter">
                            <option value="">{{ __('All Products') }}</option>
                            @foreach($this->products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="w-full border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md font-medium flex items-center justify-center" wire:click="resetFilters">
                            <i class="ti ti-x mr-1"></i>
                            {{ __('Reset') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full mr-4">
                            <i class="ti ti-currency-dollar text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Total Revenue') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ format_currency($revenueData['total_revenue'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full mr-4">
                            <i class="ti ti-chart-line text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Avg Revenue') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ format_currency($revenueData['average_revenue'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-100 rounded-full mr-4">
                            <i class="ti ti-trending-up text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Growth Rate') }}</p>
                            @php
                                $growth = $revenueData['growth_rate'] ?? 0;
                                $growthClass = $growth > 0 ? 'text-green-600' : ($growth < 0 ? 'text-red-600' : 'text-gray-500');
                            @endphp
                            <p class="text-2xl font-bold {{ $growthClass }}">
                                {{ number_format($growth, 2) }}%
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full mr-4">
                            <i class="ti ti-calendar text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ __('Report Period') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ ucfirst($reportType) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Revenue Trend') }} ({{ ucfirst($reportType) }})</h3>
                </div>
                <div class="p-6">
                    @if(!empty($revenueData['period_data']))
                        <div id="revenue-trend-chart" style="height: 400px;"></div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                                <i class="ti ti-chart-line text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No revenue data available') }}</h3>
                            <p class="text-gray-500">
                                {{ __('Revenue data will appear here once you have sales in the selected period.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Revenue Breakdown -->
            @if(!empty($revenueData['category_breakdown']) || !empty($revenueData['product_breakdown']))
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Category Breakdown -->
                    @if(!empty($revenueData['category_breakdown']))
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Revenue by Category') }}</h3>
                            </div>
                            <div class="p-6">
                                <div id="category-breakdown-chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Top Products -->
                    @if(!empty($revenueData['product_breakdown']))
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Top Products by Revenue') }}</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach(array_slice($revenueData['product_breakdown'], 0, 10) as $product)
                                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $product['name'] }}</div>
                                                <div class="text-xs text-gray-500">{{ $product['code'] ?? '' }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-medium text-gray-900">{{ format_currency($product['revenue']) }}</div>
                                                <div class="text-xs text-gray-500">{{ number_format($product['quantity']) }} {{ __('sold') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Detailed Revenue Table -->
            @if(!empty($revenueData['period_data']))
                <div class="bg-white rounded-lg shadow mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Detailed Revenue Report') }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Period') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Revenue') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Orders') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Avg Order Value') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Growth') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $previousRevenue = 0;
                                @endphp
                                @foreach($revenueData['period_data'] as $period => $data)
                                    @php
                                        $currentRevenue = $data['revenue'] ?? 0;
                                        $growth = $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
                                        $growthClass = $growth > 0 ? 'text-green-600' : ($growth < 0 ? 'text-red-600' : 'text-gray-500');
                                        $growthIcon = $growth > 0 ? 'ti-trending-up' : ($growth < 0 ? 'ti-trending-down' : 'ti-minus');
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            @if($reportType === 'daily')
                                                {{ \Carbon\Carbon::parse($period)->format('M d, Y') }}
                                            @elseif($reportType === 'weekly')
                                                {{ __('Week') }} {{ $period }}
                                            @elseif($reportType === 'monthly')
                                                {{ \Carbon\Carbon::parse($period . '-01')->format('M Y') }}
                                            @else
                                                {{ $period }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($currentRevenue) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($data['orders'] ?? 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ format_currency($data['avg_order_value'] ?? 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($previousRevenue > 0)
                                                <span class="inline-flex items-center text-sm {{ $growthClass }}">
                                                    <i class="ti {{ $growthIcon }} mr-1"></i>
                                                    {{ number_format(abs($growth), 2) }}%
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $previousRevenue = $currentRevenue;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        @if(!empty($revenueData['period_data']))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Revenue Trend Chart
                    const revenueData = @js($revenueData['period_data']);
                    const periods = Object.keys(revenueData);
                    const revenues = periods.map(period => revenueData[period].revenue || 0);
                    const orders = periods.map(period => revenueData[period].orders || 0);

                    const trendOptions = {
                        series: [
                            {
                                name: '{{ __('Revenue') }}',
                                type: 'area',
                                data: revenues
                            },
                            {
                                name: '{{ __('Orders') }}',
                                type: 'line',
                                data: orders
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
                            width: [0, 4],
                            curve: 'smooth'
                        },
                        fill: {
                            type: ['gradient', 'solid'],
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.7,
                                opacityTo: 0.3,
                                stops: [0, 90, 100]
                            }
                        },
                        xaxis: {
                            categories: periods
                        },
                        yaxis: [
                            {
                                title: {
                                    text: '{{ __('Revenue') }}'
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
                        colors: ['#206bc4', '#f59f00']
                    };

                    const trendChart = new ApexCharts(document.querySelector('#revenue-trend-chart'), trendOptions);
                    trendChart.render();

                    @if(!empty($revenueData['category_breakdown']))
                        // Category Breakdown Chart
                        const categoryData = {{ Js::from($revenueData['category_breakdown']) }};
                        const categoryNames = categoryData.map(item => item.name);
                        const categoryRevenues = categoryData.map(item => item.revenue);

                        const categoryOptions = {
                            series: categoryRevenues,
                            chart: {
                                type: 'donut',
                                height: 300
                            },
                            labels: categoryNames,
                            colors: ['#206bc4', '#f59f00', '#d63384', '#20c997', '#6f42c1', '#fd7e14'],
                            legend: {
                                position: 'bottom'
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '70%'
                                    }
                                }
                            }
                        };

                        const categoryChart = new ApexCharts(document.querySelector('#category-breakdown-chart'), categoryOptions);
                        categoryChart.render();
                    @endif
                });
            </script>
        @endif
    @endpush
</div>
