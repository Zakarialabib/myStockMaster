<div>
    <form wire:submit.prevent="update">
        <div class="flex flex-wrap -mx-2 mb-3">
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <x-label for="reference" :value="__('Reference')" required />
                <input type="text" wire:model.lazy="quotation.reference"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="reference" required value="{{ $quotation->reference }}" readonly>

            </div>
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <x-label for="customer_id" :value="__('Customer')" required />
                <select wire:model.lazy="quotation.customer_id"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="customer_id" id="customer_id" required>
                    @foreach (\App\Models\Customer::all() as $customer)
                        <option {{ $quotation->customer_id == $customer->id ? 'selected' : '' }}
                            value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                    <x-input-error :messages="$errors->get('quotation.customer_id')" for="quotation.customer_id" class="mt-2" />
                </select>
            </div>
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <x-label for="date" :value="__('Date')" required />
                <input type="date" wire:model.lazy="quotation.date"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="date" required>
                <x-input-error :messages="$errors->get('quotation.date')" for="quotation.date" class="mt-2" />
            </div>
        </div>

        <livewire:product-cart :cartInstance="'quotation'" :data="$quotation" />

        <div class="flex flex-wrap -mx-2 mb-3">
            <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                <x-label for="status" :value="__('Status')" required />
                <select wire:model.lazy="quotation.status"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    name="status" id="status" required>
                    @foreach (\App\Enums\QuotationStatus::cases() as $status)
                        <option {{ $quotation->status == $status->value ? 'selected' : '' }}
                            value="{{ $status->value }}">
                            {{ __($status->name) }}
                        </option>
                    @endforeach
                    <x-input-error :messages="$errors->get('quotation.status')" for="quotation.status" class="mt-2" />
                </select>
            </div>
        </div>

        <div class="px-3 mb-4">
            <label for="note">{{ __('Note (If Needed)') }}</label>
            <textarea name="note" id="note" rows="5" wire:model.lazy="quotation.note"
                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">{{ $quotation->note }}</textarea>
        </div>

        <div class="w-full mt-3">
            <x-button type="submit" primary>
                {{ __('Update Quotation') }}
            </x-button>
        </div>
    </form>
</div>
