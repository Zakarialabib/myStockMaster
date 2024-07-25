<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Quotation') }} - {{ $quotation?->reference }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full">
                <div class="container flex flex-wrap py-3 items-center">
                    @if ($quotation != null)
                        <x-button target="_blank" secondary class="d-print-none"
                            href="{{ route('quotations.pdf', $quotation->id) }}">
                            {{ __('Print') }}
                        </x-button>
                    @endif
                </div>
                <div class="p-4">
                    <div class="flex flex-row mb-4">
                        <div class="md-w-1/4 sm:w-full px-2 mb-2">
                            <h5 class="mb-2 border-b pb-2">{{ __('Company Info') }}:</h5>
                            <div><strong>{{ settings('company_name') }}</strong></div>
                            <div>{{ settings('company_address') }}</div>
                            @if (checkInvoiceControl('show_email'))
                                <div>{{ __('Email') }}: {{ settings('company_email') }}</div>
                            @endif
                            <div>{{ __('Phone') }}: {{ settings('company_phone') }}</div>
                        </div>

                        <div class="md-w-1/4 sm:w-full px-2 mb-2">
                            <h5 class="mb-2 border-b pb-2">{{ __('Customer Info') }}:</h5>
                            <div><strong>{{ $quotation?->customer->name }}</strong></div>
                            @if (checkInvoiceControl('show_address'))
                                <div>{{ $quotation?->customer->address }}</div>
                            @endif
                            @if (checkInvoiceControl('show_email'))
                                <div>{{ __('Email') }}: {{ $quotation?->customer->email }}</div>
                            @endif
                            <div>{{ __('Phone') }}: {{ $quotation?->customer->phone }}</div>
                        </div>

                        <div class="md-w-1/4 sm:w-full px-2 mb-2">
                            <h5 class="mb-2 border-b pb-2">{{ __('Invoice Info') }}:</h5>
                            <div>{{ __('Invoice') }}:
                                <strong>{{ $quotation?->reference }}</strong>
                            </div>
                            <div>{{ __('Date') }}:
                                {{ format_date($quotation?->date) }}</div>
                            <div>
                                {{ __('Status') }}: <strong>{{ $quotation?->status->getName() }}</strong>
                            </div>
                        </div>
                    </div>

                    <x-table>
                        <x-slot name="thead">
                            <x-table.th>{{ __('Product') }}</x-table.th>
                            <x-table.th>{{ __('Net Unit Price') }}</x-table.th>
                            <x-table.th>{{ __('Quantity') }}</x-table.th>
                            <x-table.th>{{ __('Discount') }}</x-table.th>
                            <x-table.th>{{ __('Tax') }}</x-table.th>
                            <x-table.th>{{ __('Sub Total') }}</x-table.th>
                        </x-slot>
                        <x-table.tbody>
                            @if ($quotation != null)
                                @foreach ($quotation?->quotationDetails as $item)
                                    <x-table.tr>
                                        <x-table.td>
                                            {{ $item->name }} <br>
                                            <span class="badge badge-success">
                                                {{ $item->code }}
                                            </span>
                                        </x-table.td>

                                        <x-table.td>{{ format_currency($item->unit_price) }}</x-table.td>

                                        <x-table.td>
                                            {{ $item->quantity }}
                                        </x-table.td>

                                        <x-table.td>
                                            {{ format_currency($item->product_discount_amount) }}
                                        </x-table.td>

                                        <x-table.td>
                                            {{ format_currency($item->product_tax_amount) }}
                                        </x-table.td>

                                        <x-table.td>
                                            {{ format_currency($item->sub_total) }}
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            @endif
                        </x-table.tbody>
                    </x-table>

                    <div class="flex flex-row">
                        <div class="w-full px-4 mb-4">
                            <x-table-responsive>
                                <x-table.tr>
                                    <x-table.td>
                                        <strong>{{ __('Discount') }}
                                            ({{ $quotation?->discount_percentage }}%)</strong>
                                    </x-table.td>
                                    <x-table.td>
                                        {{ format_currency($quotation?->discount_amount) }}
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.td>
                                        <strong>{{ __('Tax') }}
                                            ({{ $quotation?->tax_percentage }}%)</strong>
                                    </x-table.td>
                                    <x-table.td>
                                        {{ format_currency($quotation?->tax_amount) }}
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.td>
                                        <strong>{{ __('Shipping') }}</strong>
                                    </x-table.td>
                                    <x-table.td>
                                        {{ format_currency($quotation?->shipping_amount) }}
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.td>
                                        <strong>{{ __('Grand Total') }}</strong>
                                    </x-table.td>
                                    <x-table.td>
                                        <strong>
                                            {{ format_currency($quotation?->total_amount) }}</strong>
                                    </x-table.td>
                                </x-table.tr>
                            </x-table-responsive>
                        </div>
                    </div>
                </div>
            </div>

        </x-slot>
    </x-modal>
</div>
