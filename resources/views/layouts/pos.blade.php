<!DOCTYPE html>
<html x-data="mainState" :class="{ rtl: isRtl }" class="scroll-smooth"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

    @vite('resources/css/app.css')
    @include('includes.main-css')

    @livewireStyles

    @stack('styles')
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    <!-- Scripts -->
    @include('includes.main-js')
    <!-- Scripts -->
    @vite('resources/js/app.js')

    @livewireScriptConfig
</head>

<body class="antialiased bg-gray-50 text-body font-body" dir="ltr">
    <div x-data="mainState" @resize.window="handleWindowResize" x-cloak>
        <div class="min-h-screen">

            <x-navbar-pos />

            <main class="pt-2 flex-1">
                @yield('content')
                <x-card>
                    @isset($slot)
                        {{ $slot }}
                    @endisset
                </x-card>
            </main>

            <livewire:sales.recent />

            <livewire:products.create />

            <livewire:customers.create />

            <x-settings-bar />

        </div>
    </div>


</body>

</html>
