<div>
    {{-- ShowModal Purchase --}}
    <x-modal wire:model.live="showModal">
        <x-slot name="title">
            {{ __('Show Purchase') }} - {{ __('Reference') }}: <strong>{{ $purchase?->reference }}</strong>

            @if ($purchase != null)
                <div class="float-right">
                    <x-button secondary href="{{ route('purchases.pdf', $purchase->id) }}" target="_blank"
                        wire:loading.attr="disabled">
                        <i class="fas fa-file-pdf"></i>
                        {{ __('PDF') }}
                    </x-button>
                </div>
            @endif

        </x-slot>

        <x-slot name="content">
            <div class="w-full px-2 my-5 mx-auto" id="printable-content">

                <div class="flex flex-row mb-4">

                    <div class="md:w-1/2 mb-3 md:mb-0">
                        <h5 class="mb-2 border-bottom pb-2">{{ __('Supplier Info') }}:</h5>
                        <div><strong>{{ $purchase?->supplier?->name }}</strong></div>
                        <div>{{ $purchase?->supplier?->address }}</div>
                        <div>{{ __('Email') }}: {{ $purchase?->supplier?->email }}</div>
                        <div>{{ __('Phone') }}: {{ $purchase?->supplier?->phone }}</div>
                    </div>

                    <div class="md:w-1/2 mb-3 md:mb-0">
                        <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                        <div>{{ __('Invoice') }}:
                            {{ $purchase?->reference }}</strong></div>
                        <div>{{ __('Date') }}:
                            {{ format_date($purchase?->date) }}</div>
                        <div>
                            {{ __('Status') }}:
                            @php
                                $badgeType = $purchase?->status->getBadgeType();
                            @endphp

                            <x-badge :type="$badgeType">{{ $purchase?->status->getName() }}</x-badge>

                        </div>
                        <div>
                            {{ __('Payment Status') }} :
                            {{-- @php
                                $type = $purchase?->payment_id->getBadgeType();
                            @endphp
                            <x-badge :type="$type">
                                {{ $purchase?->payment_id->getName() }}
                            </x-badge> --}}
                            {{ $purchase?->payment_id->getName() }}
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <x-table>
                        <x-slot name="thead">
                            <x-table.th>{{ __('Product') }}</x-table.th>
                            <x-table.th>{{ __('Unit Cost') }}</x-table.th>
                            <x-table.th>{{ __('Quantity') }}</x-table.th>
                            <x-table.th>{{ __('Subtotal') }}</x-table.th>
                        </x-slot>
                        <x-table.tbody>
                            @if ($purchase != null)
                                @foreach ($purchase->purchaseDetails as $item)
                                    <x-table.tr>
                                        <x-table.td class="align-middle">
                                            {{ $item->name }} <br>
                                            <x-badge type="primary">
                                                {{ $item->code }}
                                            </x-badge>
                                        </x-table.td>

                                        <x-table.td class="align-middle">
                                            {{ format_currency($item->unit_price) }}
                                        </x-table.td>

                                        <x-table.td class="align-middle">
                                            {{ $item->quantity }}
                                        </x-table.td>

                                        <x-table.td class="align-middle">
                                            {{ format_currency($item->sub_total) }}
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            @endif
                        </x-table.tbody>
                    </x-table>
                </div>
                <div class="flex flex-row">
                    <div class="w-full px-4 mb-4">
                        <x-table-responsive>
                            @if ($purchase?->discount_percentage)
                                <x-table.tr>
                                    <x-table.heading class="left">
                                        <strong>{{ __('Discount') }}
                                            ({{ $purchase?->discount_percentage }}%)</strong>
                                    </x-table.heading>
                                    <x-table.td class="right">
                                        {{ format_currency($purchase?->discount_amount) }}</x-table.td>
                                </x-table.tr>
                            @endif
                            @if ($purchase?->tax_percentage)
                                <x-table.tr>
                                    <x-table.heading class="left">
                                        <strong>{{ __('Tax') }}
                                            ({{ format_percentage($purchase?->tax_percentage) }})</strong>
                                    </x-table.heading>
                                    <x-table.td class="right">
                                        {{ format_currency($purchase?->tax_amount) }}
                                    </x-table.td>
                                </x-table.tr>
                            @endif
                            @if (settings()->show_shipping == true)
                                <x-table.tr>
                                    <x-table.heading class="left">
                                        <strong>{{ __('Shipping') }}</strong>
                                    </x-table.heading>
                                    <x-table.td class="right">
                                        {{ format_currency($purchase?->shipping_amount) }}</x-table.td>
                                </x-table.tr>
                            @endif
                            <x-table.tr>
                                <x-table.heading class="left">
                                    <strong>{{ __('Grand Total') }}</strong>
                                </x-table.heading>
                                <x-table.td class="right">
                                    <strong>{{ format_currency($purchase?->total_amount) }}</strong>
                                </x-table.td>
                            </x-table.tr>
                        </x-table-responsive>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-modal>
    {{-- End ShowModal --}}


    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            padding-top: 4px;
        }

        thead th {
            background-color: #2980b9;
            color: #ffffff;
            text-align: left;
            border: none;
            padding: 8px;
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        td {
            border: none;
            padding: 8px;
            text-align: left;
        }
    </style>
</div>
