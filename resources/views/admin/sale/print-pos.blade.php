<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> --}}

    <title>{{ __('Sale') }} : {{ $sale->reference }}</title>

    <style>
        @font-face {
            font-family: 'Cairo';
            src: url('./fonts/cairo.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            font-family: 'Cairo' !important;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-size: 13px;
            line-height: 15px;
            height: 100%;
            -webkit-font-smoothing: antialiased;
        }

        div,
        p,
        a,
        li,
        td {
            -webkit-text-size-adjust: none;
        }


        p {
            padding: 0 !important;
            margin-top: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 0 !important;
            margin- left: 0 !important;
            font-size: 11px;
            line-height: 13px;
        }

        .title {
            background: #EEE;
        }

        td,
        th,
        tr {
            border-collapse: collapse;
            padding: 5px 0;
        }

        tr {
            border-bottom: 1px dashed #ddd;
            border-top: 1px dashed #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            padding-top: 4px;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        small {
            font-size: 11px;
        }

        @page {
            header: page-header;
            footer: page-footer;
        }

        @media print {
            * {
                font-size: 11px;
                line-height: 20px;
            }

            .hidden-print {
                display: none !important;
            }

            tbody::after {
                content: '';
                display: block;
            }
        }
    </style>
</head>

<body>
    <div>
        <htmlpageheader name="page-header">
            <div class="centered">
                <h2 style="margin-bottom: 5px;font-size: 16px;">{{ settings()->company_name }}</h2>
                <p>
                    {{ settings()->company_phone }} <br>
                    {{ settings()->company_address }} <br>
                    {{ __('Date') }}: {{ format_date($sale->date) }}<br>
                    {{ __('Reference') }}: {{ $sale->reference }}<br>
                    {{ __('Name') }}: {{ $sale->customer->name }}
                </p>
            </div>
        </htmlpageheader>
        <div id="table">
            <table>
                <thead>
                    <tr class="title">
                        <th colspan="2" style="text-align: left;">{{ __('Product information') }}</th>
                        <th colspan="2" style="text-align: right;">{{ __('Qty') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->saleDetails as $saleDetail)
                        <tr>
                            <td colspan="2" style="text-align: left;">
                                {{ $saleDetail->product->name }} <br>
                                <small><strong>{{ format_currency($saleDetail->price) }}</strong></small>
                            </td>
                            <td colspan="2" style="text-align: right;">
                                {{ $saleDetail->quantity }}
                            </td>
                        </tr>
                    @endforeach

                    @if (settings()->show_order_tax == true)
                        <tr>
                            <th colspan="3" style="text-align:left">{{ __('Tax') }}
                                ({{ format_percentage($sale->tax_percentage) }})
                            </th>
                            <th style="text-align:right">{{ format_currency($sale->tax_amount) }}</th>
                        </tr>
                    @endif
                    @if (settings()->show_discount == true)
                        <tr>
                            <th colspan="3" style="text-align:left">{{ __('Discount') }}
                                ({{ $sale->discount_percentage }}%)</th>
                            <th style="text-align:right">{{ format_currency($sale->discount_amount) }}</th>
                        </tr>
                    @endif
                    @if (settings()->show_shipping == true)
                        <tr>
                            <th colspan="3" style="text-align:left">{{ __('Shipping') }}</th>
                            <th style="text-align:right">{{ format_currency($sale->shipping_amount) }}</th>
                        </tr>
                    @endif
                    <tr>
                        <th colspan="3" style="text-align:left">{{ __('Grand Total') }}</th>
                        <th style="text-align:right">{{ format_currency($sale->total_amount) }}</th>
                    </tr>
                </tbody>
            </table>

            <div class="centered" style="background-color:#ddd;padding: 5px;">
                {{ __('Paid By') }}: {{ $sale->payment_method }} <br>

                {{ __('Amount') }}: {{ format_currency($sale->paid_amount) }}
            </div>

        </div>
    </div>
</body>

</html>
