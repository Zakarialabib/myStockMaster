<div x-data="{ isCartOpen: false }">

    <x-theme.breadcrumb :title="__('Edit Purchase')" :parent="route('purchases.index')" :parentName="__('Purchases List')" :childrenName="__('Edit Purchase')">
        <div class="flex items-center gap-2">
            <x-button primary type="button" @click="isCartOpen = true">
                {{ __('View Cart & Checkout') }}
            </x-button>
        </div>
    </x-theme.breadcrumb>

    <!-- Top Selection Header -->
    <div class="bg-white rounded-lg shadow-sm p-4 mt-4 border border-gray-200">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1">
                <x-label for="reference" :value="__('Reference')" required />
                <x-input type="text" wire:model="reference" name="reference" required readonly class="w-full mt-1 bg-gray-100" />
            </div>
            <div class="flex-1">
                <x-label for="warehouse_id" :value="__('Warehouse')" required />
                <x-select-list disabled
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1 bg-gray-100"
                    required id="warehouse_id" name="warehouse_id" wire:model="form.warehouse_id"
                    :options="$this->warehouses" />
                <x-input-error :messages="$errors->get('form.warehouse_id')" class="mt-2" />
            </div>
            <div class="flex-1">
                <x-label for="supplier_id" :value="__('Supplier')" required />
                <x-searchable-select
                    required id="supplier_id" name="supplier_id" wire:model.live="form.supplier_id"
                    :options="$this->suppliers" />
                <x-input-error :messages="$errors->get('form.supplier_id')" class="mt-2" />
            </div>
            <div class="flex-1">
                <x-label for="date" :value="__('Date')" required />
                <x-input type="date" name="date" required wire:model="form.date" class="w-full mt-1" />
                <x-input-error :messages="$errors->get('form.date')" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="mt-4 w-full h-full">
        <livewire:products.search-product :$warehouse_id="$this->form->warehouse_id" />
    </div>

    <!-- Slide-over -->
    <div x-show="isCartOpen" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <div x-show="isCartOpen" x-transition.opacity class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isCartOpen = false" aria-hidden="true"></div>

            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="isCartOpen" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-2xl">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <div class="bg-white border-b border-gray-200 px-4 py-6 sm:px-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">{{ __('Cart & Checkout') }}</h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-hidden focus:ring-2 focus:ring-indigo-500" @click="isCartOpen = false">
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
                            <form wire:submit="update">

                <livewire:utils.product-cart :cartInstance="'purchase'" :data="$purchase" />

                <div class="flex flex-wrap mb-3">
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="status" :value="__('Status')" reauired />
                        <x-select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="status" id="status" required wire:model.live="form.status">
                            @foreach (\App\Enums\PurchaseStatus::cases() as $status)
                                <option {{ $status == $status ? 'selected' : '' }} value="{{ $status->value }}">
                                    {{ __($status->name) }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="payment_method" :value="__('Payment Method')" reauired />
                        <input type="text" wire:model.live="form.payment_method"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="payment_method" required readonly>
                    </div>
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="paid_amount" :value="__('Amount Received')" reauired />
                        <input id="paid_amount" type="text" wire:model.live="form.paid_amount"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="paid_amount" required readonly>
                    </div>
                </div>

                <div class="w-full px-3 mb-4">
                    <label for="note">{{ __('Note') }}</label>
                    <textarea name="note" id="note" rows="5" wire:model.live="form.note"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">{{ $purchase->note }}</textarea>
                </div>

                <div class="w-full px-3">
                    <x-button type="submit" primary class="w-full text-center">
                        {{ __('Update Purchase') }}
                    </x-button>
                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
