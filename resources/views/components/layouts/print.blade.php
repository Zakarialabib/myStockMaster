<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') || {{ config('app.name') }}</title>

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
            padding: 10px 25px 0px 25px;
            background: #ffffff;
            font-size: 13px;
            line-height: 15px;
            position: relative;
            width: 80%;
            height: 100%;
            -webkit-font-smoothing: antialiased;
            background-size: cover;
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
            margin-left: 0 !important;
            font-size: 11px;
            line-height: 13px;
        }

        .row {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            padding-top: 4px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        thead th {
            background-color: #2980b9;
            color: #ffffff;
            text-align: left;
            border: none;
            padding: 8px;
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        td {
            border: none;
            padding: 8px;
            text-align: left;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        tr {
            border: none;
        }

        .col {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }

        .col-12 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        .col-6 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }

        .text-center {
            text-align: center;
            margin: 0 10px;
            align-content: center;
        }

        .text-right {
            text-align: right;
            align-content: right;
        }

        @page {
            header: myheader;
            footer: myfooter;
        }
    </style>

</head>

<body>


    <div class="pageStyle">
        @yield('content')
    </div>
    
    <!--mpdf 
    <htmlpageheader name="myheader">
        @if(settings('invoice_header'))
        {!! File::get(public_path('print/invoice-header.html')) !!}
        @endif
    </htmlpageheader>

    <htmlpagefooter name="myfooter">
        <div style="text-align:center; font-size:10pt;">
            {{ settings('company_name') }} &copy;
            {{ date('Y') }} - {{ __('Page') }} {PAGENO} {{ __('of') }} {nbpg}
            @if(settings('invoice_footer'))
            {{-- {!! File::get(public_path('print/invoice-footer.html')) !!} --}}
            @endif
        </div>
    </htmlpagefooter>

    <sethtmlpageheader name="myheader" show-this-page="1" />
    <sethtmlpagefooter name="myfooter" page="O" value="on" show-this-page="1" />
    mpdf-->

</body>

</html>
