<div>
    <x-validation-errors class="mb-4" :errors="$errors" />
    <x-card>
        <div>
            <x-table>
                <x-slot name="thead">
                    <x-table.th>
                        {{ __('Product Name') }}
                    </x-table.th>
                    <x-table.th>
                        {{ __('Product Code') }}
                    </x-table.th>
                    <x-table.th>
                        {{ __('Quantity') }}
                    </x-table.th>
                </x-slot>
                <x-table.tbody>
                    <x-table.tr wire:loading.class.delay="opacity-50">
                        @if (!empty($product))
                            <x-table.td>
                                {{ $product->name }}
                            </x-table.td>
                            <x-table.td>
                                {{ $product->code }}
                            </x-table.td>
                            <x-table.td>
                                {{ $product->quantity }}
                            </x-table.td>
                        @empty
                            <x-table.td colspan="3">
                                <div class="flex justify-center">
                                    {{ __('Please search & select a product!') }}
                                </div>
                            </x-table.td>
                        @endif
                    </x-table.tr>
                </x-table.tbody>
            </x-table>
        </div>

        <div class="mt-3">
            <button wire:click="generateBarcodes({{ $product }}, {{ $quantity }})" type="button"
                class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">
                 {{ __('Generate Barcodes') }}
            </button>
        </div>
    </x-card>
</div>

<div wire:loading wire:target="generateBarcodes" class="w-100">
    <div class="d-flex justify-content-center">
        <x-loading />
    </div>
</div>

@if (!empty($barcodes))
    <div class="text-right mb-3">
        <x-button primary wire:click="getPdf" 
                  wire:loading.attr="disabled" type="button">
            {{__('Download PDF')}}
        </x-button>
    </div>
    <div class="card">
        <div class="p-4">
            <div class="flex flex-wrap justify-center">
                @foreach ($barcodes as $barcode)
                    <div class="lg:w-1/3 md:w-1/4 sm:w-1/2"
                        style="border: 1px solid #ffffff;border-style: dashed;background-color: #48FCFE;">
                        <p class="mt-3 mb-1" style="font-size: 15px;color: #000;">
                            {{ $product->name }}
                        </p>
                        <div>
                            {!! $barcode !!}
                        </div>
                        <p class="text-black font-bold text-lg">
                            {{ __('Price') }} : {{ format_currency($product->price) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
</div>
