<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Customer') }} : {{ $customer->name }}</title>
    <link rel="stylesheet" href="{{ asset('/print/pdfStyle.css') }}" media="all" />
</head>

<body>
    <htmlpageheader name="page-header">
        <div class="centered">
            <div id="logo">
                <img src="{{ asset('images/logo.png') }}">
            </div>
            <h2 style="margin-bottom: 5px;font-size: 16px;">{{ settings()->company_name }}</h2>
            <p>
                {{ settings()->company_phone }} <br>
                {{ settings()->company_address }} <br>
            </p>
            <div id="Title-heading">
                {{ __('Customer') }} : {{ $customer->name }}
            </div>
        </div>
    </htmlpageheader>

    <div>
        <div id="details" class="clearfix">
            <div id="client">
                <table class="table-sm">
                    <thead>
                        <tr>
                            <th class="desc">{{ __('Customer Details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div><strong>{{ __('Name') }}:</strong> {{ $customer->name }}</div>
                                <div><strong>{{ __('Tax_number') }}:</strong> {{ $customer->tax_number }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ $customer->phone }}</div>
                                <div><strong>{{ __('Total Sales') }}:</strong> {{ $customer->total_sales }}</div>
                                <div><strong>{{ __('Total Amount') }}:</strong>
                                    {{ format_currency($customer->total_amount) }}</div>
                                <div><strong>{{ __('Total Paid') }}:</strong>
                                    {{ format_currency($customer->total_paid) }}</div>
                                <div><strong>{{ __('Due') }}:</strong> {{ format_currency($customer->due) }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="invoice">
                <table class="table-sm">
                    <thead>
                        <tr>
                            <th class="desc">{{ __('Company') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div id="comp">{{ settings()->company_name }}</div>
                                <div><strong>{{ __('Tax number') }}</strong> {{ settings()->company_tax }}</div>
                                <div><strong>{{ __('Address') }}:</strong> {{ settings()->company_address }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ settings()->company_phone }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="details_inv">
            <h3 style="margin-bottom:10px">
                {{ __('All Sales') }}
            </h3>
            <table class="table-sm">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Reference') }}</th>
                        <th>{{ __('Payment amount') }}</th>
                        <th>{{ __('Payment status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->date }} </td>
                            <td>{{ $sale->reference }}</td>
                            <td>{{ format_currency($sale->paid_amount) }} </td>
                            <td>
                                @php
                                    $type = $sale->payment_status->getBadgeType();
                                @endphp
                                <x-badge :type="$type">{{ $sale->payment_status->getName() }}</x-badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
