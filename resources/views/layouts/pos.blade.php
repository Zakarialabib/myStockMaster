<!DOCTYPE html>
<html x-data="mainState" :class="{ rtl: isRtl }" class="scroll-smooth"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    :dir="isRtl ? 'rtl' : 'ltr'">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="csrf_token" value="{{ csrf_token() }}" />

    <title>{{ $title ?? '' }} ||  {{ settings()->company_name ?? config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

    @vite('resources/css/app.css')

    @livewireStyles

    @stack('styles')

    <style>
        [x-cloak] {
            display: none;
        }
        
        /* RTL Support */
        [dir="rtl"] .rtl-flip {
            transform: scaleX(-1);
        }
        
        [dir="rtl"] .rtl-space-x-reverse > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }
        
        /* Accessibility: Focus styles */
        *:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .border-gray-200, .border-gray-300, .border-gray-600, .border-gray-700 {
                border-color: currentColor;
            }
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    
    <!-- Scripts -->
    @vite('resources/js/app.js')

    @livewireScriptConfig
</head>

<body class="antialiased bg-gray-50 text-gray-900 font-sans dark:bg-gray-900 dark:text-gray-100" 
      :dir="isRtl ? 'rtl' : 'ltr'"
      x-bind:class="{ 'rtl-layout': isRtl }">
    <div x-data="mainState" @resize.window="handleWindowResize" x-cloak>
        <div class="min-h-screen">

            <x-navbar-pos />

            <main class="pt-2 flex-1" role="main" aria-label="{{ __('Main content') }}">
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
