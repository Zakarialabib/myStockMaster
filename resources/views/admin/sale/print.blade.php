@section('title', __('Sale Details'))

@extends('layouts.print')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="text-center">
                    {{-- <h2><img width="100" src="{{ $image }}" alt="Logo"></h2> --}}
                </div>
                <hr>
                <div class="col-lg-6">
                    <address>
                        <strong>{{ settings()->company_name }}</strong><br>
                        @if (settings()->show_address == true)
                            {{ settings()->company_address }}<br>
                        @endif
                        @if (settings()->show_email == true)
                            {{ __('Email') }}: {{ settings()->company_email }}<br>
                        @endif
                        {{ __('Phone') }}: {{ settings()->company_phone }}<br>
                    </address>

                </div>
                <div class="col-lg-6 text-right">
                    <address>
                        <strong>{{ $customer->name }}:</strong><br>
                        @if (settings()->show_address == true)
                            <div>{{ $customer->address }}</div>
                        @endif

                        @if (settings()->show_email == true)
                            <div>{{ __('Email') }}: {{ $customer->email }}</div>
                        @endif
                        <div>{{ __('Phone') }}: {{ $customer->phone }}</div>
                    </address>
                </div>

                <div class="col-lg-6">
                    <div>
                        <strong>{{ __('Status') }}:</strong><br>
                        @if ($sale->status == \App\Enums\SaleStatus::Pending)
                            <span clacc="badge badge-warning">{{ __('Pending') }}</span>
                        @elseif ($sale->status == \App\Enums\SaleStatus::Ordered)
                            <span clacc="badge badge-info">{{ __('Ordered') }}</span>
                        @elseif($sale->status == \App\Enums\SaleStatus::Completed)
                            <span clacc="badge badge-success">{{ __('Completed') }}</span>
                        @endif
                    </div>
                    <div>
                        <strong>{{ __('Payment Status') }}:</strong><br>
                        @if ($sale->payment_status == \App\Enums\PaymentStatus::Paid)
                            <span clacc="badge badge-success">{{ __('Paid') }}</span>
                        @elseif ($sale->payment_status == \App\Enums\PaymentStatus::Partial)
                            <span clacc="badge badge-warning">{{ __('Partially Paid') }}</span>
                        @elseif($sale->payment_status == \App\Enums\PaymentStatus::Due)
                            <span clacc="badge badge-danger">{{ __('Due') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 text-right">
                    <address>
                        <strong>{{ __('Reference') }}:</strong><br>
                        {{ $sale->reference }}<br>
                        <strong>{{ __('Date') }}:</strong><br>
                        {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}<br><br>
                    </address>
                </div>

            </div>
        </div>

        <div class="row">

            <div id="table" class="table-responsive-sm" style="margin-top: 30px;">
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
            <div class="row">
                <div class="col-xs-4 col-xs-offset-8">
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
                <div class="col-xs-12">
                    <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy;
                        {{ date('Y') }}.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
