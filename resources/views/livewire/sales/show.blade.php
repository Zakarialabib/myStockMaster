<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Sale') }} - {{ __('Reference') }}: <strong>{{ $sale?->reference }}</strong>

            @if ($sale != null)
                <div class="float-right">
                    <x-button secondary class="d-print-none" type="button" onclick="printContent()">
                        <i class="fas fa-print"></i> {{ __('Print') }}
                    </x-button>
                </div>
            @endif
        </x-slot>

        <x-slot name="content">
            <div class="w-full px-2 my-5 mx-auto" id="printable-content">

                <div class="flex flex-row mb-4">

                    <div class="md:w-1/2 mb-3 md:mb-0">
                        <h5 class="mb-2 border-bottom pb-2">{{ __('Customer Info') }}:</h5>
                        <div><strong>{{ $sale?->customer?->name }}</strong></div>
                        <div>{{ $sale?->customer?->address }}</div>
                        <div>{{ __('Email') }}: {{ $sale?->customer?->email }}</div>
                        <div>{{ __('Phone') }}: {{ $sale?->customer?->phone }}</div>
                    </div>

                    <div class="md:w-1/2 mb-3 md:mb-0">
                        <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                        <div>{{ __('Invoice') }}:
                            <strong>{{ $sale?->reference }}</strong>
                        </div>
                        <div>{{ __('Date') }}:
                            {{ format_date($sale?->date) }}</div>
                        <div>
                            {{ __('Status') }} :
                            @php
                                $badgeType = $sale?->status->getBadgeType();
                            @endphp

                            <x-badge :type="$badgeType">{{ $sale?->status->getName() }}</x-badge>
                        </div>
                        <div>
                            {{ __('Payment Status') }} :
                            @php
                                $type = $sale?->payment_status->getBadgeType();
                            @endphp
                            <x-badge :type="$type">{{ $sale?->payment_status->getName() }}</x-badge>
                        </div>
                    </div>

                </div>

                <div class="my-4">
                    <table style="margin:10px 0">
                        <thead>
                            <tr>
                                <th>{{ __('Product') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Unit Price') }}</th>
                                <th>{{ __('Subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($sale != null)
                                @foreach ($sale->saleDetails as $item)
                                    <tr>
                                        <td>
                                            {{ $item->name }} <br>
                                            {{ $item->code }}
                                        </td>
                                        <td>
                                            {{ $item->quantity }}
                                        </td>
                                        <td>
                                            {{ format_currency($item->unit_price) }}
                                        </td>
                                        <td>
                                            {{ format_currency($item->sub_total) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="w-full mb-4">
                    <table class="table">
                        <tbody>
                            @if (settings()->show_order_tax == true)
                                <tr>
                                    <td class="left"><strong>{{ __('Discount') }}
                                            ({{ $sale?->discount_percentage }}%)</strong></td>
                                    <td class="right">
                                        {{ format_currency($sale?->discount_amount) }}
                                    </td>
                                </tr>
                            @endif

                            @if (settings()->show_discount == true)
                                <tr>
                                    <td class="left"><strong>{{ __('Tax') }}
                                            ({{ $sale?->tax_percentage }}%)</strong></td>
                                    <td class="right">
                                        {{ format_currency($sale?->tax_amount) }}
                                    </td>
                                </tr>
                            @endif
                            @if (settings()->show_shipping == true)
                                <tr>
                                    <td class="left"><strong>{{ __('Shipping') }}</strong>
                                    </td>
                                    <td class="right">
                                        {{ format_currency($sale?->shipping_amount) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="left"><strong>{{ __('Grand Total') }}</strong>
                                </td>
                                <td class="right">
                                    <strong>{{ format_currency($sale?->total_amount) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>
    </x-modal>



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
