<div>
    <div>
        <x-validation-errors class="mb-4" :errors="$errors" />
        
        <form wire:submit.prevent="save">
            <div class="flex flex-wrap -mx-2 mb-3">
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <x-label for="reference" :value="__('Reference')" required />
                    <input type="text" wire:model="reference"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        name="reference" required readonly value="PR">
                    <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                </div>
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <x-label for='supplier_id' :value="__('Supplier')" required />
                    <x-select-list
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    required id="supplier_id" name="supplier_id" wire:model="supplier_id" :options="$this->listsForFields['suppliers']" />
                    <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                </div>
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <x-label for="date" :value="__('Date')" required />
                    <input type="date" wire:model="date"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        name="date" required value="{{ now()->format('Y-m-d') }}">
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>
            </div>

            <livewire:product-cart :cartInstance="'purchase'" />

            <div class="flex flex-wrap -mx-2 mb-3">
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <x-label for="status" :value="__('Status')" required />
                    <select class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        name="status" id="status" wire:model="status" required>
                        <option value="{{ App\Enums\PurchaseStatus::Pending }}">{{ __('Pending') }}</option>
                        <option value="{{ App\Enums\PurchaseStatus::Ordered }}">{{ __('Ordered') }}</option>
                        <option value="{{ App\Enums\PurchaseStatus::Completed }}">{{ __('Completed') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <x-label for="payment_method" :value="__('Payment Method')" required />
                    <select class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        wire:model="payment_method" name="payment_method" id="payment_method" required>
                        <option value="Cash">{{ __('Cash') }}</option>
                        <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                        <option value="Cheque">{{ __('Cheque') }}</option>
                        <option value="Other">{{ __('Other') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                </div>
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <x-label for="paid_amount" :value="__('Amount Paid')" required />
                        <input id="paid_amount" type="text" wire:model="paid_amount"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="paid_amount" required>
                        <x-input-error :messages="$errors->get('paid_amount')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="note">{{ __('Note (If Needed)') }}</label>
                <textarea name="note" id="note" rows="5" wire:model="note"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
            </div>

            <div class="w-full mt-3">
                <x-button type="submit" primary class="w-full">
                    {{ __('Create Purchase') }}
                </x-button>
            </div>
        </form>
    </div>
</div>
