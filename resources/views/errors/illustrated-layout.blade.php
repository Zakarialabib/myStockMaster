<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css'])
        
    </head>
    <body class="antialiased font-sans">
        <section class="relative py-24 md:py-44 lg:py-64 bg-white" >
            <div class="relative z-10 px-4 mx-auto">
              <div class="max-w-4xl mx-auto text-center">
                <span class="inline-block py-px px-2 mb-4 text-xs leading-5 text-green-500 bg-green-100 font-medium rounded-full shadow-sm">@yield('code', __('Oh no'))</span>
                <h2 class="mb-4 text-4xl md:text-5xl leading-tight font-bold tracking-tighter">{{__('Page not found')}}</h2>
                <p class="mb-12 text-lg md:text-xl text-coolGray-500">@yield('message')</p>
                <div class="flex flex-wrap justify-center">
                  <div class="w-full md:w-auto py-1 md:py-0 md:mr-6"><a class="inline-block py-5 px-7 w-full text-base md:text-lg leading-4 text-green-50 font-medium text-center bg-green-500 hover:bg-green-600 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 border border-green-500 rounded-md shadow-sm" 
                        href="{{ app('router')->has('dashboard') ? route('dashboard') : url('/') }}">
                        {{__('Go back to Homepage')}}
                    </a></div>
                </div>
              </div>
            </div>
            <img class="absolute top-0 left-0 w-28 md:w-auto" src="{{ asset('images/elements/wave2-yellow.svg')}}" alt="">
            <img class="absolute right-6 top-6 w-28 md:w-auto" src="{{ asset('images/elements/dots3-green.svg')}}" alt="">
            <img class="absolute right-0 bottom-0 w-28 md:w-auto" src="{{ asset('images/elements/wave3-red.svg')}}" alt="">
            <img class="absolute left-6 bottom-6 w-28 md:w-auto" src="{{ asset('images/elements/dots3-violet.svg')}}" alt="">
          </section>
    </body>
</html>
