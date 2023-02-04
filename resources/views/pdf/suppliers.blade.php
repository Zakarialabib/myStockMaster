<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Suppliers') }}</title>

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

        .container{
            max-width: 800px;
            margin: 0 auto;
        }
   
        .row {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .col-12 {
            width: 100%;
            padding: 5px 0;
        }
        .text-center {
            text-align: center;
            align-content: center;
        }
        .table {
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
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('City') }}</th>
                            <th>{{ __('Country') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->phone }}</td>
                                <td>{{ $row->city }}</td>
                                <td>{{ $row->country }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer">
            <strong>{{ settings()->company_name }}</strong><br>
            @if (settings()->show_address == true)
                {{ settings()->company_address }}<br>
            @endif
            @if (settings()->show_email == true)
                {{ __('Email') }}: {{ settings()->company_email }}<br>
            @endif
            {{ __('Phone') }}:<br> {{ settings()->company_phone }}<br>
        </div>
    </div>
</body>

</html>
