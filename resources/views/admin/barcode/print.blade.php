<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Barcodes') }}</title>
    {{-- cdn --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            @foreach ($barcodes as $barcode)
                <div class="col-xs-3" style="border: 1px solid #dddddd;border-style: dashed;">
                    <p style="font-size: 15px;color: #000;margin-top: 15px;margin-bottom: 5px;">
                        {{ $name }}
                    </p>
                    <div>
                        {!! $barcode !!}
                    </div>
                    <p style="font-size: 15px;color: #000;font-weight: bold;">
                        {{ __('Price') }}:: {{ format_currency($price) }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
