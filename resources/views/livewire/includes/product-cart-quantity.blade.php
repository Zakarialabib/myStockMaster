<div wire:change="updateQuantity('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')">
    <x-input style="min-width: 40px;max-width: 90px;" type="text" value="{{ $cart_item->qty }}"
        min="1" wire:model.defer="quantity.{{ $cart_item->id }}" />
</div>
