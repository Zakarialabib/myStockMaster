<div>
    @section('title', __('Edit Quotation'))

    <x-theme.breadcrumb :title="__('Edit Quotation')" :parent="route('quotations.index')" :parentName="__('Quotations List')" :childrenName="__('Edit Quotation')" />
    <div class="flex flex-wrap">

        <div class="lg:w-1/2 sm:w-full h-full">
            <livewire:utils.search-product :warehouse_id="$this->quotation->warehouse_id" />
        </div>

        <div class="lg:w-1/2 sm:w-full h-full">
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit="update">
                <div class="flex flex-wrap mb-3">
                    <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                        <label for="reference">{{ __('Reference') }} <span class="text-red-500">*</span></label>
                        <x-input type="text" wire:model.live="reference" name="reference" required readonly />
                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                        <label for="customer_id">{{ __('Customer') }} <span class="text-red-500">*</span></label>
                        <x-select-list :options="$this->customers" name="customer_id" id="customer_id" wire:model="customer_id" />
                        <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />

                    </div>
                    <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                        <x-label for="warehouse" :value="__('Warehouse')" required />
                        <select disabled
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id">
                            @foreach ($this->warehouses as $index => $warehouse)
                                <option value="{{ $index }}" @if ($warehouse_id == $index) selected @endif>
                                    {{ $warehouse }}</option>
                                {{ $warehouse }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                    </div>

                    <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                        <x-label for="date" :value="__('Date')" required />
                        <input type="date" name="date" required wire:model="date"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                </div>

                <livewire:utils.product-cart :cartInstance="'quotation'" :data="$quotation" lazy />

                <div class="flex flex-wrap mb-3">
                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="status" :value="__('Status')" required />
                        <select wire:model="status"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="status" id="status" required>
                            <option value="">{{ __('Select Status') }}</option>
                            @foreach (\App\Enums\QuotationStatus::cases() as $status)
                                <option {{ $quotation->status == $status->value ? 'selected' : '' }}
                                    value="{{ $status->value }}">
                                    {{ __($status->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:w-1/3 px-2 mb-2">
                        <x-label for="total_amount" :value="__('Total amount')" />
                        <x-input type="number" wire:model="total_amount" name="total_amount" />
                    </div>

                    <div class="w-full px-3 mb-4">
                        <label for="note">{{ __('Note (If Needed)') }}</label>
                        <textarea name="note" id="note" rows="5" wire:model="note"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">{{ $quotation->note }}</textarea>
                    </div>

                    <div class="w-full mt-3 text-center">
                        <x-button type="submit" primary>
                            {{ __('Update Quotation') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
