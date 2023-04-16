<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Payment Purchase Return') }}_{{ $payment['reference'] }}</title>
    <link rel="stylesheet" href="{{ asset('/print/pdfStyle.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('images/logo.png') }}">
        </div>
        <div id="company">
            <div><strong> {{ __('Date') }}: </strong>{{ $payment->date }}</div>
        </div>
        <div id="Title-heading">
            {{ __('Payment Reference') }} : {{ $payment->reference }}
        </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <table class="table-sm">
                    <thead>
                        <tr>
                            <th class="desc">{{ __('Supplier Info') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div><strong>{{ __('Name') }}:</strong> {{ $payment->supplier->name }}</div>
                                <div><strong>{{ __('Tax number') }}:</strong> {{ $payment->supplier?->tax_number }}
                                </div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ $payment->supplier->phone }}</div>
                                <div><strong>{{ __('Address') }}:</strong> {{ $payment->supplier->address }}</div>
                                <div><strong>{{ __('Email') }}:</strong> {{ $payment->supplier->email }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="invoice">
                <table class="table-sm">
                    <thead>
                        <tr>
                            <th class="desc">{{ __('Company Info') }}</th>
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
            <table class="table-sm">
                <thead>
                    <tr>
                        <th>{{ __('Return') }}</th>
                        <th>{{ __('Paid By') }}</th>
                        <th>{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $payment->returnpurchase->reference }}</td>
                        <td>{{ $payment->paid_amount }}</td>
                        <td>{{ $payment->amount }} </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div>
            @if (settings()->invoice_footer_text)
                <p>{{ settings()->invoice_footer_text }}</p>
            @endif
        </div>
    </main>
</body>

</html>
