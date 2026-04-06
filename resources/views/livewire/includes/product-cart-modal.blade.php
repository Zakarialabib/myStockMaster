<div>
    <button type="button" wire:click="discountModal('{{ $item->id }}', '{{ $item->rowId }}')"
        class="border border-red-500 text-red-500 hover:text-red-700 px-2 py-1 rounded text-xs transition-colors"
        title="{{ __('Apply Discount') }}">
        <i class="fa-solid fa-percent"></i>
    </button>

    <x-modal wire:model="discountModal">
        <div class="flex items-center justify-center gap-3">
            <span class="text-xl font-bold">{{ $item->name }}</span>
            <x-badge type="info">{{ $item->attributes['code'] ?? '' }}</x-badge>
        </div>

        <form wire:submit="productDiscount('{{ $item->rowId }}', '{{ $item->id }}')">
            <x-validation-errors class="mb-4" :errors="$errors" />

            <div class="grid grid-cols-2 gap-4 my-4">
                <div>
                    <x-label for="discount_type_{{ $item->id }}" :value="__('Discount Type')" />
                    <x-select wire:model.blur="discount_type.{{ $item->id }}" id="discount_type_{{ $item->id }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                        <option value="fixed">{{ __('Fixed Amount') }}</option>
                        <option value="percentage">{{ __('Percentage') }}</option>
                    </x-select>
                </div>
                <div>
                    <x-label for="item_discount_{{ $item->id }}" :value="__('Discount Value')" />
                    @if (($discount_type[$item->id] ?? 'fixed') === 'percentage')
                        <x-input wire:model.blur="item_discount.{{ $item->id }}"
                            id="item_discount_{{ $item->id }}" type="number" step="0.01" min="0"
                            max="100" class="mt-1 block w-full" />
                    @else
                        <x-input wire:model.blur="item_discount.{{ $item->id }}"
                            id="item_discount_{{ $item->id }}" type="number" step="0.01" min="0"
                            class="mt-1 block w-full" />
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span>{{ __('Current Price') }}:</span>
                    <span class="font-semibold">{{ format_currency($item->attributes['unit_price'] ?? 0) }}</span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span>{{ __('Current Discount') }}:</span>
                    <span class="font-semibold text-red-500">
                        @if (($discount_type[$item->id] ?? 'fixed') === 'percentage')
                            {{ $item->attributes['product_discount'] ?? 0 }}%
                        @else
                            {{ format_currency($item->attributes['product_discount'] ?? 0) }}
                        @endif
                    </span>
                </div>
            </div>

            <x-button type="submit" class="w-full justify-center" primary>
                <i class="fas fa-save mr-2"></i>
                {{ __('Save Discount') }}
            </x-button>
        </form>
    </x-modal>
</div>
