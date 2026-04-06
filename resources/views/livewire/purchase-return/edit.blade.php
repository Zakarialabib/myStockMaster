<div>
    <div class="px-4 mx-auto mb-4">
        <div class="flex flex-col">
            <div class="w-full">
                <livewire:products.search-product />
            </div>

            <div class="w-full mt-4">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="p-4">
                        <x-validation-errors class="mb-4" :errors="$errors" />

                        <form wire:submit.prevent="proceed">
                            <div class="flex flex-wrap -mx-2 mb-3">
                                <div class="w-full md:w-1/3 px-2 mb-2">
                                    <div class="mb-4">
                                        <label for="reference" class="block text-sm font-medium text-gray-700">{{ __('Reference') }} <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.blur="reference" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" readonly />
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-2 mb-2">
                                    <div class="mb-4">
                                        <label for="supplier_id" class="block text-sm font-medium text-gray-700">{{ __('Supplier') }} <span class="text-red-500">*</span></label>
                                        <x-select wire:model.blur="supplier_id" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" required>
                                            <option value="">{{ __('Select Supplier') }}</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-2 mb-2">
                                    <div class="mb-4">
                                        <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }} <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model.blur="date" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" required />
                                    </div>
                                </div>
                            </div>

                            <livewire:utils.product-cart :cartInstance="'purchase_return'" :data="$purchasereturn" />

                            <div class="flex flex-wrap -mx-2 mb-3 mt-4">
                                <div class="w-full md:w-1/3 px-2 mb-2">
                                    <div class="mb-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }} <span class="text-red-500">*</span></label>
                                        <x-select wire:model.blur="status" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" required>
                                            @foreach (\App\Enums\PurchaseReturnStatus::cases() as $statusEnum)
                                                <option value="{{ $statusEnum->value }}">{{ __($statusEnum->name) }}</option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-2 mb-2">
                                    <div class="mb-4">
                                        <label for="payment_method" class="block text-sm font-medium text-gray-700">{{ __('Payment Method') }} <span class="text-red-500">*</span></label>
                                        <x-select wire:model.blur="payment_method" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" required>
                                            <option value="Cash">{{ __('Cash') }}</option>
                                            <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                                            <option value="Cheque">{{ __('Cheque') }}</option>
                                            <option value="Other">{{ __('Other') }}</option>
                                        </x-select>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-2 mb-2">
                                    <div class="mb-4">
                                        <label for="paid_amount" class="block text-sm font-medium text-gray-700">{{ __('Amount Received') }} <span class="text-red-500">*</span></label>
                                        <div class="flex mt-1">
                                            <input type="number" step="0.01" wire:model.blur="paid_amount" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-l-md" required />
                                            <button wire:click="$set('paid_amount', {{ $total_amount }})" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="note" class="block text-sm font-medium text-gray-700">{{ __('Note (If Needed)') }}</label>
                                <textarea wire:model.blur="note" rows="5" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:loading.attr="disabled">
                                    <span wire:loading.remove>{{ __('Update Purchase Return') }}</span>
                                    <span wire:loading>{{ __('Updating...') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
