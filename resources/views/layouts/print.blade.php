<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            @page { margin: 15mm; }
        }
        body { background: #f9fafb; font-family: 'Inter', sans-serif; color: #374151; }
        .print-container { max-width: 210mm; margin: 2rem auto; background: white; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        @media print { .print-container { box-shadow: none; margin: 0; padding: 0; max-width: 100%; } }
    </style>
</head>
<body class="antialiased">
    <div class="print-container">
        <!-- Print Header -->
        <div class="flex justify-between items-start mb-8 pb-6 border-b border-gray-200">
            <div class="w-1/2">
                @if(isset($logo) && $logo)
                    <img src="{{ $logo }}" alt="Logo" class="h-16 object-contain mb-4">
                @endif
                <h2 class="text-xl font-bold text-gray-900">{{ settings()->company_name ?? config('app.name') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ settings()->company_address }}</p>
                <p class="text-sm text-gray-500">{{ settings()->company_phone }}</p>
                <p class="text-sm text-gray-500">{{ settings()->company_email }}</p>
            </div>
            <div class="w-1/2 text-right">
                <h1 class="text-3xl font-extrabold text-gray-800 uppercase tracking-wider mb-2">@yield('title')</h1>
                @yield('header_right')
            </div>
        </div>

        <!-- Print Content -->
        @yield('content')

        <!-- Print Footer -->
        <div class="mt-12 pt-8 border-t border-gray-200 text-center text-sm text-gray-500">
            <p>{{ settings()->invoice_footer_text ?? 'Thank you for your business!' }}</p>
            <p class="mt-1">{{ settings()->company_name ?? config('app.name') }} &copy; {{ date('Y') }}</p>
        </div>
    </div>
    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        }
    </script>
</body>
</html>