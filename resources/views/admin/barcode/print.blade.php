<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Barcodes') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($barcodes as $barcode)
                <div class="col-xs-3" style="border: 1px solid #dddddd;border-style: dashed;">
                    <p style="font-size: 15px;color: #000;margin-top: 15px;margin-bottom: 5px;">
                        {{ $name }}
                    </p>
                    {{-- read $barcode as svg image --}}
                    <img src="data:image/svg+xml;base64,{{ base64_encode($barcode) }}" alt="barcode" />

                    <p style="font-size: 15px;color: #000;font-weight: bold;">
                        {{ __('Price') }}:: {{ format_currency($price) }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
