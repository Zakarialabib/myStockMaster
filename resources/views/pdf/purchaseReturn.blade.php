<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Return') }} _{{ $return_purchase['reference'] }}</title>
    <link rel="stylesheet" href="{{ asset('/print/pdfStyle.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('/images/' . $setting['logo']) }}">
        </div>
        <div id="company">
            <div><strong> {{ __('Date') }}: </strong>{{ $return_purchase['date'] }}</div>
            <div><strong> {{ __('Number') }}: </strong> {{ $return_purchase['reference'] }}</div>
            <div><strong> Réf d'achat: </strong> {{ $return_purchase['purchase_ref'] }}</div>
        </div>
        <div id="Title-heading">
            {{ __('Return') }} {{ $return_purchase['reference'] }}
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
                                <div><strong>{{ __('Name') }}:</strong> {{ $return_purchase->supplier->name }}
                                </div>
                                <div><strong>{{ __('Tax number') }}:</strong>
                                    {{ $return_purchase->supplier?->tax_number }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ $return_purchase->supplier->phone }}
                                </div>
                                <div><strong>{{ __('Address') }}:</strong> {{ $return_purchase->supplier->address }}
                                </div>
                                <div><strong>{{ __('Email') }}:</strong> {{ $return_purchase->supplier->email }}
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
                            <td>{{ $detail->quantity }}/{{ $detail->unit }}</td>
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
                    <td>{{ __('Tax') }}</td>
                    <td>{{ $return_purchase->tax }} </td>
                </tr>
                <tr>
                    <td>{{ __('Discount') }}</td>
                    <td>{{ $return_purchase->discount }} </td>
                </tr>
                <tr>
                    <td>{{ __('Shipping') }}</td>
                    <td>{{ $return_purchase->shipping }} </td>
                </tr>
                <tr>
                    <td>{{ __('Total') }}</td>
                    <td>{{ $return_purchase->total }} </td>
                </tr>

                <tr>
                    <td>{{ __('Paid amount') }}</td>
                    <td>{{ $return_purchase->paid_amount }} </td>
                </tr>

                <tr>
                    <td>{{ __('Due amount') }}</td>
                    <td>{{ $return_purchase->due_amount }} </td>
                </tr>
            </table>
        </div>
        <div id="signature">
            @if (settings()->is_invoice_footer !== null)
                <p>{{ __('Thank you for your business') }}</p>
            @endif
        </div>
    </main>
</body>

</html>
