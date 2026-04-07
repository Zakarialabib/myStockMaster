<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $data->reference ?? 'Preview' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @php
        $templateStyles = settings('template_styles', []);
        $primaryColor = $templateStyles['primary_color'] ?? '#2563eb';
        $secondaryColor = $templateStyles['secondary_color'] ?? '#eff6ff';
        $fontFamily = $templateStyles['font_family'] ?? 'sans-serif';
    @endphp
    <style>
        :root {
            --theme-primary: {{ $primaryColor }};
            --theme-secondary: {{ $secondaryColor }};
            --theme-font: {{ $fontFamily }};
        }
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        theme: {
                            primary: 'var(--theme-primary)',
                            secondary: 'var(--theme-secondary)',
                        }
                    },
                    fontFamily: {
                        sans: ['var(--theme-font)', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen py-10 px-4">
    <div class="no-print max-w-4xl mx-auto flex justify-end mb-4 space-x-4">
        <button onclick="window.print()" class="px-4 py-2 bg-theme-primary text-white rounded hover:opacity-90 transition">Print Invoice</button>
        <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">Back</a>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-10 shadow-lg rounded-lg border border-gray-200">
        <!-- Header Section -->
        @if(settings('invoice_header'))
        <div class="mb-8">
            <img src="{{ asset('storage/settings/' . settings('invoice_header')) }}" alt="Invoice Header" class="w-full h-auto rounded-t-lg">
        </div>
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-theme-primary uppercase tracking-wider">{{ $entity ?? 'INVOICE' }}</h1>
        </div>
        @else
        <div class="flex justify-between items-center border-b-4 border-theme-primary pb-8 mb-8">
            <div class="w-1/2">
                @if(settings('site_logo'))
                    <img src="{{ asset('images/' . settings('site_logo')) }}" alt="Company Logo" class="h-20 object-contain mb-4">
                @endif
                <h1 class="text-4xl font-extrabold text-theme-primary uppercase tracking-wider">{{ $entity ?? 'INVOICE' }}</h1>
            </div>
            <div class="w-1/2 text-right">
                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ settings('company_name') }}</h2>
                @if(checkInvoiceControl('show_address'))
                    <p class="text-sm text-gray-500 whitespace-pre-line leading-relaxed">{{ settings('company_address') }}</p>
                @endif
                @if(checkInvoiceControl('show_email'))
                    <p class="text-sm text-theme-primary opacity-80 mt-1 font-medium">{{ settings('company_email') }}</p>
                @endif
                @if(settings('company_tax'))
                    <p class="text-sm text-gray-500 mt-1">Tax ID: <span class="font-medium text-gray-700">{{ settings('company_tax') }}</span></p>
                @endif
            </div>
        </div>
        @endif

        <!-- Meta Information -->
        <div class="flex justify-between bg-theme-secondary p-6 rounded-lg mb-8">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Invoice Number</p>
                <p class="text-lg font-bold text-gray-900">{{ $data->reference ?? 'REF-XXXX' }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Date of Issue</p>
                <p class="text-lg font-bold text-gray-900">{{ isset($data->date) ? \Carbon\Carbon::parse($data->date)->format(settings('default_date_format', 'F j, Y')) : date('F j, Y') }}</p>
            </div>
        </div>

        <!-- Billing Details -->
        <div class="mb-10 px-2">
            <h3 class="text-xs text-gray-500 uppercase tracking-widest font-bold border-b border-gray-200 pb-2 mb-4">Billed To</h3>
            <p class="text-xl font-bold text-gray-800 mb-1">{{ $data->customer->name ?? $data->supplier->name ?? 'Client Name' }}</p>
            @if(checkInvoiceControl('show_address') && isset($data->customer->address))
                <p class="text-sm text-gray-600 w-2/3 leading-relaxed">{{ $data->customer->address }}</p>
            @endif
            @if(checkInvoiceControl('show_email') && isset($data->customer->email))
                <p class="text-sm text-theme-primary opacity-80 font-medium mt-2">{{ $data->customer->email }}</p>
            @endif
            @if(isset($data->customer->tax_number))
                <p class="text-sm text-gray-600 mt-1">Tax ID: {{ $data->customer->tax_number }}</p>
            @endif
        </div>

        <!-- Invoice Table -->
        <div class="overflow-hidden rounded-lg border border-gray-200 mb-8">
            <table class="w-full text-left bg-white">
                <thead class="bg-theme-primary text-white">
                    <tr>
                        <th class="py-4 px-6 font-semibold text-sm tracking-wide">Item Description</th>
                        <th class="py-4 px-6 font-semibold text-sm tracking-wide text-center">Qty</th>
                        <th class="py-4 px-6 font-semibold text-sm tracking-wide text-right">Price</th>
                        @if(checkInvoiceControl('show_discount'))
                            <th class="py-4 px-6 font-semibold text-sm tracking-wide text-right">Discount</th>
                        @endif
                        @if(checkInvoiceControl('show_order_tax'))
                            <th class="py-4 px-6 font-semibold text-sm tracking-wide text-right">Tax</th>
                        @endif
                        <th class="py-4 px-6 font-semibold text-sm tracking-wide text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($data->details ?? [] as $detail)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $detail->product->name ?? 'Product Name' }}</td>
                        <td class="py-4 px-6 text-sm text-gray-600 text-center">{{ $detail->quantity ?? 1 }}</td>
                        <td class="py-4 px-6 text-sm text-gray-600 text-right">{{ format_currency($detail->unit_price ?? 0) }}</td>
                        @if(checkInvoiceControl('show_discount'))
                            <td class="py-4 px-6 text-sm text-gray-500 text-right">{{ format_currency($detail->discount ?? 0) }}</td>
                        @endif
                        @if(checkInvoiceControl('show_order_tax'))
                            <td class="py-4 px-6 text-sm text-gray-500 text-right">{{ format_currency($detail->tax_amount ?? 0) }}</td>
                        @endif
                        <td class="py-4 px-6 text-sm font-bold text-gray-900 text-right">{{ format_currency($detail->sub_total ?? 0) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 px-6 text-center text-gray-400 italic">No line items found for this invoice.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Totals Section -->
        <div class="flex justify-end mb-12">
            <div class="w-full md:w-2/5 bg-gray-50 p-6 rounded-lg border border-gray-100">
                <div class="space-y-3 mb-4">
                    @if(checkInvoiceControl('show_order_tax'))
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Order Tax</span>
                            <span class="font-semibold text-gray-800">{{ format_currency($data->tax_amount ?? 0) }}</span>
                        </div>
                    @endif
                    @if(checkInvoiceControl('show_discount'))
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Discount</span>
                            <span class="font-semibold text-red-500">-{{ format_currency($data->discount_amount ?? $data->discount ?? 0) }}</span>
                        </div>
                    @endif
                    @if(checkInvoiceControl('show_shipping'))
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Shipping</span>
                            <span class="font-semibold text-gray-800">{{ format_currency($data->shipping_amount ?? 0) }}</span>
                        </div>
                    @endif
                </div>
                
                <div class="flex justify-between items-center border-t border-gray-300 pt-4 mb-4">
                    <span class="text-lg font-bold text-gray-900">Total Amount</span>
                    <span class="text-2xl font-black text-theme-primary">{{ format_currency($data->total_amount ?? 0) }}</span>
                </div>

                @if(isset($data->paid_amount))
                <div class="space-y-2 border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Amount Paid</span>
                        <span class="font-semibold text-green-600">{{ format_currency($data->paid_amount) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-bold">Balance Due</span>
                        <span class="font-bold text-red-600">{{ format_currency($data->due_amount ?? ($data->total_amount - $data->paid_amount)) }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer Notes -->
        @if(settings('invoice_footer'))
        <div class="mt-8">
            <img src="{{ asset('storage/settings/' . settings('invoice_footer')) }}" alt="Invoice Footer" class="w-full h-auto rounded-b-lg">
        </div>
        @elseif(settings('invoice_footer_text'))
        <div class="border-t border-gray-200 pt-8 mt-8 text-center">
            <p class="text-sm text-gray-500 italic">{{ settings('invoice_footer_text') }}</p>
            <p class="text-xs text-gray-400 mt-2">Thank you for your business!</p>
        </div>
        @endif
    </div>
</body>
</html>