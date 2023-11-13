<div>
    <div class="flex flex-row">
        <div class="w-full px-2">
            <livewire:search-product />
        </div>

        <div class="w-full px-2">
            <x-validation-errors class="mb-4" :errors="$errors" />
            <x-select-list :options="$this->warehouses" wire:model="warehouse_id" label="Warehouse" required class="mb-3" />
            <x-table>
                <x-slot name="thead">
                    <x-table.th>{{ __('Product Name') }}</x-table.th>
                    <x-table.th>{{ __('Price') }}</x-table.th>
                    <x-table.th>
                        {{ __('Quantity') }} <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip"
                            data-placement="top" title="Max Quantity: 100"></i>
                    </x-table.th>
                    <x-table.th>{{ __('Size') }}</x-table.th>
                    <x-table.th></x-table.th>
                </x-slot>
                <x-table.tbody>
                    @if (!empty($products))
                        @forelse ($products as $index => $product)
                            <x-table.tr wire:key="{{ $product['id'] }}">
                                <x-table.td>{{ $product['name'] }}</x-table.td>
                                <x-table.td>{{ $product['price'] }}</x-table.td>
                                <x-table.td style="width: 200px;">
                                    <x-input wire:model="products.{{ $index }}.quantity" type="text"
                                        min="0" max="100" required />
                                </x-table.td>
                                <x-table.td>
                                    <select name="barcodeSize" id="barcodeSize"
                                        wire:model="products.{{ $index }}.barcodeSize">
                                        <option value="1">{{ __('Small') }}</option>
                                        <option value="2">{{ __('Medium') }}</option>
                                        <option value="3">{{ __('Large') }}</option>
                                        <option value="4">{{ __('Extra') }}</option>
                                        <option value="5">{{ __('Huge') }}</option>
                                    </select>
                                </x-table.td>
                                <x-table.td>
                                    <x-button danger type="button" wire:click="deleteProduct({{ $product['id'] }})"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        <i class="bi bi-trash"></i>
                                    </x-button>
                                </x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.td colspan="3" class="text-center">
                                <span class="text-red-500">{{ __('Please search & select a product!') }}</span>
                            </x-table.td>
                        @endforelse
                    @endif
                </x-table.tbody>
            </x-table>
            <div class="flex justify-center text-center mt-3">
                <x-button wire:click="generateBarcodes" type="button" primary class="w-full">
                    {{ __('Generate Barcodes') }}
                </x-button>
            </div>
        </div>
    </div>
    <div class="w-full px-2 py-6">

        <div wire:loading wire:target="generateBarcodes" class="w-full">
            <div class="flex justify-center">">
                <x-loading />
            </div>
        </div>

        @if (!empty($barcodes))
            <div class="flex flex-wrap justify-center border-collapse" wire:loading.class="opacity-50">
                @foreach ($barcodes as $barcode)
                    <div class="lg:w-1/3 md:w-1/4 sm:w-1/2" style="border: 1px solid #ffffff; border-style: dashed;">
                        <p class="text-black font-bold text-lg text-center my-2">
                            {{ $barcode['name'] }}
                        </p>
                        <div class="flex justify-center">
                            {!! $barcode['barcode'] !!}
                        </div>
                        <p class="text-black font-bold text-lg text-center my-2">
                            {{ format_currency($barcode['price']) }}
                        </p>
                    </div>
                @endforeach
            </div>
            <div class="text-center my-3">
                <x-button primary wire:click="downloadBarcodes" type="button">
                    {{ __('Download PDF') }}
                </x-button>
            </div>
        @endif
    </div>
</div>
