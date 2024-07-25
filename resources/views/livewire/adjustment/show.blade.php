<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Adjustment') }} - {{ $adjustment?->date }}
        </x-slot>

        <x-slot name="content">
            <div class="p-4">
                <div>
                    <x-table-responsive>
                        <x-table.tr>
                            <x-table.th>{{ __('Date') }}</x-table.th>
                            <x-table.td>
                                {{ $adjustment?->date }}
                            </x-table.td>
                        </x-table.tr>

                        <x-table.tr>
                            <x-table.th>{{ __('Reference') }}</x-table.th>
                            <x-table.td>
                                {{ $adjustment?->reference }}
                            </x-table.td>
                        </x-table.tr>

                        <x-table.tr>
                            <x-table.th>{{ __('Product Name') }}</x-table.th>
                            <x-table.th>{{ __('Code') }}</x-table.th>
                            <x-table.th>{{ __('Warehouse') }}</x-table.th>
                            <x-table.th>{{ __('Quantity') }}</x-table.th>
                            <x-table.th>{{ __('Type') }}</x-table.th>
                        </x-table.tr>
                        <x-table.tbody>
                            @if ($adjustment != null)
                                @forelse ($adjustment->adjustedProducts as $adjustedProduct)
                                    <x-table.tr>
                                        {{-- @dd($adjustedProduct); --}}
                                        <x-table.td>{{ $adjustedProduct->product->name }}</x-table.td>
                                        <x-table.td>{{ $adjustedProduct->product->code }}</x-table.td>
                                        <x-table.td>{{ $adjustedProduct->warehouse->name }}</x-table.td>
                                        <x-table.td>{{ $adjustedProduct->quantity }}</x-table.td>
                                        <x-table.td>
                                            @if ($adjustedProduct->type == 'add')
                                                {{ __('(+) Addition') }}
                                            @else
                                                {{ __('(-) Subtraction') }}
                                            @endif
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            @endif
                        </x-table.tbody>

                    </x-table-responsive>
                </div>
            </div>
        </x-slot>
    </x-modal>
</div>
