@section('title', __('Purchase Details'))

@extends('layouts.print')

@section('content')
    <div class="container">

        <x-printHeader :customer="$customer" :sale="$purchase" :logo="$logo" style="centered" />

        <br>

        <div style="margin-top: 20px;">
            <table style="border-collapse:collapse">
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
                    @foreach ($purchase->purchaseDetails as $item)
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
            <div class="row">
                <div class="col">
                    <table class="table">
                        <tbody>
                            @if ($purchase->discount_percentage)
                                <tr>
                                    <td class="left">
                                        <strong>{{ __('Discount') }}
                                            ({{ $purchase->discount_percentage }}%)
                                        </strong>
                                    </td>
                                    <td class="right">{{ format_currency($purchase->discount_amount) }}
                                    </td>
                                </tr>
                            @endif
                            @if ($purchase->tax_percentage)
                                <tr>
                                    <td class="left"><strong>{{ __('Tax') }}
                                            ({{ format_percentage($purchase->tax_percentage) }})</strong>
                                    </td>
                                    <td class="right">{{ format_currency($purchase->tax_amount) }}</td>
                                </tr>
                            @endif
                            @if (settings()->show_shipping == true)
                                <tr>

                                    <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                    <td class="right">{{ format_currency($purchase->shipping_amount) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="left"><strong>{{ __('Grand Total') }}</strong></td>
                                <td class="right">
                                    <strong>{{ format_currency($purchase->total_amount) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
