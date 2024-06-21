<div>

    <div class="flex flex-wrap">
        <div class="lg:w-1/2 sm:w-full pb-2 mb-2 bg-white relative">
            <div class="flex w-full px-4 justify-between items-center">
                <h3>{{ __('Sales/Purchases') }}</h3>
            </div>
            <div id="chart"></div>
        </div>
        <div class="lg:w-1/2 sm:w-full pb-2 mb-2 bg-white relative">
            <div class="w-full px-4 justify-between items-center">
                <h3>{{ __('Monthly Cash Flow (Payment Sent & Received)') }}</h3>
            </div>
            <div id="monthly-chart"></div>
        </div>
        <div class="w-full pb-2 mb-2 bg-white relative">
            <div class="w-full px-4 justify-between items-center">
                <h3>{{ __('Daily Sales and Purchases') }}</h3>
            </div>
            <div id="daily-chart"></div>
        </div>
        <div class="w-full pb-2 mb-2 bg-white relative">
            <div class="w-full px-4 justify-between items-center">
                <h3>{{ __('Payment Chart') }}</h3>
            </div>
            <div id="payment-chart"></div>
        </div>
    </div>
    <div class="w-full px-2 pb-2 mt-2">
        <div class="bg-white rounded-lg border border-gray-200 pb-2">
            <div class="py-3 px-5 mb-3 w-full inline-flex itees-center justify-between">
                <span class="text-md font-semibold text-gray-700">{{ __('Recent Sale') }}</span>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-left px-4">{{ 'Customer' }}</th>
                        <th class="text-left px-4">{{ 'Total' }}</th>
                        <th class="text-left px-4">{{ 'Date' }}</th>
                        <th class="text-left px-4">{{ 'Status' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lastSales as $sale)
                        <tr class="text-sm antialiased">
                            <td class="px-4 py-2">
                                <p class="font-bold tracking-wide text-gray-800">{{ $sale->customer?->name }}
                                </p>
                                <span class="text-indigo-600 text-xs font-semibold">{{ $sale->reference }}</span>
                            </td>
                            <td class="px-4 py-2">{{ format_currency($sale->total_amount) }}</td>
                            <td class="px-4 py-2">{{ $sale->date }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $type = $sale->status->getBadgeType();
                                @endphp
                                <x-badge :type="$type">{{ $sale->status->label() }}</x-badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-2 w-full pb-2">
        <div class="bg-white rounded-lg border border-gray-200 pb-2">
            <div class="py-3 px-5 mb-3 w-full inline-flex items-center justify-between">
                <span class="text-md font-semibold text-gray-700">{{ __('Recent Purchase') }}</span>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-left px-4">{{ 'Supplier' }}</th>
                        <th class="text-left px-4">{{ 'Total' }}</th>
                        <th class="text-left px-4">{{ 'Date' }}</th>
                        <th class="text-left px-4">{{ 'Status' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lastPurchases as $purchase)
                        <tr class="text-sm antialiased">
                            <td class="px-4 py-2">
                                <a href="{{ route('supplier.details', $purchase->supplier->id) }}"
                                    class="text-indigo-500 hover:text-indigo-600 
                                    font-bold tracking-wide">
                                    {{ $purchase->supplier->name }}
                                </a>
                                <span class="text-indigo-600 text-xs font-semibold">{{ $purchase->reference }}</span>
                            </td>
                            <td class="px-4 py-2">{{ format_currency($purchase->total_amount) }}</td>
                            <td class="px-4 py-2">{{ $purchase->date }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $badgeType = $purchase->status->getBadgeType();
                                @endphp

                                <x-badge :type="$badgeType">{{ $purchase->status->label() }}</x-badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex flex-wrap gap-y-4">
        <div class="sm:w-1/2 w-full px-2">
            <div class="bg-white rounded-lg border border-gray-200 pb-2">
                <div class="py-3 px-5 w-full inline-flex items-center justify-between text-gray-700">
                    <span class="text-md font-semibold">{{ __('Top 5 Sellers in') }} {{ now()->format('F') }}</span>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Seller') }}</th>
                            <th>{{ __('Profit') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Sale Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bestSales as $sale)
                            <tr class="antialiased" wire:key="{{ $sale->id }}">
                                <td class="py-1 px-2">{{ $sale->user->name }}</td>
                                <td class="py-1 px-2">{{ format_currency($sale->total_amount) }}</td>
                                <td class="py-1 px-2">{{ $sale->customer->name }}</td>
                                <td class="py-1 px-2">{{ $sale->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="sm:w-1/2 w-full px-2">
            <div class="bg-white rounded-lg border border-gray-200 pb-2">
                <div class="py-3 px-5 w-full inline-flex items-center justify-between text-gray-700">
                    <span class="text-md font-semibold">{{ __('Top Products in') }} {{ now()->format('F') }}</span>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Product Name') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Total Quantity') }}</th>
                            <th>{{ __('Total Sales') }}</th>
                            <th>{{ __('Warehouse') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->topProducts as $product)
                            <tr class="antialiased" wire:key="{{ $product->id }}">
                                <td class="py-1 px-2">{{ $product->name }}</td>
                                <td class="py-1 px-2">{{ $product->code }}</td>
                                <td class="py-1 px-2">{{ $product->qtyItem }}</td>
                                <td class="py-1 px-2">{{ format_currency($product->totalSalesAmount) }}</td>
                                <td class="py-1 px-2">
                                    <x-badge danger
                                        class="text-sm">{{ \App\Helpers::warehouseName($product->warehouse_id) }}</x-badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <div class="sm:w-1/2 w-full px-2">
            <div class="bg-white rounded-lg border border-gray-200 pb-2">
                <div class="py-3 px-5 w-full inline-flex items-center justify-between text-gray-700">
                    <span class="text-md font-semibold">{{ __('Top Customers by Warehouse') }}</span>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>{{ __('Warehouse') }}</th>
                            <th>{{ __('Top Customer') }}</th>
                            <th>{{ __('Total Sales') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->topCustomers as $index => $customer)
                            <tr class="antialiased" wire:key="{{ $index }}">
                                <td>{{ $index }}</td>
                                <td class="py-1 px-2">
                                    <x-badge danger
                                        class="text-sm">{{ \App\Helpers::warehouseName($customer->warehouse_id) }}</x-badge>
                                </td>

                                <td class="py-1 px-2">{{ $customer->name }}</td>

                                <td class="py-1 px-2">{{ format_currency($customer->totalSalesAmount) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @script
        <script>
            document.addEventListener('livewire:init', () => {
                var dailyChart = new ApexCharts(document.querySelector("#daily-chart"), @json($this->dailyChartOptions));
                console.log(dailyChart);
                dailyChart.render();

                // Render the monthly cash flow chart
                var monthlyChart = new ApexCharts(document.querySelector("#monthly-chart"),
                    @json($this->monthlyChartOptions));

                monthlyChart.render();

                var paymentChart = new ApexCharts(document.querySelector("#payment-chart"),
                    @json($this->paymentChart));
                paymentChart.render();

                function chart(data, selector) {
                    let tes = data;
                    let options = {
                        series: [{
                                name: "Sales Total Amount",
                                data: tes.total.sales
                            },
                            {
                                name: "Sales Due Amount",
                                data: tes.due_amount.sales
                            },
                            {
                                name: "Purchase Total Amount",
                                data: tes.total.purchase
                            },
                            {
                                name: "Purchase Due Amount",
                                data: tes.due_amount.purchase
                            }
                        ],
                        chart: {
                            height: 350,
                            width: '100%',
                            type: "bar",
                            zoom: {
                                enabled: false
                            }
                        },
                        responsive: [{
                            breakpoint: undefined,
                            options: {},
                        }],
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                borderRadius: 4,
                                dataLabels: {
                                    position: "top"
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            offsetY: -20,
                            style: {
                                fontSize: "12px",
                                colors: ["#fff"],
                            },
                            formatter: function(val, opt) {
                                return opt.w.globals.labels[opt.dataPointIndex] + ": " + val;
                            },
                        },
                        stroke: {
                            show: true,
                            width: 1,
                            colors: ["#fff"]
                        },
                        markers: {
                            size: 5,
                            colors: ["#1a56db"],
                            strokeColor: "#ffffff",
                            strokeWidth: 3
                        },
                        xaxis: {
                            categories: tes.labels,
                            labels: {
                                style: {
                                    colors: "#1a56db"
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: "Amount",
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return "$" + val;
                                },
                            },
                        },
                        legend: {
                            position: "top",
                            horizontalAlign: "center",
                            offsetX: 40,
                        },
                    };
                    const chart = new ApexCharts(document.querySelector(selector), options);
                    chart.render();
                }
                chart({!! $charts !!}, '#chart');
            })
        </script>
    @endscript

</div>
