<!DOCTYPE html>
<html x-data="mainState" :class="{ rtl: isRtl, dark: isDarkMode }" class="scroll-smooth"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="robots" content="nofollow">

    <title>{{ $title ?? '' }} || {{ settings()->company_name ?? config('app.name') }}</title>
    <!-- Styles -->

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <meta name="theme-color" content="#000000">
    <link rel="manifest" href="manifest.json" />
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content=" {{ settings()->company_name ?? config('app.name') }}">

    @include('includes.main-css')

    @if ($isDesktop)
    @vite('resources/css/desktop.css')
    @endif

    @stack('styles')
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    @if ($isDesktop)
    @vite('resources/js/desktop.js')
    @endif

    @stack('scripts')
    @livewireScriptConfig

</head>

<body class="antialiased bg-gray-50 text-body font-body {{ $isDesktop ? 'desktop-app' : '' }}" dir="ltr">
    <x-loading-mask />

    <div class="flex flex-col min-h-screen">
        <!-- Sidebar -->
        <x-sidebar.sidebar />

        <!-- Page Wrapper -->
        <div class="flex flex-col flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'lg:ml-64': isSidebarOpen || isSidebarHovered,
                'lg:ml-16': !isSidebarOpen && !isSidebarHovered,
            }">

            <!-- Navigation Bar-->
            <x-navbar />

            <!-- Desktop Indicators & Notifications -->
            @if ($isDesktop)
            <div class="fixed top-20 right-6 z-40 flex flex-col gap-3 pointer-events-none">
                <livewire:desktop-mode-indicator />
                <livewire:desktop-notification />
                <livewire:sync-status />
            </div>
            @endif

            <main class="flex-1 p-6 lg:p-8">
                <div class="max-w-7xl mx-auto space-y-6">
                    @yield('breadcrumb')

                    @yield('content')

                    @isset($slot)
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-soft p-6 border border-gray-100 dark:border-gray-800">
                        {{ $slot }}
                    </div>
                    @endisset

                    {{-- <x-settings-bar /> --}}

                </div>
            </main>

            <!-- Footer -->
            <x-footer />
        </div>
    </div>
</body>

</html>