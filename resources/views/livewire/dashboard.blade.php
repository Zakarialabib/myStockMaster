@section('title', __('Dashboard'))

@section('breadcrumb')
    <div class="relative p-4 sm:p-6 ">
        <h1 class="text-2xl md:text-3xl text-gray-800 font-bold mb-1">{{ __('Hello') }}, {{ Auth::user()->name }} 👋
        </h1>
        <p>{{ __('What are you look for today ?') }}</p>
        <div class="py-5">
            <livewire:utils.livesearch />
        </div>
    </div>
@endsection

<div class="px-2 mx-auto">
    {{-- <livewire:calculator /> --}}
    <div class="block mb-4 px-4">

        <div class="w-full relative mb-6 px-4 flex justify-center gap-4 items-center">
            <label class="font-semibold">{{ __('from') }}:</label>
            <div class="flex-1">
                <input type="date" wire:model.live="startDate" class="w-full border rounded px-2 py-1">
            </div>
            <span class="mx-2 font-semibold">{{ __('to') }}</span>
            <div class="flex-1">
                <input type="date" wire:model.live="endDate" class="w-full border rounded px-2 py-1">
            </div>
        </div>
        @can('show total stats')
            <div class="w-full flex flex-wrap my-4 px-4 py-2 border-dashed border-gray-400">

                @foreach (settings()->analytics_control as $control)
                    @if ($control['status'])
                        @switch($control['name'])
                            @case('total_categories')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="{{ $control['color'] }}" counter="{{ $categoriesCount }}"
                                        title="{{ __('Total Categories') }}" href="{{ route('product-categories.index') }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('total_products')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="{{ $control['color'] }}" counter="{{ $productCount }}"
                                        title="{{ __('Total Products') }}" href="{{ route('products.index') }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                        </path>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('total_supplier')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="{{ $control['color'] }}" counter="{{ $supplierCount }}"
                                        title="{{ __('Total Supplier') }}" href="{{ route('suppliers.index') }}">
                                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                        </path>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('total_customer')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="{{ $control['color'] }}" counter="{{ $customerCount }}"
                                        title="{{ __('Total Customer') }}" href="{{ route('customers.index') }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('sales')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="{{ $control['color'] }}" counter="{{ $salesCount }}"
                                        :title="__('Sales')" href="{{ route('sales.index') }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('purchases')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="{{ $control['color'] }}" counter="{{ $purchasesCount }}"
                                        :title="__('Purchases')" href="{{ route('purchases.index') }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('best_selling_product')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="yellow" counter="{{ $best_selling_product }}"
                                        title="{{ __('Best Selling Product') }}" href="{{ route('best-selling-product.index') }}">
                                        <i class="bi bi-star"></i>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('number_of_products_sold')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="purple" counter="{{ $number_of_products_sold }}"
                                        title="{{ __('Number of Products Sold') }}" href="{{ route('products-sold.index') }}">
                                        <i class="bi bi-box"></i>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('average_purchase_return_amount')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="red" counter="{{ format_currency($average_purchase_return_amount) }}"
                                        title="{{ __('Average Purchase Return Amount') }}"
                                        href="{{ route('purchase-return.index') }}">
                                        <i class="fa fa-arrow-left"></i>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('common_return_reason')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="orange" counter="{{ $common_return_reason }}"
                                        title="{{ __('Common Return Reason') }}" href="{{ route('return-reason.index') }}">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('average_payment_received_per_sale')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2"> <x-counter-card color="green"
                                        counter="{{ format_currency($average_payment_received_per_sale) }}"
                                        title="{{ __('Average Payment Received per Sale') }}"
                                        href="{{ route('payment-received.index') }}">
                                        <i class="fa fa-dollar-sign"></i>
                                    </x-counter-card>
                                </div>
                            @break

                            @case('significant_payment_changes')
                                <div class="sm:w-1/4 w-1/2 px-2 pb-2">
                                    <x-counter-card color="blue" counter="{{ $significant_payment_changes }}"
                                        title="{{ __('Significant Payment Changes') }}"
                                        href="{{ route('payment-changes.index') }}">
                                        <i class="fa fa-chart-line"></i>
                                    </x-counter-card>
                                </div>
                            @break
                        @endswitch
                    @endif
                @endforeach

            </div>
        @endcan

    </div>
    
    @can('dashboard_access')
        <livewire:stats.transactions />
    @endcan

</div>
