<div x-data="{
    editPrice: false,
    selectedRowId: null,
    buttonHidden: false,
}">

    <button type="button" @click="editPrice = !editPrice; selectedRowId = '{{ $cart_item->rowId }}'; buttonHidden = true"
        x-show="!buttonHidden">
        <i class="fa fa-pen text-red-500"></i>
    </button>

    <div x-show="editPrice && selectedRowId === '{{ $cart_item->rowId }}'">
        <form wire:change="updatePrice('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')" class="flex  justify-center">
            <x-input type="text" wire:model="price.{{ $cart_item->id }}" />
        </form>
    </div>
</div>
