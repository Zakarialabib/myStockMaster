<div x-data="{ isCartOpen: false }">
    @section('title', __('Create Purchase'))

    <x-theme.breadcrumb :title="__('Create Purchase')" :parent="route('purchases.index')" :parentName="__('Purchases List')" :childrenName="__('Create Purchase')">
        <div class="flex items-center gap-2">
            <x-button primary type="button" @click="isCartOpen = true">
                {{ __('View Cart & Checkout') }}
            </x-button>
            <x-button primary
                wire:click="dispatchTo('suppliers.create', 'createModal')">{{ __('Create Supplier') }}</x-button>
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
                <div class="flex flex-wrap mb-3">
                    <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                        <x-label for='supplier_id' :value="__('Supplier')" required />
                        <x-searchable-select
                            required id="supplier_id" name="supplier_id" wire:model.live="supplier_id"
                            :options="$this->suppliers" />
                        <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                        <x-label for="date" :value="__('Date')" required />
                        <input type="date" name="date" required wire:model="date"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                        <x-label for="warehouse" :value="__('Warehouse')" />
                        <select required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                            <option value="">
                                {{ __('Select Warehouse') }}
                            </option>
                            @foreach ($this->warehouses as $index => $warehouse)
                                <option value="{{ $index }}">{{ $warehouse }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                    </div>
                </div>

                <livewire:utils.product-cart :cartInstance="'purchase'" />

                <div class="flex flex-wrap mb-3">
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="status" :value="__('Status')" required />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="status" id="status" wire:model.live="status" required>
                            <option>{{ __('Select Status') }}</option>
                            @foreach (App\Enums\PurchaseStatus::cases() as $status)
                                <option value="{{ $status->value }}">
                                    {{ __($status->name) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="payment_method" :value="__('Payment Method')" required />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            wire:model.live="payment_method" name="payment_method" id="payment_method" required>
                            <option>{{ __('Select Payment Method') }}</option>
                            <option value="Cash">{{ __('Cash') }}</option>
                            <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                            <option value="Cheque">{{ __('Cheque') }}</option>
                            <option value="Other">{{ __('Other') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                    </div>
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="paid_amount" :value="__('Amount Paid')" required />
                        <x-input id="paid_amount" type="text" wire:model="paid_amount" name="paid_amount" required />
                        <x-input-error :messages="$errors->get('paid_amount')" class="mt-2" />
                    </div>
                </div>

                <div class="mb-4">
                    <label for="note">{{ __('Note (If Needed)') }}</label>
                    <textarea name="note" id="note" rows="5" wire:model="note"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                    <x-input-error :messages="$errors->get('note')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-button danger type="button" wire:click="resetCart" wire:loading.attr="disabled"
                        class="ml-2 font-bold">
                        {{ __('Reset') }}
                    </x-button>

                    <button type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest active:bg-indigo-900 focus:outline-hidden focus:border-indigo-900 focus:ring-3 ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 bg-green-500 hover:bg-green-700"
                        wire:click.throttle="proceed" wire:loading.attr="disabled"
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
    <livewire:suppliers.create />
</div>
