<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ __('Supplier') }} : {{ $supplier->name }}</title>
    <link rel="stylesheet" href="{{ asset('/print/pdfStyle.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('/images/' . $setting['logo']) }}">
        </div>

        <div id="Title-heading">
            {{ __('Supplier') }} : {{ $supplier->name }}
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
                                <div><strong>{{ __('Name') }}:</strong> {{ $supplier->name }}</div>
                                <div><strong>{{ __('Tax_number') }}:</strong> {{ $supplier->tax_number }}</div>
                                <div><strong>{{ __('Phone') }}:</strong> {{ $supplier->phone }}</div>
                                <div><strong>{{ __('Purchase Total') }}:</strong> {{ $supplier->total_purchase }}
                                </div>
                                <div><strong>{{ __('Total Amount') }}:</strong>
                                    {{ format_currency($supplier->total_amount) }}</div>
                                <div><strong>{{ __('Total Paid') }}:</strong>
                                    {{ format_currency($supplier->total_paid) }}</div>
                                <div><strong>{{ __('Due') }}:</strong> {{ format_currency($supplier->due) }}</div>
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
            <h3 style="margin-bottom:10px">
                {{ __('All purchases ( Not Paid/Partial )') }}
            </h3>
            <table class="table-sm">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Reference') }}</th>
                        <th>{{ __('Payment amount') }}</th>
                        <th>{{ __('Due amount') }}</th>
                        <th>{{ __('Payment status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->date }} </td>
                            <td>{{ $purchase->reference }}</td>
                            <td>{{ $purchase->paid_amount }} </td>
                            <td>{{ $purchase->due_amount }} </td>
                            <td>
                                @php
                                    $type = $purchase_return->payment_status->getBadgeType();
                                @endphp
                                <x-badge :type="$type">{{ $purchase_return->payment_status->getName() }}</x-badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
