<!-- Button trigger Discount Modal -->
<span wire:click="$emitSelf('discountModalRefresh', '{{ $cart_item->id }}', '{{ $cart_item->rowId }}')" role="button"
    class="badge badge-warning pointer-event" data-toggle="modal" data-target="#discountModal{{ $cart_item->id }}">
    <i class="bi bi-pencil-square text-white"></i>
</span>
<!-- Discount Modal -->
<x-modal id="discountModal{{ $cart_item->id }}" wire:model="discountModal">
    <x-slot name="title">
        <h5>
            {{ $cart_item->name }}
            <br>
            <x-badge success>
                {{ $cart_item->options->code }}
            </x-badge>
        </h5>
    </x-slot>
    <x-slot name="description">
        <form wire:submit.prevent="productDiscount('{{ $cart_item->rowId }}', '{{ $cart_item->id }}')"
            method="POST">
            <div class="modal-body">
                @if (session()->has('discount_message' . $cart_item->id))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="alert-body">
                            <span>{{ session('discount_message' . $cart_item->id) }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="mb-4">
                    <label>{{ __('Discount Type') }}<span class="text-red-500">*</span></label>
                    <select wire:model="discount_type.{{ $cart_item->id }}"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        required>
                        <option value="fixed">{{__('Fixed')}}</option>
                        <option value="percentage">{{__('Percentage')}}</option>
                    </select>
                </div>
                <div class="mb-4">
                    @if ($discount_type[$cart_item->id] == 'percentage')
                        <label>{{__('Discount(%)')}} <span class="text-red-500">*</span></label>
                        <input wire:model.defer="item_discount.{{ $cart_item->id }}" type="text"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            value="{{ $item_discount[$cart_item->id] }}" min="0" max="100">
                    @elseif($discount_type[$cart_item->id] == 'fixed')
                        <label>{{ __('Discount') }} <span class="text-red-500">*</span></label>
                        <input wire:model.defer="item_discount.{{ $cart_item->id }}" type="text"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            value="{{ $item_discount[$cart_item->id] }}">
                    @endif
                </div>
            </div>
            <div class="w-full px-3">
                <x-button primary type="submit" class="w-full text-center">
                    {{__('Save changes')}}
                </x-button>
            </div>
        </form>
    </x-slot>
    </div>
    </div>
