<div
    x-data="{
        showCheckout: @entangle('checkoutModal'),
        totalAmount: @entangle('total_amount'),
        paidAmount: @entangle('paid_amount'),
        paymentMethod: @entangle('payment_method'),
        scanBeep: null,
        scanError: null,
        init() {
            this.scanBeep = new Audio('{{ asset('sounds/beep.mp3') }}');
            this.scanBeep.volume = 0.3;
            this.scanError = new Audio('{{ asset('sounds/error.mp3') }}');
            this.scanError.volume = 0.3;

            this.scanBeep.onerror = () => console.warn('Audio file not found: sounds/beep.mp3');
            this.scanError.onerror = () => console.warn('Audio file not found: sounds/error.mp3');

            this.$watch('showCheckout', value => {
                if (value) {
                    this.$nextTick(() => {
                        document.getElementById('paid_amount')?.focus();
                    });
                }
            });

            Livewire.on('barcode-scanned-success', () => {
                this.scanBeep.currentTime = 0;
                this.scanBeep.play().catch(() => {});
            });

            Livewire.on('barcode-scanned-error', () => {
                this.scanError.currentTime = 0;
                this.scanError.play().catch(() => {});
            });
        }
    }"
    x-on:keydown.window.prevent.ctrl.f="$refs.productSearchInput?.focus()"
    x-on:keydown.window.prevent.ctrl.enter="$wire.proceed()"
    x-on:keydown.window.prevent.escape="showCheckout = false"
    class="h-full"
>
    <div class="flex flex-col lg:flex-row gap-4 h-full">
        <!-- Left Column: Product Selection (40%) -->
        <div class="w-full lg:w-5/12 flex flex-col gap-4">
            <!-- Product Search -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex-1 relative {{ !$warehouse_id ? 'opacity-75' : '' }}"
                role="region"
                aria-label="{{ __('Product selection area') }}"
            >
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-search mr-2 text-blue-500" aria-hidden="true"></i>
                        {{ __('Select Products') }}
                        <kbd class="ml-2 px-2 py-0.5 text-xs bg-gray-200 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600">Ctrl+F</kbd>
                    </h3>
                </div>
                <div class="p-3">
                    @if (!$warehouse_id)
                        <div
                            class="absolute inset-0 z-50 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm flex items-center justify-center">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center border border-gray-200 dark:border-gray-700">
                                <i class="fas fa-warehouse text-5xl text-blue-400 mb-3" aria-hidden="true"></i>
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ __('Warehouse Required') }}</h4>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">
                                    {{ __('Please select a warehouse first to load available products.') }}</p>
                            </div>
                        </div>
                    @endif
                    <livewire:products.search-product :warehouseId="$warehouse_id" />
                </div>
            </div>
        </div>

        <!-- Center Column: Shopping Cart (35%) -->
        <div class="w-full lg:w-4/12 flex flex-col gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex-1 flex flex-col">
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-shopping-basket mr-2 text-green-500" aria-hidden="true"></i>
                        {{ __('Shopping Cart') }}
                    </h3>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400"
                        role="status"
                        aria-live="polite"
                    >
                        {{ $this->cartCount }} {{ __('Items') }}
                    </span>
                </div>
                <div class="p-3 flex-1 overflow-y-auto" role="region" aria-label="{{ __('Cart items') }}">
                    <livewire:utils.product-cart :cartInstance="'pos'" :warehouseId="$warehouse_id" />
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Customer (25%) -->
        <div class="w-full lg:w-3/12 flex flex-col gap-4">
            <!-- Warehouse Selection -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-warehouse mr-2 text-blue-500" aria-hidden="true"></i>
                        {{ __('Warehouse') }}
                    </h3>
                    @if (!$warehouse_id)
                        <span
                            class="px-2 py-1 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 rounded-full animate-pulse"
                            role="alert"
                        >
                            {{ __('Required') }}
                        </span>
                    @endif
                </div>
                <div class="p-4">
                    <div class="relative">
                        <select
                            required
                            id="warehouse_id"
                            name="warehouse_id"
                            wire:model.live="warehouse_id"
                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 {{ !$warehouse_id ? 'ring-2 ring-red-300 dark:ring-red-800' : '' }}"
                            aria-label="{{ __('Select warehouse') }}"
                            aria-required="true"
                        >
                            <option value="">{{ __('Select warehouse to start') }}</option>
                            @foreach ($this->warehouses as $index => $warehouse)
                                <option value="{{ $index }}">{{ $warehouse }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Selection (Combobox) -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-user mr-2 text-blue-500" aria-hidden="true"></i>
                        {{ __('Customer') }}
                    </h3>
                </div>
                <div class="p-4">
                    <livewire:pos.customer-combobox :customer-id="$customer_id" />
                </div>
            </div>

            <!-- Action Panel -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-bolt mr-2 text-yellow-500" aria-hidden="true"></i>
                        {{ __('Actions') }}
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <button
                        type="button"
                        wire:click="proceed"
                        wire:loading.attr="disabled"
                        class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors {{ $total_amount == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        aria-label="{{ __('Proceed to checkout') }}"
                    >
                        <i class="fas fa-cash-register mr-2" aria-hidden="true"></i>
                        {{ __('Checkout') }}
                        <kbd class="ml-2 px-2 py-0.5 text-xs bg-green-700 rounded border border-green-800">Ctrl+Enter</kbd>
                    </button>

                    <button
                        type="button"
                        wire:click="clearCart"
                        wire:loading.attr="disabled"
                        class="w-full flex items-center justify-center px-4 py-2.5 border border-red-300 dark:border-red-700 text-sm font-medium rounded-lg text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 transition-colors"
                        aria-label="{{ __('Clear all items from cart') }}"
                    >
                        <i class="fas fa-trash-alt mr-2" aria-hidden="true"></i>
                        {{ __('Reset Cart') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <x-modal wire:model="checkoutModal" max-width="4xl">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center text-lg font-bold text-gray-900 dark:text-gray-100">
                <i class="fas fa-cash-register mr-3 text-green-500" aria-hidden="true"></i>
                {{ __('Checkout') }}
            </div>
        </div>

        <div class="p-6">
            <form id="checkout-form" wire:submit="store" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Payment Information -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-credit-card mr-2 text-blue-500" aria-hidden="true"></i>
                            {{ __('Payment Information') }}
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <x-label for="total_amount" :value="__('Total Amount')" required class="mb-2" />
                                <div class="relative">
                                    <input
                                        id="total_amount"
                                        type="text"
                                        wire:model.live="total_amount"
                                        class="block w-full pl-10 pr-4 py-3 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100"
                                        name="total_amount"
                                        readonly
                                        required
                                        aria-readonly="true"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-dollar-sign text-gray-400" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <x-label for="paid_amount" :value="__('Paid Amount')" required />
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Change') }}:
                                        <span
                                            x-text="formatCurrency(Math.max(0, paidAmount - totalAmount))"
                                            class="text-xl font-bold"
                                            :class="(paidAmount - totalAmount) < 0 ? 'text-red-500' : 'text-green-600'"
                                        >
                                            {{ format_currency((float) $paid_amount - (float) $total_amount) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="relative mb-3">
                                    <input
                                        id="paid_amount"
                                        type="number"
                                        step="0.01"
                                        wire:model.live.debounce.300ms="paid_amount"
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        name="paid_amount"
                                        required
                                        aria-label="{{ __('Amount paid by customer') }}"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-money-bill-wave text-gray-400" aria-hidden="true"></i>
                                    </div>
                                </div>

                                <!-- Smart Cash Buttons -->
                                <x-smart-cash-buttons :total-amount="$total_amount" wire-model="paid_amount" />
                            </div>

                            <div>
                                <x-label for="payment_method" :value="__('Payment Method')" required class="mb-2" />
                                <div class="relative">
                                    <select
                                        wire:model.live="payment_method"
                                        id="payment_method"
                                        required
                                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        aria-label="{{ __('Select payment method') }}"
                                    >
                                        <option value="Cash">{{ __('Cash') }}</option>
                                        <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                                        <option value="Cheque">{{ __('Cheque') }}</option>
                                        <option value="Other">{{ __('Other') }}</option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-credit-card text-gray-400" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-label for="note" :value="__('Note')" class="mb-2" />
                                <textarea
                                    name="note"
                                    id="note"
                                    rows="4"
                                    wire:model.live.debounce.300ms="note"
                                    class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="{{ __('Add any notes...') }}"
                                    maxlength="1000"
                                    aria-label="{{ __('Order notes') }}"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fas fa-receipt mr-2 text-purple-500" aria-hidden="true"></i>
                                {{ __('Order Summary') }}
                            </h4>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Total Products') }}</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400"
                                        role="status"
                                        aria-live="polite"
                                    >
                                        {{ $this->cartCount }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Order Tax') }}
                                        ({{ $global_tax }}%)</span>
                                    <span
                                        class="font-medium text-blue-600 dark:text-blue-400">+{{ format_currency($this->cartTax) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Discount') }}
                                        ({{ $global_discount }}%)</span>
                                    <span
                                        class="font-medium text-red-600 dark:text-red-400">-{{ format_currency($this->cartDiscount) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Shipping') }}</span>
                                    <span
                                        class="font-medium text-blue-600 dark:text-blue-400">+{{ format_currency($shipping_amount) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-4 bg-gray-50 dark:bg-gray-900/50 px-4 rounded-lg">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Grand Total') }}</span>
                                    <span
                                        class="text-lg font-bold text-green-600 dark:text-green-400">{{ format_currency($total_with_shipping) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-button secondary type="button" x-on:click="show = false">
                        <i class="fas fa-times mr-2" aria-hidden="true"></i>
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button success type="submit" wire:loading.attr="disabled" class="min-w-32">
                        <i class="fas fa-check mr-2" aria-hidden="true"></i>
                        {{ __('Complete Sale') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>

    @push('scripts')
        <script>
            document.addEventListener('open-print-window', (event) => {
                window.open(event.detail.url, '_blank');
            });

            function formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(amount);
            }
        </script>
    @endpush
</div>
