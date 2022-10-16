<div>
    <x-modal wire:model="paymentModal">
        <x-slot name="title">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Sale Payment') }}
            </h2>
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="flex flex-wrap -mx-1">
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="reference" :value="__('Reference')" required />
                        <x-input type="text" wire:model="reference" id="reference" class="block w-full mt-1" required
                            readonly />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="date" :value="__('Date')" required />
                        <x-input type="date" wire:model="date" id="date" class="block w-full mt-1" required />
                    </div>

                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="due_amount" :value="__('Due Amount')" required />
                        <x-input type="text" wire:model="due_amount" id="due_amount" class="block w-full mt-1"
                            required readonly />
                    </div>
                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="amount" :value="__('Amount')" required />
                        <x-input type="text" wire:model="amount" id="amount" class="block w-full mt-1" required />
                    </div>
                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="payment_method" :value="__('Payment Method')" required />
                        <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                            name="payment_method" id="payment_method" required>
                            <option value="Cash">{{ __('Cash') }}</option>
                            <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                            <option value="Cheque">{{ __('Cheque') }}</option>
                            <option value="Other">{{ __('Other') }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <x-label for="note" :value="__('Note')" />
                    <textarea wire:model="note" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                        rows="4" name="note">{{ old('note') }}</textarea>
                </div>

                {{-- <input type="hidden" value="{{ $sale->id }}" name="sale_id"> --}}

                <div class="w-full flex justfiy-start">

                    <x-button wire:click="save" primary type="button">
                        {{ __('Save') }}
                    </x-button>
                    <x-button wire:click="$set('paymentModal', false)" type="button" secondary>
                        {{ __('Cancel') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
