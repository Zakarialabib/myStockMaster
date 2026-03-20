<div class="w-full flex flex-wrap -mx-3 mb-6">
    @foreach (settings()->analytics_control as $control)
        @if ($control['status'])
            @switch($control['name'])
                    @case('total_categories')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="{{ $control['color'] }}" counter="{{ $this->categoriesCount }}"
                                title="{{ __('Total Categories') }}" href="{{ route('product-categories.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('total_products')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="{{ $control['color'] }}" counter="{{ $this->productCount }}"
                                title="{{ __('Total Products') }}" href="{{ route('products.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                    </path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('total_supplier')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="{{ $control['color'] }}" counter="{{ $this->supplierCount }}"
                                title="{{ __('Total Supplier') }}" href="{{ route('suppliers.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                    </path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('total_customer')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="{{ $control['color'] }}" counter="{{ $this->customerCount }}"
                                title="{{ __('Total Customer') }}" href="{{ route('customers.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('sales')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="{{ $control['color'] }}" counter="{{ $this->salesCount }}" :title="__('Sales')"
                                href="{{ route('sales.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('purchases')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="{{ $control['color'] }}" counter="{{ $this->purchasesCount }}" :title="__('Purchases')"
                                href="{{ route('purchases.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('best_selling_product')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="yellow" counter="{{ $this->bestSellingProduct }}"
                                title="{{ __('Best Selling Product') }}" href="{{ route('best-selling-product.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('number_of_products_sold')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="purple" counter="{{ $this->numberOfProductsSold }}"
                                title="{{ __('Number of Products Sold') }}" href="{{ route('products-sold.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('average_purchase_return_amount')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="red" counter="{{ format_currency($this->averagePurchaseReturnAmount) }}"
                                title="{{ __('Average Purchase Return Amount') }}" href="{{ route('purchase-return.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('common_return_reason')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="orange" counter="{{ $this->commonReturnReason }}"
                                title="{{ __('Common Return Reason') }}" href="{{ route('return-reason.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('average_payment_received_per_sale')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="green" counter="{{ format_currency($this->averagePaymentReceivedPerSale) }}"
                                title="{{ __('Average Payment Received per Sale') }}"
                                href="{{ route('payment-received.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break

                    @case('significant_payment_changes')
                        <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                            <x-counter-card color="blue" counter="{{ $this->significantPaymentChanges }}"
                                title="{{ __('Significant Payment Changes') }}" href="{{ route('payment-changes.index') }}">
                                <svg class="w-6 h-6 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </x-counter-card>
                        </div>
                    @break
            @endswitch
        @endif
    @endforeach
</div>
