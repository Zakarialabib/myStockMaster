<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Purchase') }} {{ $purchase->reference }}</title>

    <link rel="stylesheet" href="{{ asset('/print/pdfStyle.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('/images/' . $setting['logo']) }}">
        </div>
        <div id="company">
            <div><strong> {{ __('Date') }}: </strong>{{ $purchase->date }}</div>
            <div><strong> {{ __('Number') }}: </strong> {{ $purchase->reference }}</div>
        </div>
        <div id="Title-heading">
            {{ __('Purchase') }} : {{ $purchase->reference }}
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
                                <div><strong>{{ __('Name') }}:</strong> {{ $purchase->supplier->name }}</div>
                                <div><strong>{{ __('Tax number') }}:</strong> {{ $purchase->tax_number }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ $purchase->phone }}</div>
                                <div><strong>{{ 'Address' }}:</strong> {{ $purchase->adress }}</div>
                                <div><strong>{{ __('Email') }}:</strong> {{ $purchase->email }}</div>
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
                                <div><strong>{{ __('Email') }}:</strong> {{ settings()->company_email }}</div>
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
                        <th>{{ __('PRODUCT') }}</th>
                        <th>{{ __('UNIT COST') }}</th>
                        <th>{{ __('QUANTITY') }}</th>
                        <th>{{ __('DISCOUNT') }}</th>
                        <th>{{ __('TAX') }}</th>
                        <th>{{ __('TOTAL') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($details as $detail)
                        <tr>
                            <td>
                                <span>{{ $detail->code }} ({{ $detail->name }})</span>
                            </td>
                            <td>{{ $detail->cost }} </td>
                            <td>{{ $detail->quantity }}/{{ $detail->unit_purchase }}</td>
                            <td>{{ $detail->discount }} </td>
                            <td>{{ $detail->tax }} </td>
                            <td>{{ $detail->total_amount }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="total">
            <table>
                <tr>
                    <td>{{ __('Order Tax') }}</td>
                    <td>{{ $purchase->tax_amount }} </td>
                </tr>
                <tr>
                    <td>{{ __('Discount') }}</td>
                    <td>{{ $purchase->discount }} </td>
                </tr>
                <tr>
                    <td>{{ __('Shipping') }}</td>
                    <td>{{ $purchase->shipping }} </td>
                </tr>
                <tr>
                    <td>{{ __('Total') }}</td>
                    <td>{{ $purchase->total }} </td>
                </tr>

                <tr>
                    <td>{{ __('Paid Amount') }}</td>
                    <td>{{ $purchase->paid_amount }} </td>
                </tr>

                <tr>
                    <td>{{ __('Due') }}</td>
                    <td>{{ $purchase->due }} </td>
                </tr>
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
