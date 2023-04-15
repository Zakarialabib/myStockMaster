<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Quotation Details') }}</title>
    <link rel="stylesheet" href="{{ public_path('print/bootstrap.min.css') }}">
    
</head>

<body>
    <div style="min-width: 600px">
        <div class="row">
            <div class="col-xs-12">
                <div class="text-center">
                    <img width="180" src="{{ public_path('images/logo-dark.png') }}" alt="Logo">
                    <h4>
                        <span>{{ __('Reference') }}:</span> <strong>{{ $quotation->reference }}</strong>
                    </h4>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-4 mb-2 pull-left">
                        <div class="panel panel-default height">
                            <div class="panel-heading">
                                {{ __('Company Info') }}:
                            </div>
                            <div class="panel-body">
                                <p><strong>{{ settings()->company_name }}</strong></p>
                                <p>{{ settings()->company_address }}</p>
                                @if (settings()->show_email == true)
                                    <p>{{ __('Email') }}: {{ settings()->company_email }}</p>
                                @endif
                                <p>{{ __('Phone') }}: {{ settings()->company_phone }}</p>

                            </div>
                        </div>
                    </div>

                    <div class="col-xs-4 mb-2">
                        <div class="panel panel-default height">
                            <div class="panel-heading">
                                {{ __('Customer Info') }}:
                            </div>
                            <div class="panel-body">
                                <p><strong>{{ $customer->name }}</strong></p>
                                @if (settings()->show_address == true)
                                    <p>{{ $customer->address }}</p>
                                @endif
                                @if (settings()->show_email == true)
                                    <p>{{ __('Email') }}: {{ $customer->email }}</p>
                                @endif
                                <p>{{ __('Phone') }}: {{ $customer->phone }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-4 mb-2">
                        <div class="panel panel-default height">
                            <div class="panel-heading">
                                {{ __('Invoice Info') }}:
                            </div>
                            <div class="panel-body">
                                <p>{{ __('Invoice') }}:
                                    <strong>{{ $quotation->reference }}</strong>
                                <div class="panel-body">
                                    <p>{{ __('Date') }}:
                                        {{ format_date($quotation->date) }}</p>
                                    <p>
                                        {{ __('Status') }}: <strong>{{ $quotation->status }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive" style="margin-top: 30px;">
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
                                @foreach ($quotation->quotationDetails as $item)
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
                </div>
                <div class="row">
                    <div class="col-4 col-offset-8">
                        <table class="table">
                            <tbody>
                                @if ($quotation->discount_percentage)
                                    <tr>
                                        <td class="left"><strong>{{ __('Discount') }}
                                                ({{ $quotation->discount_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($quotation->discount_amount) }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($quotation->tax_percentage)
                                    <tr>
                                        <td class="left"><strong>{{ __('Tax') }}
                                                ({{ $quotation->tax_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($quotation->tax_amount) }}</td>
                                    </tr>
                                @endif
                                @if (settings()->show_shipping == true)
                                    <tr>
                                        <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                        <td class="right">{{ format_currency($quotation->shipping_amount) }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="left"><strong>{{ __('Grand Total') }}</strong></td>
                                    <td class="right">
                                        <strong>{{ format_currency($quotation->total_amount) }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row" style="margin-top: 25px;">
                    <div class="col-12">
                        <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy;
                            {{ date('Y') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
