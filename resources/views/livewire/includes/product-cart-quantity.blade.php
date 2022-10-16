<form wire:submit.prevent="updateQuantity('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')">
    <input wire:model="quantity.{{ $cart_item->id }}" type="number" class="px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" value="{{ $cart_item->qty }}" min="1">
</form>
