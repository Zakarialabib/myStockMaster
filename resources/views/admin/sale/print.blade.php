@section('title', __('Sale Details'))

@extends('layouts.print')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <h2>
                        {{ $customer->name }} - {{ __('Sale Details') }}
                    </h2>
                </div>
                <hr>
                <div class="col">
                    <div>
                        <strong>{{ settings()->company_name }}</strong><br>
                        @if (settings()->show_address == true)
                            {{ settings()->company_address }}<br>
                        @endif
                        @if (settings()->show_email == true)
                            {{ __('Email') }}: {{ settings()->company_email }}<br>
                        @endif
                        {{ __('Phone') }}: {{ settings()->company_phone }}<br>
                    </div>
                </div>
                <div class="col text-right">
                    <div>
                        <strong>{{ $customer->name }}:</strong><br>
                        @if (settings()->show_address == true)
                            <div>{{ $customer->address }}</div>
                        @endif

                        @if (settings()->show_email == true)
                            <div>{{ __('Email') }}: {{ $customer->email }}</div>
                        @endif
                        <div>{{ __('Phone') }}: {{ $customer->phone }}</div>
                    </div>
                </div>

                <div class="col">

                    <strong>{{ __('Status') }}:</strong><br>
                    @if ($sale->status == \App\Enums\SaleStatus::PENDING)
                        <span clacc="badge badge-warning">{{ __('Pending') }}</span>
                    @elseif ($sale->status == \App\Enums\SaleStatus::ORDERED)
                        <span clacc="badge badge-info">{{ __('Ordered') }}</span>
                    @elseif($sale->status == \App\Enums\SaleStatus::COMPLETED)
                        <span clacc="badge badge-success">{{ __('Completed') }}</span>
                    @endif
                    <br>
                    <strong>{{ __('Payment Status') }}:</strong><br>
                    @if ($sale->payment_status == \App\Enums\PaymentStatus::PAID)
                        <span clacc="badge badge-success">{{ __('Paid') }}</span>
                    @elseif ($sale->payment_status == \App\Enums\PaymentStatus::PARTIAL)
                        <span clacc="badge badge-warning">{{ __('Partially Paid') }}</span>
                    @elseif($sale->payment_status == \App\Enums\PaymentStatus::DUE)
                        <span clacc="badge badge-danger">{{ __('Due') }}</span>
                    @endif

                </div>
                <div class="col text-right">
                    <div>
                        <strong>{{ __('Reference') }}:</strong><br>
                        {{ $sale->reference }}<br>
                        <strong>{{ __('Date') }}:</strong><br>
                        {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}<br><br>
                    </div>
                </div>

            </div>
        </div>
        <br>
        <div class="row">
            <div style="margin-top: 30px;">
                <table>
                    <thead>
                        <tr class="title">
                            <th class="align-middle">{{ __('Product') }}</th>
                            <th class="align-middle">{{ __('Net Unit Price') }}</th>
                            <th class="align-middle">{{ __('Quantity') }}</th>
                            <th class="align-middle">{{ __('Discount') }}</th>
                            <th class="align-middle">{{ __('Tax') }}</th>
                            <th class="align-middle">{{ __('Sub Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->saleDetails as $item)
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
            <div class="row" style="padding:10px 15px">
                <div class="col">
                    <table class="table">
                        <tbody>
                            @if ($sale->discount_percentage)
                                <tr>
                                    <td class="left"><strong>{{ __('Discount') }}
                                            ({{ $sale->discount_percentage }}%)</strong></td>
                                    <td class="right">{{ format_currency($sale->discount_amount) }}</td>
                                </tr>
                            @endif
                            @if ($sale->tax_percentage)
                                <tr>
                                    <td class="left"><strong>{{ __('Tax') }}
                                            ({{ $sale->tax_percentage }}%)</strong></td>
                                    <td class="right">{{ format_currency($sale->tax_amount) }}</td>
                                </tr>
                            @endif
                            @if (settings()->show_shipping == true)
                                <tr>
                                    <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                    <td class="right">{{ format_currency($sale->shipping_amount) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="left"><strong>{{ __('Grand Total') }}</strong></td>
                                <td class="right">
                                    <strong>{{ format_currency($sale->total_amount) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-12">
                    <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy;
                        {{ date('Y') }}.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
