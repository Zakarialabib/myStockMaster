<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="csrf_token" value="{{ csrf_token() }}" />

    <title>@yield('title') || {{ config('app.name') }}</title>
    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('includes.main-css')
    @livewireScriptConfig

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-livewire-alert::scripts />

</head>

<body class="antialiased bg-gray-50 text-body font-body" x-data="mainState"
    :class="{ rtl: isRtl }" dir="ltr">
    <div @resize.window="handleWindowResize" x-cloak>
        <div class="min-h-screen">

            <x-navbar-pos />

            <main class="pt-2 flex-1">
                @yield('content')
                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>

            <livewire:sales.recent lazy />

            <livewire:products.create lazy />

            <livewire:customers.create lazy />

            <x-settings-bar />

        </div>
    </div>
</body>

</html>
