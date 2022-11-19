<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Customer') }} : {{ $client['name'] }}</title>
    <link rel="stylesheet" href="{{ asset('/print/pdfStyle.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('/images/' . $setting['logo']) }}">
        </div>

        <div id="Title-heading">
            {{ __('Customer') }} : {{ $client['name'] }}
        </div>
        </div>
    </header>
    <main>
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
                                <div><strong>{{ __('Name') }}:</strong> {{ $client['name'] }}</div>
                                <div><strong>{{ __('Tax_number') }}:</strong> {{ $client['tax_number'] }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ $client['phone'] }}</div>
                                <div><strong>{{ __('Total Sales') }}:</strong> {{ $client['total_sales'] }}</div>
                                <div><strong>{{ __('Total Amount') }}:</strong> {{ $symbol }}
                                    {{ $client['total_amount'] }}</div>
                                <div><strong>{{ __('Total Paid') }}:</strong> {{ $symbol }}
                                    {{ $client['total_paid'] }}</div>
                                <div><strong>{{ __('Due') }}:</strong> {{ $symbol }} {{ $client['due'] }}
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
                                <div id="comp">{{ $setting['CompanyName'] }}</div>
                                <div><strong>{{ __('Tax Number') }}:</strong> {{ $setting['CompanyTaxNumber'] }}</div>
                                <div><strong>{{ __('Address') }}:</strong> {{ $setting['CompanyAdress'] }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ $setting['CompanyPhone'] }}</div>
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
                            <td>{{ $sale['date'] }} </td>
                            <td>{{ $$sale['reference'] }}</td>
                            <td>{{ $symbol }} {{ $sale['paid_amount'] }} </td>
                            <td>{{ $sale['payment_status'] }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
