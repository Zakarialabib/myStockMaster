<div>
    <!-- Button trigger Discount Modal -->
    <button type="button" wire:click="discountModal('{{ $cart_item->id }}', '{{ $cart_item->rowId }}')"
        class="border border-red-500 text-red-500 hover:text-reg-800">
        <i class="bi bi-percent text-black"></i>
    </button>
    <!-- Discount Modal -->
    <x-modal wire:model.live="discountModal">
        <x-slot name="title">
            <div class="text-center text-xl">
                {{ $cart_item->name }}
                <x-badge type="success">
                    {{ $cart_item->options->code }}
                </x-badge>
            </div>
        </x-slot>
        <x-slot name="content">
            <form wire:submit="productDiscount('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')">
                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" :errors="$errors" />
                <div class="grid grid-cols-2 gap-4 my-4">
                    <div>
                        <label>{{ __('Discount Type') }}<span class="text-red-500">*</span></label>
                        <select wire:model.live="discount_type.{{ $cart_item->id }}"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required>
                            <option value="fixed">{{ __('Fixed') }}</option>
                            <option value="percentage">{{ __('Percentage') }}</option>
                        </select>
                    </div>
                    <div>
                        @if ($discount_type[$cart_item->id] == 'percentage')
                            <label>{{ __('Discount(%)') }} <span class="text-red-500">*</span></label>
                            <x-input wire:model="item_discount.{{ $cart_item->id }}" type="text"
                                value="{{ $item_discount[$cart_item->id] }}" min="0" max="100" />
                        @elseif($discount_type[$cart_item->id] == 'fixed')
                            <label>{{ __('Discount') }} <span class="text-red-500">*</span></label>
                            <x-input wire:model="item_discount.{{ $cart_item->id }}" type="text"
                                value="{{ $item_discount[$cart_item->id] }}" />
                        @endif
                    </div>
                </div>
                <div class="w-full">
                    <x-button primary type="submit" class="w-full text-center">
                        {{ __('Save changes') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
