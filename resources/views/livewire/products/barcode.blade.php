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
                    </x-slot>
                    <x-table.tbody>
                        @if (!empty($products))
                            @forelse ($products as $product)
                                <x-table.tr wire:key="{{ $product['id'] }}" >
                                    <x-table.td>{{ $product['name'] }}</x-table.td>
                                    <x-table.td>{{ $product['code'] }}</x-table.td>
                                    <x-table.td style="width: 200px;">
                                        <x-input wire:model="quantity" type="text" value="{{ $quantity }}"
                                            required />
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
            <div class="flex justify-center mt-3">
                <button wire:click="generateBarcodes({{ $product }}, {{ $quantity }})" type="button"
                    class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">
                    {{ __('Generate Barcodes') }}
                </button>
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
            {{-- open print page with getPDF --}}
            <button
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150 bg-indigo-500 hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300"
                wire:click="getPdf" target="_blank" wire:loading.attr="disabled" type="button">
                {{ __('Download PDF') }}
            </button>
        </div>
        <div class="card">
            <div class="p-4">
                <div class="flex flex-wrap justify-center">
                    @foreach ($barcodes as $barcode)
                        @foreach ($products as $product)
                            <div class="lg:w-1/3 md:w-1/4 sm:w-1/2"
                                style="border: 1px solid #ffffff;border-style: dashed;">
                                <p class="text-black font-bold text-lg text-center my-2">
                                    {{ $product->name }}
                                </p>
                                <div>
                                    {!! $barcode !!}
                                </div>
                                <p class="text-black font-bold text-lg text-center my-2">
                                    {{ __('Price') }} : {{ $product->price }}
                                </p>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
