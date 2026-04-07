<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Analytics') }}
                    </div>
                    <h2 class="page-title">
                        {{ __('Revenue Reports') }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-download me-1"></i>
                                {{ __('Export') }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" wire:click="exportReport('json')">
                                    <i class="ti ti-file-code me-2"></i>
                                    {{ __('Export as JSON') }}
                                </a></li>
                                <li><a class="dropdown-item" href="#" wire:click="exportReport('csv')">
                                    <i class="ti ti-file-spreadsheet me-2"></i>
                                    {{ __('Export as CSV') }}
                                </a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-outline-primary" wire:click="loadRevenueData">
                            <i class="ti ti-refresh me-1"></i>
                            {{ __('Refresh') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('Report Type') }}</label>
                                    <x-select class="form-select" wire:model.live="reportType">
                                        <option value="daily">{{ __('Daily') }}</option>
                                        <option value="weekly">{{ __('Weekly') }}</option>
                                        <option value="monthly">{{ __('Monthly') }}</option>
                                        <option value="yearly">{{ __('Yearly') }}</option>
                                    </x-select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('Date From') }}</label>
                                    <input type="date" class="form-control" wire:model.live="dateFrom">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('Date To') }}</label>
                                    <input type="date" class="form-control" wire:model.live="dateTo">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('Category') }}</label>
                                    <x-select class="form-select" wire:model.live="categoryFilter">
                                        <option value="">{{ __('All Categories') }}</option>
                                        @foreach($this->categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">{{ __('Product') }}</label>
                                    <x-select class="form-select" wire:model.live="productFilter">
                                        <option value="">{{ __('All Products') }}</option>
                                        @foreach($this->products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                                        <i class="ti ti-x me-1"></i>
                                        {{ __('Reset') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($loading)
                <div class="d-flex justify-content-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </div>
                </div>
            @else
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                            <i class="ti ti-currency-dollar"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            {{ __('Total Revenue') }}
                                        </div>
                                        <div class="text-muted">
                                            {{ format_currency($revenueData['total_revenue'] ?? 0) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-success text-white avatar">
                                            <i class="ti ti-chart-line"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            {{ __('Avg Revenue') }}
                                        </div>
                                        <div class="text-muted">
                                            {{ format_currency($revenueData['average_revenue'] ?? 0) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-info text-white avatar">
                                            <i class="ti ti-trending-up"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            {{ __('Growth Rate') }}
                                        </div>
                                        <div class="text-muted">
                                            @php
                                                $growth = $revenueData['growth_rate'] ?? 0;
                                                $growthClass = $growth > 0 ? 'text-success' : ($growth < 0 ? 'text-danger' : 'text-muted');
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
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-warning text-white avatar">
                                            <i class="ti ti-calendar"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            {{ __('Report Period') }}
                                        </div>
                                        <div class="text-muted">
                                            {{ ucfirst($reportType) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Revenue Trend') }} ({{ ucfirst($reportType) }})</h3>
                            </div>
                            <div class="card-body">
                                @if(!empty($revenueData['period_data']))
                                    <div id="revenue-trend-chart" style="height: 400px;"></div>
                                @else
                                    <div class="empty">
                                        <div class="empty-img"><img src="{{ asset('assets/static/illustrations/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                                        </div>
                                        <p class="empty-title">{{ __('No revenue data available') }}</p>
                                        <p class="empty-subtitle text-muted">
                                            {{ __('Revenue data will appear here once you have sales in the selected period.') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Breakdown -->
                @if(!empty($revenueData['category_breakdown']) || !empty($revenueData['product_breakdown']))
                    <div class="row mb-4">
                        <!-- Category Breakdown -->
                        @if(!empty($revenueData['category_breakdown']))
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Revenue by Category') }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div id="category-breakdown-chart" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Top Products -->
                        @if(!empty($revenueData['product_breakdown']))
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Top Products by Revenue') }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group list-group-flush">
                                            @foreach(array_slice($revenueData['product_breakdown'], 0, 10) as $product)
                                                <div class="list-group-item d-flex align-items-center px-0">
                                                    <div class="flex-fill">
                                                        <div class="font-weight-medium">{{ $product['name'] }}</div>
                                                        <div class="text-muted small">{{ $product['code'] ?? '' }}</div>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="font-weight-medium">{{ format_currency($product['revenue']) }}</div>
                                                        <div class="text-muted small">{{ number_format($product['quantity']) }} {{ __('sold') }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Detailed Revenue Table -->
                @if(!empty($revenueData['period_data']))
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Detailed Revenue Report') }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Period') }}</th>
                                                    <th>{{ __('Revenue') }}</th>
                                                    <th>{{ __('Orders') }}</th>
                                                    <th>{{ __('Avg Order Value') }}</th>
                                                    <th>{{ __('Growth') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $previousRevenue = 0;
                                                @endphp
                                                @foreach($revenueData['period_data'] as $period => $data)
                                                    @php
                                                        $currentRevenue = $data['revenue'] ?? 0;
                                                        $growth = $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
                                                        $growthClass = $growth > 0 ? 'text-success' : ($growth < 0 ? 'text-danger' : 'text-muted');
                                                        $growthIcon = $growth > 0 ? 'ti-trending-up' : ($growth < 0 ? 'ti-trending-down' : 'ti-minus');
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="font-weight-medium">
                                                                @if($reportType === 'daily')
                                                                    {{ \Carbon\Carbon::parse($period)->format('M d, Y') }}
                                                                @elseif($reportType === 'weekly')
                                                                    {{ __('Week') }} {{ $period }}
                                                                @elseif($reportType === 'monthly')
                                                                    {{ \Carbon\Carbon::parse($period . '-01')->format('M Y') }}
                                                                @else
                                                                    {{ $period }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ format_currency($currentRevenue) }}</td>
                                                        <td>{{ number_format($data['orders'] ?? 0) }}</td>
                                                        <td>{{ format_currency($data['avg_order_value'] ?? 0) }}</td>
                                                        <td>
                                                            @if($previousRevenue > 0)
                                                                <span class="{{ $growthClass }}">
                                                                    <i class="ti {{ $growthIcon }} me-1"></i>
                                                                    {{ number_format(abs($growth), 2) }}%
                                                                </span>
                                                            @else
                                                                <span class="text-muted">-</span>
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
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @push('scripts')
        @if(!empty($revenueData['period_data']))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Revenue Trend Chart
                    const revenueData = @json($revenueData['period_data']);
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
                        const categoryData = @json($revenueData['category_breakdown']);
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
