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

        .row {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc;
            padding-top: 4px;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        tr {
            border-bottom: 1px dashed #ddd;
            border-top: 1px dashed #ddd;
        }

        /* Style the footer */
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
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
            align-content: center;
        }

        .text-right {
            text-align: right;
            align-content: right;
        }
    </style>

</head>

<body>
    <div>
        @yield('content')
    </div>
</body>

</html>
