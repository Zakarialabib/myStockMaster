<!DOCTYPE html>
<html x-data="mainState" :class="{ rtl: isRtl }" class="scroll-smooth"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="csrf_token" value="{{ csrf_token() }}" />
    <meta name="csrf_token" value="{{ csrf_token() }}" />
    <meta name="robots" content="nofollow">

    <title>@yield('title') || {{ settings()->company_name }}</title>
    <!-- Styles -->

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <meta name="theme-color" content="#000000">
    <link rel="manifest" href="manifest.json" />
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ settings()->company_name }}">

    @include('includes.main-css')
    @vite('resources/css/app.css')

    @livewireStyles

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />

    @stack('styles')
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    @vite('resources/js/app.js')

    @livewireScriptConfig

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

    <x-livewire-alert::scripts />

    @stack('scripts')
</head>

<body class="antialiased bg-gray-50 text-body font-body" dir="ltr">
    <x-loading-mask />
    <div @resize.window="handleWindowResize">
        <div class="min-h-screen">
            <!-- Sidebar -->
            <x-sidebar.sidebar />
            <!-- Page Wrapper -->
            <div class="flex flex-col min-h-screen"
                :class="{
                    'lg:ml-64': isSidebarOpen,
                    'lg:ml-16': !isSidebarOpen,
                }"
                style="transition-property: margin; transition-duration: 150ms;">

                <!-- Navigation Bar-->
                <x-navbar />

                <main class="flex-1">

                    @yield('breadcrumb')

                    @yield('content')

                    <x-card>
                        @isset($slot)
                            {{ $slot }}
                        @endisset
                    </x-card>
                    <x-settings-bar />

                </main>

                <!-- Footer -->
                <x-footer />

            </div>
        </div>
    </div>
</body>

</html>
