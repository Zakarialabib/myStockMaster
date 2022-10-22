<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') || {{ config('app.name') }}</title>
    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    @include('includes.main-css')
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-body text-body font-body">
    <div x-data="mainState" :class="{ dark: isDarkMode }" @resize.window="handleWindowResize" x-cloak>
        <div class="min-h-screen">
            <x-navbar-pos />
            <main class="pt-5 flex-1">
                @yield('content')
                @isset($slot)
                {{ $slot }}
                @endisset
            </main>
            
            <livewire:sales.recent />

            <livewire:products.create />
            
            <livewire:customers.create />
            
        </div>
    </div>
    @include('includes.main-js')
</body>

</html>
