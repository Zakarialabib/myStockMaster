<div class="flex items-center justify-center">
    <input type="number" step="1" min="1"
        wire:model.blur="quantity.{{ $item->id }}"
        wire:change="updateQuantity('{{ $item->rowId }}', '{{ $item->id }}')"
        class="w-16 text-center text-sm px-2 py-1 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
</div>
