<div>
    <!-- Validation Errors -->
    <x-validation-errors class="mb-4" :errors="$errors" />

    <form wire:submit.prevent="update">
        <div class="flex flex-wrap -mx-2 mb-3">
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="reference">{{ __('Reference') }} <span class="text-red-500">*</span></label>
                <x-input type="text" wire:model="reference" name="reference" required readonly />
            </div>
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="supplier_id">{{ __('Supplier') }} <span class="text-red-500">*</span></label>
                <x-select2 :options="$this->supplier" name="supplier_id" id="supplier_id" wire:model="supplier_id" />
            </div>
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="date">{{ __('Date') }} <span class="text-red-500">*</span></label>
                <x-input type="date" name="date" required wire:model="date" />
            </div>
        </div>

        <livewire:product-cart :cartInstance="'purchase'" :data="$purchase" />

        <div class="flex flex-wrap -mx-2 mb-3">
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="status">{{ __('Status') }} <span class="text-red-500">*</span></label>
                <select
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="status" id="status" required wire:model="status">
                    @foreach (\App\Enums\PurchaseStatus::cases() as $status)
                        <option {{ $purchase->status == $status ? 'selected' : '' }} value="{{ $status->value }}">
                            {{ __($status->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="payment_method">{{ __('Payment Method') }} <span class="text-red-500">*</span></label>
                <input type="text" wire:model="payment_method"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="payment_method" required readonly>
            </div>
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <label for="paid_amount">{{ __('Amount Received') }} <span class="text-red-500">*</span></label>
                <input id="paid_amount" type="text" wire:model="paid_amount"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="paid_amount" required readonly>
            </div>
        </div>

        <div class="w-full px-3 mb-4">
            <label for="note">{{ __('Note') }}</label>
            <textarea name="note" id="note" rows="5" wire:model="note"
                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">{{ $purchase->note }}</textarea>
        </div>

        <div class="w-full px-3">
            <x-button type="submit" primary class="w-full text-center">
                {{ __('Update Purchase') }}
            </x-button>
        </div>
    </form>
</div>
