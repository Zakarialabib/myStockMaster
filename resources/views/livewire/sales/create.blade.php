<div x-data="{ isCartOpen: false }" @keydown.window.ctrl.s.prevent="$wire.store()">

    <x-theme.breadcrumb :title="__('Create Sale')" :parent="route('sales.index')" :parentName="__('Sales List')" :childrenName="__('Create Sale')">
        <div class="flex items-center gap-2">
            @can('customer_create')
                <x-button primary type="button" wire:click="dispatchTo('customer.create', 'createModal')">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ __('Create Customer') }}
                </x-button>
            @endcan
            <x-button secondary type="button" wire:click="saveDraft">
                <i class="fas fa-save mr-2"></i>
                {{ __('Save Draft') }}
            </x-button>
            <x-button success type="button" wire:click.throttle="proceed" wire:loading.attr="disabled" :disabled="$form->total_amount == 0">
                <i class="fas fa-check mr-2"></i>
                {{ __('Complete Sale') }} (Ctrl+S)
            </x-button>
        </div>
    </x-theme.breadcrumb>

    <!-- Split-Pane Layout -->
    <div class="mt-4 grid grid-cols-1 lg:grid-cols-12 gap-6 h-[calc(100vh-12rem)]">
        
        <!-- Left Pane: Order Context & Products (60%) -->
        <div class="lg:col-span-7 flex flex-col gap-4 overflow-y-auto">
            <!-- Order Metadata -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for='warehouse_id' :value="__('Warehouse')" required />
                        <div class="relative mt-1">
                            <x-select
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md"
                                required id="warehouse_id" name="warehouse_id" wire:model.live="form.warehouse_id">
                                <option value=""> {{ __('Select warehouse') }}</option>
                                @foreach ($this->warehouses as $index => $warehouse)
                                    <option value="{{ $index }}">{{ $warehouse }}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <x-input-error :messages="$errors->get('form.warehouse_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-label for='customer_id' :value="__('Customer')" required />
                        <div class="mt-1">
                            <livewire:pos.customer-combobox wire:model="form.customer_id" />
                        </div>
                        <x-input-error :messages="$errors->get('form.customer_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="date" :value="__('Date')" required />
                        <x-input type="date" name="date" required wire:model="form.date" class="w-full mt-1" />
                        <x-input-error :messages="$errors->get('form.date')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-label for="status" :value="__('Status')" required />
                        <x-select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md mt-1"
                            name="status" id="status" required wire:model.live="form.status">
                            <option value=""> {{ __('Select option') }}</option>
                            @foreach (\App\Enums\SaleStatus::cases() as $status)
                                <option value="{{ $status->value }}">
                                    {{ __($status->name) }}
                                </option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('form.status')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Product Search -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex-1">
                @if (!$form->warehouse_id)
                    <div class="h-full flex items-center justify-center text-blue-500 dark:text-blue-400 p-6 text-center">
                        <div>
                            <i class="fas fa-warehouse text-4xl mb-4"></i>
                            <p>{{ __('Please select a warehouse first to load available products.') }}</p>
                        </div>
                    </div>
                @else
                    <livewire:products.search-product :warehouseId="$form->warehouse_id" />
                @endif
            </div>
        </div>

        <!-- Right Pane: Cart & Financial Summary (40%) -->
        <div class="lg:col-span-5 flex flex-col gap-4 overflow-y-auto">
            <!-- Cart Items -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex-1 flex flex-col min-h-[300px]">
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-shopping-cart mr-2 text-indigo-500"></i>
                        {{ __('Order Items') }}
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                        {{ $this->cartCount }} {{ __('Items') }}
                    </span>
                </div>
                <div class="p-0 flex-1 overflow-y-auto">
                    <livewire:utils.product-cart :cartInstance="'sale'" :warehouseId="$form->warehouse_id" />
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-label for="payment_method" :value="__('Payment Method')" required />
                        <x-select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md mt-1"
                            name="payment_method" id="payment_method" wire:model.live="form.payment_method" required>
                            <option value=""> {{ __('Select option') }}</option>
                            <option value="Cash">{{ __('Cash') }}</option>
                            <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                            <option value="Cheque">{{ __('Cheque') }}</option>
                            <option value="Other">{{ __('Other') }}</option>
                        </x-select>
                        <x-input-error :messages="$errors->get('form.payment_method')" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="paid_amount" :value="__('Received Amount')" required />
                        <x-input id="paid_amount" type="number" step="0.01" wire:model.live.debounce.300ms="form.paid_amount" class="w-full mt-1" required />
                        <x-input-error :messages="$errors->get('form.paid_amount')" class="mt-2" />
                    </div>
                </div>

                <div class="space-y-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Order Tax') }}</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">+{{ format_currency($this->cartTax) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Discount') }}</span>
                        <span class="font-medium text-red-600 dark:text-red-400">-{{ format_currency($this->cartDiscount) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ __('Shipping') }}</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">+{{ format_currency($form->shipping_amount) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 bg-gray-50 dark:bg-gray-900 px-3 rounded-lg mt-2">
                        <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ __('Grand Total') }}</span>
                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ format_currency($form->total_amount) }}
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <x-label for="note" :value="__('Order Note')" />
                    <textarea name="note" id="note" rows="2" wire:model.live.debounce.300ms="form.note"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md mt-1"></textarea>
                </div>
            </div>
        </div>
    </div>

    <livewire:customers.create />
    <livewire:cash-register.create />
</div>
