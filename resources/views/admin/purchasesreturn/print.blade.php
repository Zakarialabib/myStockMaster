<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Purchase Return Details') }}</title>
    <link rel="stylesheet" href="{{ public_path('print/bootstrap.min.css') }}">
</head>

<body>
    <div class="px-4 mx-auto">
        <div class="row">
            <div class="col-xs-12">
                <div style="text-align: center;margin-bottom: 25px;">
                    <img width="180" src="{{ public_path('images/logo-dark.png') }}" alt="Logo">
                    <h4 style="margin-bottom: 20px;">
                        <span>{{ __('Reference') }}::</span> <strong>{{ $purchase_return->reference }}</strong>
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
                                @if (settings()->address == true)
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
                                <div>{{ __('Reference') }}: <strong>{{ $purchase_return->reference }}</strong></div>
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
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-8">
                                <table class="table">
                                    <tbody>
                                        @if ($purchase_return->discount_percentage)
                                            <tr>
                                                <td class="left"><strong>{{ __('Discount') }}
                                                        ({{ $purchase_return->discount_percentage }}%)</strong></td>
                                                <td class="right">
                                                    {{ format_currency($purchase_return->discount_amount) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($purchase_return->tax_percentage)
                                            <tr>
                                                <td class="left"><strong>{{ __('Tax') }}
                                                        ({{ $purchase_return->tax_percentage }}%)</strong></td>
                                                <td class="right">{{ format_currency($purchase_return->tax_amount) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if (settings()->show_shipping == true)
                                            <tr>
                                                <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                                <td class="right">
                                                    {{ format_currency($purchase_return->shipping_amount) }}
                                                </td>
                                            </tr>
                                        @endif
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
