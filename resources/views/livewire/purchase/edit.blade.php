<div>
    @section('title', __('Edit Purchase'))

    <x-theme.breadcrumb :title="__('Edit Purchase')" :parent="route('purchases.index')" :parentName="__('Purchases List')" :childrenName="__('Edit Purchase')">

    </x-theme.breadcrumb>

    <div class="flex flex-wrap">

        <div class="lg:w-1/2 sm:w-full h-full">
            <livewire:utils.search-product :$warehouse_id="$this->warehouse_id" lazy />
        </div>

        <div class="lg:w-1/2 sm:w-full h-full">
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit="update">
                <div class="mb-4 flex flex-wrap gap-4">
                    <div class="flex-1">
                        <label for="reference">{{ __('Reference') }} <span class="text-red-500">*</span></label>
                        <x-input type="text" wire:model.live="reference" name="reference" required readonly />
                    </div>
                    <div class="flex-1">
                        <label for="supplier_id">{{ __('Supplier') }} <span class="text-red-500">*</span></label>
                        <select name="supplier_id" id="supplier_id" wire:model.live="supplier_id">
                            <option value="">{{ __('Select a supplier') }}</option>
                            @foreach ($this->suppliers as $index => $supplier)
                                <option value="{{ $index }}">
                                    {{ $supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <x-label for="warehouse" :value="__('Warehouse')" />
                        <x-select-list disabled
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id"
                            :options="$this->warehouses" />
                        <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                    </div>
                    <div class="flex-1">
                        <x-label for="date" :value="__('Date')" required />
                        <input type="date" name="date" required wire:model.live="date"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                </div>

                <livewire:utils.product-cart :cartInstance="'purchase'" :data="$purchase" lazy />

                <div class="flex flex-wrap mb-3">
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="status" :value="__('Status')" reauired />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="status" id="status" required wire:model.live="status">
                            @foreach (\App\Enums\PurchaseStatus::cases() as $status)
                                <option {{ $status == $status ? 'selected' : '' }} value="{{ $status->value }}">
                                    {{ __($status->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="payment_method" :value="__('Payment Method')" reauired />
                        <input type="text" wire:model.live="payment_method"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="payment_method" required readonly>
                    </div>
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="paid_amount" :value="__('Amount Received')" reauired />
                        <input id="paid_amount" type="text" wire:model.live="paid_amount"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="paid_amount" required readonly>
                    </div>
                </div>

                <div class="w-full px-3 mb-4">
                    <label for="note">{{ __('Note') }}</label>
                    <textarea name="note" id="note" rows="5" wire:model.live="note"
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
