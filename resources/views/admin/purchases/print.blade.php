<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('Purchase Details')}}</title>
    <link rel="stylesheet" href="{{ public_path('print/bootstrap.min.css') }}">
</head>

<body>
    <div class="px-4 mx-auto">
        <div class="row">
            <div class="col-xs-12">
                <div style="text-align: center;margin-bottom: 25px;">
                    <img width="180" src="{{ public_path('images/logo-dark.png') }}" alt="Logo">
                    <h4 style="margin-bottom: 20px;">
                        <span>{{ __('Reference') }}::</span> <strong>{{ $purchase->reference }}</strong>
                    </h4>
                </div>
                <div class="card">
                    <div class="p-4">
                        <div class="row mb-4">
                            <div class="col-xs-4 mb-3 mb-md-0">
                                <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">
                                    {{ __('Company Info') }}:</h4>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                @if (settings()->show_email == true)
                                    <div>{{ __('Email') }}: {{ settings()->company_email }}</div>
                                @endif
                                <div>{{ __('Phone') }}: {{ settings()->company_phone }}</div>
                            </div>

                            <div class="col-xs-4 mb-3 mb-md-0">
                                <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">
                                    {{ __('Supplier Info') }}:</h4>
                                <div><strong>{{ $supplier->name }}</strong></div>
                                @if (settings()->show_address == true)
                                <div>{{ $supplier->address }}</div>
                                @endif
                                @if (settings()->show_email == true)
                                <div>{{ __('Email') }}: {{ $supplier->email }}</div>
                                @endif
                                <div>{{ __('Phone') }}: {{ $supplier->phone }}</div>
                            </div>

                            <div class="col-xs-4 mb-3 mb-md-0">
                                <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">
                                    {{ __('Invoice Info') }}:</h4>
                                <div>{{ __('Invoice') }}: <strong>{{ settings()->purchase_prefix }} -
                                        {{ $purchase->reference }}</strong></div>
                                <div>{{ __('Date') }}:
                                    {{ \Carbon\Carbon::parse($purchase->date)->format('d M, Y') }}</div>
                                <div>
                                    {{ __('Status') }}: <strong>{{ $purchase->status }}</strong>
                                </div>
                                <div>
                                    {{ __('Payment Status') }}: <strong>{{ $purchase->payment_status }}</strong>
                                </div>
                                <div>
                                    <strong>{{ __('Status') }}:</strong><br>
                                    @if ($purchase->status == \App\Enums\PurchaseStatus::Pending)
                                        <span clacc="badge badge-warning">{{ __('Pending') }}</span>
                                    @elseif ($purchase->status == \App\Enums\PurchaseStatus::Ordered)
                                        <span clacc="badge badge-info">{{ __('Ordered') }}</span>
                                    @elseif($purchase->status == \App\Enums\PurchaseStatus::Completed)
                                        <span clacc="badge badge-success">{{ __('Completed') }}</span>
                                    @elseif($purchase->status == \App\Enums\PurchaseStatus::Returned)
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

                        <div class="table-responsive-sm" style="margin-top: 30px;">
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
                            <div class="col-xs-4 col-xs-offset-8">
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
                                        @if ( settings()->show_shipping == true )
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
                            <div class="col-xs-12">
                                <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy;
                                    {{ date('Y') }}.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</body>

</html>
