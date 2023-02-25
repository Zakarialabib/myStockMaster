@section('title', __('Purchase Details'))

@extends('layouts.print')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>
                    {{ $supplier->name }} - {{__('Purchase Details')}}
                </h2>
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
                        <strong>{{ $supplier->name }}</strong><br>
                        @if (settings()->show_address == true)
                            {{ $supplier->address }}<br>
                        @endif
                        @if (settings()->show_email == true)
                            {{ __('Email') }}: {{ $supplier->email }}<br>
                        @endif
                        {{ __('Phone') }}: {{ $supplier->phone }}<br>
                    </div>
                </div>
                <br>
                <div class="col ">

                    <strong>{{ $purchase->reference }}</strong><br>
                    {{ __('Date') }}:{{ \Carbon\Carbon::parse($purchase->date)->format('d M, Y') }}<br>
                    {{ __('Status') }}: <strong>{{ $purchase->status }}</strong><br>
                    {{ __('Payment Status') }}: <strong>{{ $purchase->payment_status }}</strong>
                    <br>
                </div>
                <div class="col text-right">
                    <div>
                        <strong>{{ __('Status') }}:</strong><br>
                        @if ($purchase->status == \App\Enums\PurchaseStatus::PENDING)
                            <span clacc="badge badge-warning">{{ __('Pending') }}</span>
                        @elseif ($purchase->status == \App\Enums\PurchaseStatus::ORDERED)
                            <span clacc="badge badge-info">{{ __('Ordered') }}</span>
                        @elseif($purchase->status == \App\Enums\PurchaseStatus::COMPLETED)
                            <span clacc="badge badge-success">{{ __('Completed') }}</span>
                        @elseif($purchase->status == \App\Enums\PurchaseStatus::RETURNED)
                            <span clacc="badge badge-success">{{ __('Returned') }}</span>
                        @endif
                    </div>
                    <div>
                        <strong>{{ __('Payment Status') }}:</strong><br>
                        @if ($purchase->payment_status == \App\Enums\PaymentStatus::Paid)
                            <span clacc="badge badge-success">{{ __('Paid') }}</span>
                        @elseif ($purchase->payment_status == \App\Enums\PaymentStatus::Partial)
                            <span clacc="badge badge-warning">{{ __('Partially Paid') }}</span>
                        @elseif($purchase->payment_status == \App\Enums\PaymentStatus::Due)
                            <span clacc="badge badge-danger">{{ __('Due') }}</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div style="margin-top: 30px;">
            <table>
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
        </div>
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
                                        ({{ $purchase->tax_percentage }}%)</strong>
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
        <div class="row" style="margin-top: 25px;">
            <div class="col-12">
                <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy;
                    {{ date('Y') }}.</p>
            </div>
        </div>
    </div>
@endsection
