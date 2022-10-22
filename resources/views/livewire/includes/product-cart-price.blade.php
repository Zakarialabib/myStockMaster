<form wire:submit.prevent="updatePrice('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')"
    class="flex  justify-center">
    <input style="min-width: 40px;max-width: 90px;" type="number" class="form-control" type="number" value="{{ $cart_item->price }}"
        min="1" wire:model.defer="price.{{ $cart_item->id }}">
    <button class="hover:bg-red-900 text-lg float-right absolute">
        <i class="fas fa-sync-alt"></i>
    </button>
</form>
