<!DOCTYPE html>
<html x-data="mainState" :class="{ rtl: isRtl }" class="scroll-smooth"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="csrf_token" value="{{ csrf_token() }}"/>
    <meta name="robots" content="nofollow">

    <title>{{ $title ?? '' }} ||  {{ settings()->company_name ?? config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <meta name="theme-color" content="#000000">
    <link rel="manifest" href="manifest.json" />
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content=" {{ settings()->company_name ?? config('app.name') }}">

    @vite('resources/css/app.css')

    @livewireStyles

    @stack('styles')
    @php
        $appStyle = settings('app_style', []);
        $primaryColor = $appStyle['primary_color'] ?? '#0061ff';
        $palette = generate_color_palette($primaryColor);
    @endphp
    <style>
        :root {
            @foreach($palette as $weight => $hex)
            --color-primary-{{ $weight }}: {{ $hex }};
            @endforeach
            --font-sans: {{ $appStyle['font_family'] ?? "'Inter', sans-serif" }};
            --font-body: {{ $appStyle['font_family'] ?? "'Inter', sans-serif" }};
        }
        [x-cloak] {
            display: none;
        }
    </style>

<!-- Scripts -->
    @vite('resources/js/app.js')

    @livewireScriptConfig
</head>

<body class="font-sans text-gray-900 antialiased">
    <x-auth-card>
        <x-slot:logo>
            <a href="/" wire:navigate>
                <x-application-logo class="h-20 text-gray-500" />
            </a>
        </x-slot:logo>

        {{ $slot }}
    </x-auth-card>
</body>

</html>
