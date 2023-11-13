<div>
    <div class="w-full px-4">

        <x-validation-errors class="mb-4" :errors="$errors" />

        <form wire:submit.prevent="store">
            <div class="flex flex-wrap -mx-2 mb-3">
                <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                    <x-label for="date" :value="__('Date')" required />
                    <input type="date" name="date" required wire:model="date"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>
                <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                    <x-label for="warehouse" :value="__('Warehouse')" />
                    <x-select-list
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        required id="warehouse_id" name="warehouse_id" wire:model="warehouse_id" :options="$this->warehouses" />
                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                </div>
                <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                    <x-label for='supplier_id' :value="__('Supplier')" required />
                    <x-select-list
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        required id="supplier_id" name="supplier_id" wire:model="supplier_id" :options="$this->listsForFields['suppliers']" />
                    <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                </div>
            </div>

            <livewire:product-cart :cartInstance="'purchase'" />

            <div class="flex flex-wrap -mx-2 mb-3">
                <div class="w-full md:w-1/3 px-2 mb-2">
                    <x-label for="status" :value="__('Status')" required />
                    <select
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        name="status" id="status" wire:model.lazy="status" required>
                        <option>{{ __('Select Status') }}</option>
                        @foreach (\App\Enums\PurchaseStatus::cases() as $status)
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
                        wire:model.lazy="payment_method" name="payment_method" id="payment_method" required>
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
                    <x-input id="paid_amount" type="text" wire:model.lazy="paid_amount" name="paid_amount"
                        required />
                    <x-input-error :messages="$errors->get('paid_amount')" class="mt-2" />
                </div>
            </div>

            <div class="mb-4">
                <label for="note">{{ __('Note (If Needed)') }}</label>
                <textarea name="note" id="note" rows="5" wire:model.lazy="note"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                <x-input-error :messages="$errors->get('note')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-button danger type="button" wire:click="resetCart" wire:loading.attr="disabled"
                    class="ml-2 font-bold">
                    {{ __('Reset') }}
                </x-button>

                <x-button type="submit" primary wire:loading.attr="disabled">
                    {{ __('Create Purchase') }}
                </x-button>
            </div>
        </form>
    </div>
</div>
