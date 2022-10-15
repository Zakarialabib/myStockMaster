<!DOCTYPE html>
<html x-data="mainState" :class="{ dark: isDarkMode }" class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <met@endforelsea content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') || {{ config('app.name') }}</title>
    <!-- Styles -->
   
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    @include('includes.main-css')
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('includes.main-js')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-700 dark:bg-slate-900 dark:text-slate-300 selection:bg-brand-200 dark:selection:text-slate-800 text-sm sm:text-base">
    <x-loading-mask />
    <div @resize.window="handleWindowResize">
        <div class="min-h-screen text-zinc-500 bg-slate-200 dark:bg-dark-bg dark:text-zinc-200">
            <!-- Sidebar -->
            <x-sidebar.sidebar />
            <!-- Page Wrapper -->
            <div class="flex flex-col min-h-screen pl-7"
                :class="{
                    'lg:ml-64': isSidebarOpen,
                    // 'md:ml-16': !isSidebarOpen,
                }"
                style="transition-property: margin; transition-duration: 150ms;">

                <!-- Navigation Bar-->
                <x-navbar />

                <main class="pt-5 pl-10 pr-5 sm:pl-5 sm:pr-3 flex-1">
                    @yield('breadcrumb')
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>

</html>
