<div>
    <div class="print:hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <div>
                    <div class="text-sm text-gray-500 mb-1">
                        {{ __('Finance') }}
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ __('Financial Dashboard') }}
                    </h2>
                </div>
                <div class="print:hidden">
                    <div class="flex space-x-2">
                        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium flex items-center" wire:click="exportReport">
                            <i class="ti ti-download mr-1"></i>
                            {{ __('Export Report') }}
                        </button>
                        <button type="button" class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md font-medium flex items-center" wire:click="refreshData">
                            <i class="ti ti-refresh mr-1"></i>
                            {{ __('Refresh') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date Range') }}</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="dateRange">
                        <option value="today">{{ __('Today') }}</option>
                        <option value="week">{{ __('This Week') }}</option>
                        <option value="month">{{ __('This Month') }}</option>
                        <option value="quarter">{{ __('This Quarter') }}</option>
                        <option value="year">{{ __('This Year') }}</option>
                        <option value="custom">{{ __('Custom Range') }}</option>
                    </select>
                </div>
                @if($dateRange === 'custom')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Start Date') }}</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="startDate">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('End Date') }}</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" wire:model.live="endDate">
                </div>
                @endif
            </div>

            @if($loading)
                <div class="flex justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600" role="status">
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </div>
                </div>
            @else
                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-currency-dollar text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">
                                    {{ __('Total Revenue') }}
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ format_currency($this->kpiData['total_revenue'] ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-chart-line text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">
                                    {{ __('Net Profit') }}
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ format_currency($this->kpiData['net_profit'] ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="w-12 h-12 bg-cyan-600 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-percentage text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">
                                    {{ __('Profit Margin') }}
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($this->kpiData['profit_margin'] ?? 0, 2) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-trending-up text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">
                                    {{ __('ROI') }}
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($this->kpiData['roi'] ?? 0, 2) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Financial Performance Chart -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Financial Performance') }}</h3>
                            </div>
                            <div class="p-6">
                                @if(!empty($this->financialReports['monthly_data']))
                                    <div id="financial-performance-chart" style="height: 350px;"></div>
                                @else
                                    <div class="text-center py-8">
                                        <div class="mb-4"><img src="{{ asset('assets/static/illustrations/undraw_finance_0bdk.svg') }}" height="128" alt="" class="mx-auto">
                                        </div>
                                        <p class="text-lg font-medium text-gray-900 mb-2">{{ __('No financial data available') }}</p>
                                        <p class="text-gray-500">
                                            {{ __('Financial performance data will appear here once you have transactions.') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Break-Even Analysis -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Break-Even Analysis') }}</h3>
                            </div>
                            <div class="p-6">
                                @if(!empty($this->breakEvenData))
                                    <div class="mb-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <div class="text-sm text-gray-500">{{ __('Break-Even Point') }}</div>
                                                <div class="text-2xl font-bold text-gray-900">{{ number_format($this->breakEvenData['break_even_units'] ?? 0) }}</div>
                                                <div class="text-xs text-gray-500">{{ __('units') }}</div>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-500">{{ __('Break-Even Revenue') }}</div>
                                                <div class="text-2xl font-bold text-gray-900">{{ format_currency($this->breakEvenData['break_even_revenue'] ?? 0) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                        @php
                                            $currentUnits = $this->breakEvenData['current_units'] ?? 0;
                                            $breakEvenUnits = $this->breakEvenData['break_even_units'] ?? 1;
                                            $progressPercentage = min(100, ($currentUnits / $breakEvenUnits) * 100);
                                        @endphp
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                    </div>
                                    <div class="text-sm text-gray-500 mb-4">
                                        {{ number_format($currentUnits) }} / {{ number_format($breakEvenUnits) }} {{ __('units sold') }}
                                        ({{ number_format($progressPercentage, 1) }}%)
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-gray-700">{{ __('Fixed Costs') }}:</span>
                                            <span class="text-sm text-gray-900">{{ format_currency($this->breakEvenData['fixed_costs'] ?? 0) }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-gray-700">{{ __('Variable Cost/Unit') }}:</span>
                                            <span class="text-sm text-gray-900">{{ format_currency($this->breakEvenData['variable_cost_per_unit'] ?? 0) }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-gray-700">{{ __('Selling Price/Unit') }}:</span>
                                            <span class="text-sm text-gray-900">{{ format_currency($this->breakEvenData['selling_price_per_unit'] ?? 0) }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <p class="text-lg font-medium text-gray-900">{{ __('No break-even data available') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gross Margin Analysis -->
                @if(!empty($this->grossMarginData))
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Gross Margin Analysis') }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="h2 text-primary">{{ number_format($this->grossMarginData['overall_margin'] ?? 0, 2) }}%</div>
                                                <div class="text-muted">{{ __('Overall Gross Margin') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="h2 text-success">{{ format_currency($this->grossMarginData['total_gross_profit'] ?? 0) }}</div>
                                                <div class="text-muted">{{ __('Total Gross Profit') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="h2 text-info">{{ format_currency($this->grossMarginData['total_revenue'] ?? 0) }}</div>
                                                <div class="text-muted">{{ __('Total Revenue') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="h2 text-warning">{{ format_currency($this->grossMarginData['total_cogs'] ?? 0) }}</div>
                                                <div class="text-muted">{{ __('Total COGS') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($this->grossMarginData['product_margins']))
                                        <div class="table-responsive">
                                            <table class="table table-vcenter">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Product') }}</th>
                                                        <th>{{ __('Revenue') }}</th>
                                                        <th>{{ __('COGS') }}</th>
                                                        <th>{{ __('Gross Profit') }}</th>
                                                        <th>{{ __('Margin %') }}</th>
                                                        <th>{{ __('Performance') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($this->grossMarginData['product_margins'] as $product)
                                                        <tr>
                                                            <td>
                                                                <div class="font-weight-medium">{{ $product['name'] }}</div>
                                                                <div class="text-muted small">{{ $product['code'] ?? '' }}</div>
                                                            </td>
                                                            <td>{{ format_currency($product['revenue'] ?? 0) }}</td>
                                                            <td>{{ format_currency($product['cogs'] ?? 0) }}</td>
                                                            <td>{{ format_currency($product['gross_profit'] ?? 0) }}</td>
                                                            <td>
                                                                @php
                                                                    $margin = $product['margin_percentage'] ?? 0;
                                                                    $marginClass = $margin > 30 ? 'text-success' : ($margin > 15 ? 'text-warning' : 'text-danger');
                                                                @endphp
                                                                <span class="{{ $marginClass }} font-weight-medium">
                                                                    {{ number_format($margin, 2) }}%
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $performance = $margin > 30 ? 'Excellent' : ($margin > 15 ? 'Good' : 'Poor');
                                                                    $badgeClass = $margin > 30 ? 'bg-success' : ($margin > 15 ? 'bg-warning' : 'bg-danger');
                                                                @endphp
                                                                <span class="badge {{ $badgeClass }}">
                                                                    {{ __($performance) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Financial Summary -->
                @if(!empty($this->financialReports))
                    <div class="bg-white rounded-lg shadow mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Financial Summary') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-base font-medium text-gray-900 mb-4">{{ __('Income Statement Summary') }}</h4>
                                    <dl class="space-y-3">
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-700">{{ __('Total Revenue') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ format_currency($this->financialReports['total_revenue'] ?? 0) }}</dd>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-700">{{ __('Total Expenses') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ format_currency($this->financialReports['total_expenses'] ?? 0) }}</dd>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-700">{{ __('Gross Profit') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ format_currency($this->financialReports['gross_profit'] ?? 0) }}</dd>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-700">{{ __('Net Income') }}:</dt>
                                            <dd class="text-sm">
                                                @php
                                                    $netIncome = $this->financialReports['net_income'] ?? 0;
                                                    $incomeClass = $netIncome > 0 ? 'text-green-600' : 'text-red-600';
                                                @endphp
                                                <span class="{{ $incomeClass }} font-medium">
                                                    {{ format_currency($netIncome) }}
                                                </span>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                                <div>
                                    <h4 class="text-base font-medium text-gray-900 mb-4">{{ __('Key Ratios') }}</h4>
                                    <dl class="space-y-3">
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-700">{{ __('Gross Margin') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ number_format($this->financialReports['gross_margin'] ?? 0, 2) }}%</dd>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-700">{{ __('Operating Margin') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ number_format($this->financialReports['operating_margin'] ?? 0, 2) }}%</dd>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-700">{{ __('Net Margin') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ number_format($this->financialReports['net_margin'] ?? 0, 2) }}%</dd>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-700">{{ __('Expense Ratio') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ number_format($this->financialReports['expense_ratio'] ?? 0, 2) }}%</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @push('scripts')
        @if(!empty($this->financialReports['monthly_data']))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const monthlyData = @json($this->financialReports['monthly_data']);
                    const months = Object.keys(monthlyData);
                    const revenues = months.map(month => monthlyData[month].revenue || 0);
                    const expenses = months.map(month => monthlyData[month].expenses || 0);
                    const profits = months.map(month => (monthlyData[month].revenue || 0) - (monthlyData[month].expenses || 0));

                    const options = {
                        series: [
                            {
                                name: '{{ __('Revenue') }}',
                                type: 'column',
                                data: revenues
                            },
                            {
                                name: '{{ __('Expenses') }}',
                                type: 'column',
                                data: expenses
                            },
                            {
                                name: '{{ __('Net Profit') }}',
                                type: 'line',
                                data: profits
                            }
                        ],
                        chart: {
                            height: 350,
                            type: 'line',
                            toolbar: {
                                show: false
                            }
                        },
                        stroke: {
                            width: [0, 0, 4],
                            curve: 'smooth'
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '50%'
                            }
                        },
                        xaxis: {
                            categories: months
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
                        colors: ['#206bc4', '#f59f00', '#20c997'],
                        legend: {
                            position: 'top'
                        }
                    };

                    const chart = new ApexCharts(document.querySelector('#financial-performance-chart'), options);
                    chart.render();
                });
            </script>
        @endif
    @endpush
</div>