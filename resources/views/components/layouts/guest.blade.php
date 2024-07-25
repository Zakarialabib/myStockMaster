<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="dns-prefetch" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="preconnect" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="prefetch" href="{{ request()->getSchemeAndHttpHost() }}">
    <link rel="prerender" href="{{ request()->getSchemeAndHttpHost() }}">
    <meta name="csrf_token" value="{{ csrf_token() }}"/>
    <!-- Head Tags -->
    @if ( settings('head_tags') )
        {!! settings('head_tags') !!}
    @endif

    <title>
        @yield('title') || {{ settings('site_title') }}
    </title>


    @hasSection('meta')
        @yield('meta')
    @else
        <meta name="title" content="{{ settings('seo_meta_title') }}">
        <meta name="description" content="{{ settings('seo_meta_description') }}">
        <meta property="og:title" content="{{ settings('site_title') }}">
        <meta property="og:description" content="{{ settings('seo_meta_description') }}">
        <meta property="og:url" content="/" />
    @endif

    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ settings('company_name') }}" />
    <meta name="author" content="{{ settings('company_name') }}">
    {{-- <link rel="canonical" href="{{ URL::current() }}"> --}}
    <meta name="robots" content="all,follow">

    <link rel="icon" href="{{ asset('images/' . settings('site_favicon') ) }}" type="image/x-icon">

    {{-- Styles --}}
    @vite('resources/css/app.css')

    @livewireStyles
 
    @stack('styles')
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
      @vite('resources/js/app.js')

      @livewireScriptConfig 
      
      <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      
      <x-livewire-alert::scripts />
        
      @stack('scripts')
</head>

<body class="antialiased bg-gray-100 text-body font-body" x-data="{ showCart: false }">
    <!-- Body Tags -->

    @if (settings('body_tags') )
        {!! settings('body_tags')  !!}
    @endif
    
    {{-- <x-loading-mask /> --}}

    <section class="relative">

        <x-topheader />

        <x-header vertical /> 

        {{-- <x-bottomheader /> --}}

        @yield('content')

        @isset($slot)
            {{ $slot }}
        @endisset

        <x-theme.footer />

        {{-- <x-whatsapp /> --}}

    </section>

  
</body>

</html>