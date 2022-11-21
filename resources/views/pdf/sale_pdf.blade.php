<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Sale') }} _{{ $sale['reference'] }}</title>
    <link rel="stylesheet" href="{{ asset('/print/pdfStyle.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('/images/' . $setting['logo']) }}">
        </div>
        <div id="company">
            <div><strong> {{ __('Date') }}: </strong>{{ $sale['date'] }}</div>
            <div><strong> {{ __('Number') }}: </strong> {{ $sale['reference'] }}</div>
        </div>
        <div id="Title-heading">
            {{ __('Sale') }} : {{ $sale['reference'] }}
        </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <table class="table-sm">
                    <thead>
                        <tr>
                            <th class="desc">{{ __('Customer Info') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div><strong>Nom:</strong> {{ $sale['client_name'] }}</div>
                                <div><strong>ICE:</strong> {{ $sale['client_ice'] }}</div>
                                <div><strong>Téle:</strong> {{ $sale['client_phone'] }}</div>
                                <div><strong>Adresse:</strong> {{ $sale['client_adr'] }}</div>
                                <div><strong>{{ __('Email') }}:</strong> {{ $sale['client_email'] }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="invoice">
                <table class="table-sm">
                    <thead>
                        <tr>
                            <th class="desc">Company Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div id="comp">{{ $setting['CompanyName'] }}</div>
                                <div><strong>ICE:</strong> {{ $setting['CompanyTaxNumber'] }}</div>
                                <div><strong>Adresse:</strong> {{ $setting['CompanyAdress'] }}</div>
                                <div><strong>Téle:</strong> {{ $setting['CompanyPhone'] }}</div>
                                <div><strong>{{ __('Email') }}:</strong> {{ $setting['email'] }}</div>
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
                        <th>EPRODUIT</th>
                        <th>PRIX UNITAIRE</th>
                        <th>QUANTITE</th>
                        <th>REMISE</th>
                        <th>TAXE</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($details as $detail)
                        <tr>
                            <td>
                                <span>{{ $detail['code'] }} ({{ $detail['name'] }})</span>
                                @if ($detail['is_imei'] && $detail['imei_number'] !== null)
                                    <p>IMEI/SN : {{ $detail['imei_number'] }}</p>
                                @endif
                            </td>
                            <td>{{ $detail['price'] }} </td>
                            <td>{{ $detail['quantity'] }}/{{ $detail['unitSale'] }}</td>
                            <td>{{ $detail['DiscountNet'] }} </td>
                            <td>{{ $detail['taxe'] }} </td>
                            <td>{{ $detail['total'] }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="total">
            <table>
                <tr>
                    <td>Taxe de commande</td>
                    <td>{{ $sale['TaxNet'] }} </td>
                </tr>
                <tr>
                    <td>Remise</td>
                    <td>{{ $sale['discount'] }} </td>
                </tr>
                <tr>
                    <td>Livraison</td>
                    <td>{{ $sale['shipping'] }} </td>
                </tr>
                <tr>
                    <td{{ __('Total') }}< /td>
                        <td>{{ $symbol }} {{ $sale['GrandTotal'] }} </td>
                </tr>

                <tr>
                    <td>Montant payé</td>
                    <td>{{ $symbol }} {{ $sale['paid_amount'] }} </td>
                </tr>

                <tr>
                    <td>Dû</td>
                    <td>{{ $symbol }} {{ $sale['due'] }} </td>
                </tr>
            </table>
        </div>
        <div id="signature">
            @if ($setting['is_invoice_footer'] && $setting['invoice_footer'] !== null)
                <p>{{ $setting['invoice_footer'] }}</p>
            @endif
        </div>
    </main>
</body>

</html>
