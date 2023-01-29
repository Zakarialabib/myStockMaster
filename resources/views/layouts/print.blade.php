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
        
        *{ font-family: 'Cairo'!important;}

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

        .col-12 {
            width: 100%;
            padding: 5px 0;
        }

        .row {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .text-center {
            text-align: center;
            align-content: center;
        }
    </style>

</head>

<body>
    <main>
        @yield('content')
    </main>
</body>

</html>
