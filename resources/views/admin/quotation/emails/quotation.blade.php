<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quotation Details</title>
    <link rel="stylesheet" href="{{ asset('print/bootstrap.min.css') }}">
</head>
<body>
<div class="px-4 mx-auto" style="margin: 20px 0;">
    <div class="row">
        <div class="w-full px-4">
            <div style="text-align: center;margin-bottom: 25px;">
                <img width="180" src="{{ asset('images/logo-dark.png') }}" alt="Logo">
                <h4 style="margin-bottom: 20px;">
                    <span>{{__('Reference')}}::</span> <strong>{{ $quotation->reference }}</strong>
                </h4>
            </div>
            <div class="card">
                <div class="p-4">
                    <div class="flex flex-row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">Company Info:</h4>
                            <div><strong>{{ settings()->company_name }}</strong></div>
                            <div>{{ settings()->company_address }}</div>
                            @if (settings()->show_email == true)
                                    <div>{{ __('Email') }}: {{ settings()->company_email }}</div>
                                @endif
                            <div>{{__('Phone')}}: {{ settings()->company_phone }}</div>
                        </div>

                        <div class="col-md-6 mb-3 mb-md-0">
                            <h4 class="mb-2" style="border-bottom: 1px solid #dddddd;padding-bottom: 10px;">Customer Info:</h4>
                            <div><strong>{{ $customer->name }}</strong></div>
                            <div>{{ $customer->address }}</div>
                            <div>{{__('Email')}}: {{ $customer->email }}</div>
                            <div>{{__('Phone')}}: {{ $customer->phone }}</div>
                        </div>
                    </div>

                    <div class="table-responsive" style="margin-top: 30px;">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="align-middle">Product</th>
                                <th class="align-middle">{{__('Net Unit Price')}}</th>
                                <th class="align-middle">{{__('Quantity')}}</th>
                                <th class="align-middle">{{__('Discount')}}</th>
                                <th class="align-middle">{{__('Tax')}}</th>
                                <th class="align-middle">{{__('Sub Total')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($quotation->quotationDetails as $item)
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
                        <div class="col-md-4 col-md-offset-8">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="left"><strong>{{__('Discount')}} ({{ $quotation->discount_percentage }}%)</strong></td>
                                    <td class="right">{{ format_currency($quotation->discount_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="left"><strong>{{__('Tax')}} ({{ $quotation->tax_percentage }}%)</strong></td>
                                    <td class="right">{{ format_currency($quotation->tax_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="left"><strong>{{__('Shipping')}}</strong></td>
                                    <td class="right">{{ format_currency($quotation->shipping_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="left"><strong>{{__('Grand Total')}}</strong></td>
                                    <td class="right"><strong>{{ format_currency($quotation->total_amount) }}</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 25px;">
                        <div class="w-full px-4">
                            <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy; {{ date('Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
