@extends('layouts.app')

@section('title', 'Purchase Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">Purchase Returns</a></li>
        <li class="breadcrumb-item active">{{ __('Details') }}</li>
    </ol>
@endsection

@section('content')
    <div class="px-4 mx-auto">
        <div class="row">
            <div class="w-full px-4">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center">
                        <div>
                            {{ __('Reference') }}: <strong>{{ $purchase_return->reference }}</strong>
                        </div>
                        <a target="_blank" class="btn-secondary mfs-auto mfe-1 d-print-none"
                            href="{{ route('purchase-returns.pdf', $purchase_return->id) }}">
                            <i class="bi bi-printer"></i> {{ __('Print') }}
                        </a>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-row mb-4">
                            <div class="md-w-1/4 sm:w-full px-2 mb-2">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Company Info') }}:</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                @if (settings()->show_email == true)
                                    <div>{{ __('Email') }}: {{ settings()->company_email }}</div>
                                @endif
                                <div>{{ __('Phone') }}: {{ settings()->company_phone }}</div>
                            </div>

                            <div class="md-w-1/4 sm:w-full px-2 mb-2">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Supplier Info') }}:</h5>
                                <div><strong>{{ $supplier->name }}</strong></div>
                                @if (settings()->show_address == true)
                                    <div>{{ $supplier->address }}</div>
                                @endif
                                @if (settings()->show_email == true)
                                    <div>{{ __('Email') }}: {{ $supplier->email }}</div>
                                @endif
                                <div>{{ __('Phone') }}: {{ $supplier->phone }}</div>
                            </div>

                            <div class="md-w-1/4 sm:w-full px-2 mb-2">
                                <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                                <div>{{ __('Invoice') }}: <strong>{{ $purchase_return->reference }}</strong></div>
                                <div>{{ __('Date') }}:
                                    {{ format_date($purchase_return->date) }}</div>
                                <div>
                                    {{ __('Status') }}:
                                    @php
                                        $type = $purchase_return->status->getBadgeType();
                                    @endphp
                                    <x-badge :type="$type">{{ $purchase_return->status->getName() }}</x-badge>
                                </div>
                                <div>
                                    {{ __('Payment Status') }}:
                                    @php
                                        $type = $purchase_return->payment_status->getBadgeType();
                                    @endphp
                                    <x-badge :type="$type">{{ $purchase_return->payment_status->getName() }}</x-badge>

                                </div>
                            </div>

                        </div>

                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="align-middle">{{ __('Product') }}</th>
                                        <th class="align-middle">{{ __('Net Unit Price') }}</th>
                                        <th class="align-middle">{{ __('Quantity') }}</th>
                                        <th class="align-middle">{{ __('Discount') }}</th>
                                        <th class="align-middle">{{ __('Tax') }}</th>
                                        <th class="align-middle">{{ __('Sub Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase_return->purchaseReturnDetails as $item)
                                        <tr>
                                            <td class="align-middle">
                                                {{ $item->name }} <br>
                                                <span class="badge badge-success">
                                                    {{ $item->code }}
                                                </span>
                                            </td>

                                            <td class="align-middle">{{ format_currency($item->unit_price) }}</td>

                                            <td class="align-middle">
                                                {{ $item->quantity }}
                                            </td>

                                            <td class="align-middle">
                                                {{ format_currency($item->product_discount_amount) }}
                                            </td>

                                            <td class="align-middle">
                                                {{ format_currency($item->product_tax_amount) }}
                                            </td>

                                            <td class="align-middle">
                                                {{ format_currency($item->sub_total) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="w-full px-4 mb-4">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="left"><strong>{{ __('Discount') }}
                                                ({{ $purchase_return->discount_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($purchase_return->discount_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __('Tax') }}
                                                ({{ format_percentage($purchase_return->tax_percentage) }})</strong></td>
                                        <td class="right">{{ format_currency($purchase_return->tax_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                        <td class="right">{{ format_currency($purchase_return->shipping_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>{{ __('Grand Total') }}</strong></td>
                                        <td class="right">
                                            <strong>{{ format_currency($purchase_return->total_amount) }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
