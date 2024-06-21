<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf_token" value="{{ csrf_token() }}"/>

    <title>
        @yield('title') || {{ \App\Helpers::settings('site_title') }}
    </title>
    <!-- Styles -->

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/' . Helpers::settings('site_favicon')) }}" type="image/x-icon">

    @vite('resources/css/app.css')

    @livewireStyles

    @stack('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />

    <!-- Scripts -->
    @vite('resources/js/app.js')
    @livewireScriptConfig

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

    <x-livewire-alert::scripts />

    @stack('scripts')
</head>

<body class="antialiased bg-gray-50 text-body font-body" x-data="mainState"
    :class="{ rtl: isRtl }">

    <x-loading-mask />

    <x-vendor-bar />

    <main class="pt-5 flex-1">
        @yield('content')
        @isset($slot)
            {{ $slot }}
        @endisset
    </main>

    <!-- Footer -->
    <x-copyright />

</body>

</html>
