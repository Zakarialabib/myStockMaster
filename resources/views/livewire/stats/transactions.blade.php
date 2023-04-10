<div>
    <div>
        <div class="flex flex-row flex-wrap px-2 py-3">
            <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                <x-counter-card color="blue" counter="{{ $categoriesCount }}" title="{{ __('Total Categories') }}"
                    href="{{ route('product-categories.index') }}">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </x-counter-card>
            </div>
            <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                <x-counter-card color="orange" counter="{{ $productCount }}" title="{{ __('Total Products') }}"
                    href="{{ route('products.index') }}">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </x-counter-card>
            </div>
            <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                <x-counter-card color="green" counter="{{ $supplierCount }}" title="{{ __('Total Supplier') }}"
                    href="{{ route('suppliers.index') }}">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                    </path>
                </x-counter-card>
            </div>
            <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                <x-counter-card color="indigo" counter="{{ $customerCount }}" title="{{ __('Total Customer') }}"
                    href="{{ route('customers.index') }}">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </x-counter-card>
            </div>
            <div class="px-2 pb-2 w-full sm:w-1/2">
                <div class="card bg-white">
                    <div class="flex w-full px-4 justify-between items-center">
                        <h3>{{ __('Sales') }}</h3>
                    </div>
                    <div id="chart2"></div>
                </div>
            </div>
            <div class="px-2 pb-2 w-full sm:w-1/2">
                <div class="card bg-white">
                    <div class="flex w-full px-4 justify-between items-center">
                        <h3>{{ __('Purchases') }}</h3>
                    </div>
                    <div id="chart1"></div>
                </div>
            </div>
            <div class="px-2 pb-2 w-full sm:w-full">
                <div class="card bg-white">
                    <div class="flex w-full px-4 justify-between items-center">
                        <h3>{{ __('Sales/Purchases') }}</h3>
                    </div>
                    <div id="chart"></div>
                </div>
            </div>

            <div class="px-2 pb-2 w-full">
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
                                        <p class="font-bold tracking-wide text-gray-800">{{ $sale->customer->name }}
                                        </p>
                                        <span
                                            class="text-indigo-600 text-xs font-semibold">{{ $sale->reference }}</span>
                                    </td>
                                    <td class="px-4 py-2">{{ format_currency($sale->total_amount) }}</td>
                                    <td class="px-4 py-2">{{ $sale->date }}</td>
                                    <td class="px-4 py-2">
                                        @if ($sale->status == \App\Enums\SaleStatus::PENDING)
                                            <x-badge warning>{{ __('Pending') }}</x-badge>
                                        @elseif ($sale->status == \App\Enums\SaleStatus::ORDERED)
                                            <x-badge info>{{ __('Ordered') }}</x-badge>
                                        @elseif($sale->status == \App\Enums\SaleStatus::COMPLETED)
                                            <x-badge success>{{ __('Completed') }}</x-badge>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="px-2 pb-2 w-full">
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
                                        <p class="font-bold tracking-wide text-gray-800">
                                            {{ $purchase->supplier->name }}</p>
                                        <span
                                            class="text-indigo-600 text-xs font-semibold">{{ $purchase->reference }}</span>
                                    </td>
                                    <td class="px-4 py-2">{{ format_currency($purchase->total_amount) }}</td>
                                    <td class="px-4 py-2">{{ $purchase->date }}</td>
                                    <td class="px-4 py-2">
                                        @if ($purchase?->status == \App\Enums\PurchaseStatus::PENDING)
                                            <x-badge warning class="text-xs">
                                                {{ __('Pending') }}
                                            </x-badge>
                                        @elseif ($purchase?->status == \App\Enums\PurchaseStatus::ORDERED)
                                            <x-badge success class="text-xs">
                                                {{ __('Ordered') }}
                                            </x-badge>
                                        @elseif ($purchase?->status == \App\Enums\PurchaseStatus::COMPLETED)
                                            <x-badge success class="text-xs">
                                                {{ __('Completed') }}
                                            </x-badge>
                                        @elseif ($purchase?->status == \App\Enums\PurchaseStatus::Returned)
                                            <x-badge success class="text-xs">
                                                {{ __('Returned') }}
                                            </x-badge>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="px-2 pb-2 sm:w-1/2 w-full">
                <div class="bg-white rounded-lg border border-gray-200 pb-2">
                    <div class="py-3 px-5 w-full inline-flex items-center justify-between text-gray-700">
                        <span class="text-md font-semibold">{{ __('Top 5 Seller on ') }}{{ DATE('F') }}</span>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-left">{{ 'Name' }}</th>
                                <th>{{ 'Profit' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bestSales as $sales)
                                <tr class="antialiased">
                                    <td class="py-1 px-2 font-bold tracking-wide text-gray-800">{{ $sales->name }}
                                        <br><span class="text-indigo-600 text-xs font-semibold">{{ 'Total Sales' }}
                                            :
                                            {{ $sales->totalSales }}</span>
                                    </td>
                                    <td class="py-1 px-2 text-center text-xs tracking-wide">
                                        {{ format_currency($sales->TotalAmount) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="px-2 pb-2 sm:w-1/2 w-full">
                <div class="bg-white rounded-lg border border-gray-200 pb-2">
                    <div class="py-3 px-5 w-full inline-flex items-center justify-between text-gray-700">
                        <span class="text-md font-semibold">{{ __('Top Product on ') }}{{ DATE('F') }}</span>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ 'Name' }}</th>
                                <th>{{ 'Total' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topProduct as $product)
                                <tr class="antialiased">
                                    <td class="py-1 px-2 font-bold tracking-wide text-gray-800">{{ $product->name }}
                                        <br><span class="text-indigo-600 text-xs font-semibold">code :
                                            {{ $product->code }}</span>
                                    </td>
                                    <td class="py-1 px-2 text-center text-xs tracking-wide">{{ $product->qtyItem }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@prepend('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endprepend

@push('scripts')
    <script>
        var options = {
            chart: {
                type: 'area',
                height: '400px',
                defaultLocale: 'en',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                },
                locales: [{
                    name: 'en',
                    options: {
                        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                            'September', 'October', 'November', 'December'
                        ],
                        shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                            'Nov', 'Dec'
                        ],
                        days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                        shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        toolbar: {
                            download: 'تحميل',
                            selection: 'تحديد',
                            selectionZoom: 'تحديد التكبير',
                            zoomIn: 'تكبير',
                            zoomOut: 'تصغير',
                            pan: 'Panning',
                            reset: 'إعادة الحجم الطبيعي',
                            menu: 'اللائحة',
                            DownloadSVG: 'asdfasdf'
                        }
                    }
                }]
            },
            series: [{
                name: 'المشتريات',
                data: @json($purchases_count)
            }],
            xaxis: {
                categories: @json($purchases)
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val;
                    }
                }
            }
        }
        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
    </script>

    <script>
        var options = {
            chart: {
                type: 'area',
                height: '400px',
                defaultLocale: 'en',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                },
                locales: [{
                    name: 'en',
                    options: {
                        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                            'September', 'October', 'November', 'December'
                        ],
                        shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                            'Nov', 'Dec'
                        ],
                        days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                        shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                        toolbar: {
                            download: 'تحميل',
                            selection: 'تحديد',
                            selectionZoom: 'تحديد التكبير',
                            zoomIn: 'تكبير',
                            zoomOut: 'تصغير',
                            pan: 'Panning',
                            reset: 'إعادة الحجم الطبيعي',
                            menu: 'اللائحة',
                            DownloadSVG: 'asdfasdf'
                        }
                    }
                }]
            },
            series: [{
                name: 'المبيعات',
                data: @json($sales_count)
            }],
            xaxis: {
                categories: @json($sales)
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val;
                    }
                }
            }
        }
        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    </script>
@endpush


@push('scripts')
    <script>
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
                    type: "bar",
                    zoom: {
                        enabled: false
                    }
                },
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
            var chart = new ApexCharts(document.querySelector(selector), options);
            chart.render();
        }
        chart({!! $charts !!}, '#chart');
    </script>
@endpush
