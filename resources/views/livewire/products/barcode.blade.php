<div>
    <x-validation-errors class="mb-4" :errors="$errors" />

    <div class="card">
        <div class="p-4">
            <div>
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>{{ __('Product Name') }}</x-table.th>
                        <x-table.th>{{ __('Code') }}</x-table.th>
                        <x-table.th>
                            {{ __('Quantity') }} <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip"
                                data-placement="top" title="Max Quantity: 100"></i>
                        </x-table.th>
                        <x-table.th>{{ __('Size') }}</x-table.th>
                        <x-table.th></x-table.th>
                    </x-slot>
                    <x-table.tbody>
                        @if (!empty($products))
                            @forelse ($products as $product)
                                <x-table.tr wire:key="{{ $product['id'] }}">
                                    <x-table.td>{{ $product['name'] }}</x-table.td>
                                    <x-table.td>{{ $product['code'] }}</x-table.td>
                                    <x-table.td style="width: 200px;">
                                        <x-input wire:model.lazy="quantity.{{ $product['id'] }}" type="text"
                                            min="0" max="100" required />
                                    </x-table.td>
                                    <x-table.td>
                                        <select name="barcodeSize" id="barcodeSize"
                                            wire:model.lazy="barcodeSize.{{ $product['id'] }}">
                                            <option value="1">{{ __('Small') }}</option>
                                            <option value="2">{{ __('Medium') }}</option>
                                            <option value="3">{{ __('Large') }}</option>
                                            <option value="4">{{ __('Extra') }}</option>
                                            <option value="5">{{ __('Huge') }}</option>
                                        </select>
                                    </x-table.td>
                                    <x-table.td>
                                        <button wire:click="deleteProduct({{ $product['id'] }})"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            {{ __('Delete') }}
                                        </button>
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
            </div>
            <div class="flex justify-center text-center mt-3">
                <x-button wire:click="generateBarcodes" type="button" primary class="w-full">
                    {{ __('Generate Barcodes') }}
                </x-button>
            </div>
        </div>
    </div>

    <div wire:loading wire:target="generateBarcodes" class="w-100">
        <div class="d-flex justify-content-center">
            <x-loading />
        </div>
    </div>

    @if (!empty($barcodes))
        <div class="text-center mb-3">
            <x-button primary wire:click="downloadBarcodes" type="button">
                {{ __('Download PDF') }}
            </x-button>
        </div>
        <div class="card">
            <div class="p-4">
                <div class="flex flex-wrap justify-center border-collapse">
                    @foreach ($barcodes as $barcode)
                        <div class="lg:w-1/3 md:w-1/4 sm:w-1/2"
                            style="border: 1px solid #ffffff; border-style: dashed;">
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
            </div>
        </div>
    @endif
</div>
