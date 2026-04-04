<div x-data="{ isCartOpen: false }">
    @section('title', __('Create Sale'))

    <x-theme.breadcrumb :title="__('Create Sale')" :parent="route('sales.index')" :parentName="__('Sales List')" :childrenName="__('Create Sale')">
        <div class="flex items-center gap-2">
            <x-button primary type="button" @click="isCartOpen = true">
                {{ __('View Cart & Checkout') }}
            </x-button>
            @can('customer_create')
                <x-button primary type="button" wire:click="dispatchTo('customer.create', 'createModal')">
                    {{ __('Create Customer') }}
                </x-button>
            @endcan
        </div>
    </x-theme.breadcrumb>

    <div class="mt-2 w-full h-full">
        <livewire:products.search-product />
    </div>

    <!-- Slide-over -->
    <div x-show="isCartOpen" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <div x-show="isCartOpen" x-transition.opacity class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isCartOpen = false" aria-hidden="true"></div>

            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="isCartOpen" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-2xl">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <div class="bg-indigo-600 px-4 py-6 sm:px-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-medium text-white" id="slide-over-title">{{ __('Cart & Checkout') }}</h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" class="rounded-md bg-indigo-600 text-indigo-200 hover:text-white focus:outline-hidden focus:ring-2 focus:ring-white" @click="isCartOpen = false">
                                        <span class="sr-only">Close panel</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="relative flex-1 px-4 py-6 sm:px-6">
                            <x-validation-errors class="mb-4" :errors="$errors" />
                            <form wire:submit="store">

                <div class="mb-4 flex flex-wrap gap-4">
                    <div class="flex-1">
                        <x-label for='customer_id' :value="__('Customer')" required />
                        <x-searchable-select
                            required id="customer_id" name="customer_id" wire:model.live="customer_id"
                            :options="$this->customers" />
                        <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                    </div>
                    <div class="flex-1">
                        <x-label for="date" :value="__('Date')" required />
                        <input type="date" name="date" required wire:model="date"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                    <div class="flex-1">
                        <x-label for="warehouse" :value="__('Warehouse')" />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id">
                            <option value=""> {{ __('Select warehouse') }}</option>
                            @foreach ($this->warehouses as $index => $warehouse)
                                <option value="{{ $index }}">{{ $warehouse }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                    </div>
                </div>

                <livewire:utils.product-cart :cartInstance="'sale'" />
                <div class="mb-4 grid md:grid-cols-2 sm:grid-cols-1 gap-4">

                    <div class="">
                        <label for="payment_method">{{ __('Payment Method') }} <span
                                class="text-red-500">*</span></label>
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="payment_method" id="payment_method" wire:model.live="payment_method" required>
                            <option value=""> {{ __('Select option') }}</option>
                            <option value="Cash">{{ __('Cash') }}</option>
                            <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                            <option value="Cheque">{{ __('Cheque') }}</option>
                            <option value="Other">{{ __('Other') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />

                    </div>
                    <div class="">
                        <label for="status">{{ __('Status') }} <span class="text-red-500">*</span></label>
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="status" id="status" required wire:model.live="status">
                            <option value="" selected> {{ __('Select option') }}</option>
                            @foreach (\App\Enums\SaleStatus::cases() as $status)
                                <option value="{{ $status->value }}">
                                    {{ __($status->name) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />

                    </div>

                    <div class="">
                        <label for="total_amount">{{ __('Total Amount') }} <span class="text-red-500">*</span></label>
                        <x-input id="total_amount" type="text" wire:model.live="total_amount" name="total_amount"
                            value="{{ $total_amount }}" readonly required />
                    </div>

                    <div class="">
                        <label for="paid_amount">{{ __('Received Amount') }} <span
                                class="text-red-500">*</span></label>
                        <x-input id="paid_amount" type="text" wire:model.live="paid_amount"
                            value="{{ $total_amount }}" name="paid_amount" required />

                    </div>
                </div>

                <div class="my-4">
                    <label for="note">{{ __('Note (If Needed)') }}</label>
                    <textarea name="note" id="note" rows="5" wire:model.live="note"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                </div>

                <div class="flex flex-wrap gap-4 justify-between">
                    <x-button danger type="button" wire:click="resetCart" wire:loading.attr="disabled"
                        class="ml-2 font-bold flex-1">
                        {{ __('Reset') }}
                    </x-button>
                    <button
                        class="flex-1 items-center px-4 py-2 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest active:bg-green-900 focus:outline-hidden focus:border-green-900 focus:ring-3 ring-green-500 disabled:opacity-25 transition ease-in-out duration-150 bg-green-600 hover:bg-green-700"
                        type="button" wire:click.throttle="proceed" wire:loading.attr="disabled"
                        {{ $total_amount == 0 ? 'disabled' : '' }}>
                        {{ __('Proceed') }}
                    </button>
                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <livewire:customers.create />

    <livewire:cash-register.create />

</div>
