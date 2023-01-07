<div>

    <!-- Show Modal -->
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Product') }} - {{ $product->code }}
        </x-slot>

        <x-slot name="content">
            <div class="px-4 mx-auto mb-4">
                {{-- Send telegram --}}
                <div class="flex justify-center w-full my-5 px-3">
                    <x-button success type="button" wire:click="sendTelegram({{ $product->id }})"
                        wire:loading.attr="disabled">
                        <i class="fas fa-edit"></i>
                        {{ __('Send to telegram') }}
                    </x-button>
                </div>
                <div class="flex flex-row mb-3">
                    <div class="lg:w-1/2 sm:w-full px-3">
                        {!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG($product->code, $product->barcode_symbology, 2, 110) !!}
                    </div>
                    @if ($product->image)
                        <div class="lg:w-1/2 sm:w-full px-3">
                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-32 h-32 rounded-full">
                        </div>
                    @endif
                </div>

                <div class="flex flex-row">
                    <div class="w-full px-4">
                        <x-table-responsive>
                            <x-table.tr>
                                <x-table.th>{{ __('Product Code') }}</x-table.th>
                                <x-table.td>{{ $product->code }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Barcode Symbology') }}</x-table.th>
                                <x-table.td>{{ $product->barcode_symbology }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Name') }}</x-table.th>
                                <x-table.td>{{ $product->name }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Category') }}</x-table.th>
                                <x-table.td>{{ $product->category->name }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Cost') }}</x-table.th>
                                <x-table.td>{{ format_currency($product->cost) }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Price') }}</x-table.th>
                                <x-table.td>{{ format_currency($product->price) }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Quantity') }}</x-table.th>
                                <x-table.td>{{ $product->quantity . ' ' . $product->unit }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Stock Worth') }}</x-table.th>
                                <x-table.td>
                                    {{ __('COST') }}::
                                    {{ format_currency($product->cost * $product->quantity) }}
                                    /
                                    {{ __('PRICE') }}::
                                    {{ format_currency($product->price * $product->quantity) }}
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Alert Quantity') }}</x-table.th>
                                <x-table.td>{{ $product->stock_alert }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Tax (%)') }}</x-table.th>
                                <x-table.td>{{ $product->order_tax ?? 'N/A' }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Tax Type') }}</x-table.th>
                                <x-table.td>
                                    @if ($product->tax_type == 1)
                                        {{ __('Exclusive') }}
                                    @elseif($product->tax_type == 2)
                                        {{ __('Inclusive') }}
                                    @else
                                        N/A
                                    @endif
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{ __('Description') }}</x-table.th>
                                <x-table.td>{{ $product->note ?? 'N/A' }}</x-table.td>
                            </x-table.tr>
                        </x-table-responsive>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-modal>
    <!-- End Show Modal -->
