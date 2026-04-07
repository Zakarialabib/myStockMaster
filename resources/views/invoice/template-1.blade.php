<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $data->reference ?? 'Preview' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-white text-gray-900 p-8 max-w-4xl mx-auto font-sans">
    <!-- Action Buttons -->
    <div class="no-print flex justify-end mb-8 space-x-4">
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">Print</button>
        <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded shadow hover:bg-gray-300">Back</a>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-start border-b-2 border-gray-800 pb-6 mb-6">
        <div>
            @if(settings('site_logo'))
                <img src="{{ asset('images/' . settings('site_logo')) }}" alt="Logo" class="h-16 mb-2">
            @endif
            <h1 class="text-3xl font-bold uppercase tracking-widest text-gray-800">{{ $entity ?? 'INVOICE' }}</h1>
            <p class="text-sm text-gray-500 font-semibold mt-1">Reference: {{ $data->reference ?? 'REF-XXXX' }}</p>
            <p class="text-sm text-gray-500 mt-1">Date: {{ isset($data->date) ? \Carbon\Carbon::parse($data->date)->format(settings('default_date_format', 'Y-m-d')) : date('Y-m-d') }}</p>
        </div>
        <div class="text-right">
            <h2 class="font-bold text-xl text-gray-800">{{ settings('company_name') }}</h2>
            @if(checkInvoiceControl('show_email'))
                <p class="text-sm text-gray-600">{{ settings('company_email') }}</p>
            @endif
            @if(checkInvoiceControl('show_address'))
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ settings('company_address') }}</p>
            @endif
            @if(settings('company_tax'))
                <p class="text-sm text-gray-600">Tax ID: {{ settings('company_tax') }}</p>
            @endif
        </div>
    </div>

    <!-- Customer Details -->
    <div class="mb-8">
        <h3 class="font-bold border-b border-gray-300 inline-block mb-2 text-gray-700">Bill To:</h3>
        <p class="font-bold text-lg text-gray-800">{{ $data->customer->name ?? $data->supplier->name ?? 'Customer Name' }}</p>
        @if(checkInvoiceControl('show_address') && isset($data->customer->address))
            <p class="text-sm text-gray-600">{{ $data->customer->address }}</p>
        @endif
        @if(checkInvoiceControl('show_email') && isset($data->customer->email))
            <p class="text-sm text-gray-600">{{ $data->customer->email }}</p>
        @endif
        @if(isset($data->customer->tax_number))
            <p class="text-sm text-gray-600">Tax ID: {{ $data->customer->tax_number }}</p>
        @endif
    </div>

    <!-- Items Table -->
    <table class="w-full mb-8 text-left border-collapse">
        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="p-3 font-semibold text-sm rounded-tl-sm">Product</th>
                <th class="p-3 font-semibold text-sm text-center">Qty</th>
                <th class="p-3 font-semibold text-sm text-right">Unit Price</th>
                @if(checkInvoiceControl('show_discount'))
                    <th class="p-3 font-semibold text-sm text-right">Discount</th>
                @endif
                @if(checkInvoiceControl('show_order_tax'))
                    <th class="p-3 font-semibold text-sm text-right">Tax</th>
                @endif
                <th class="p-3 font-semibold text-sm text-right rounded-tr-sm">SubTotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data->details ?? [] as $detail)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="p-3 text-sm text-gray-800">{{ $detail->product->name ?? 'Item Name' }}</td>
                <td class="p-3 text-sm text-center text-gray-800">{{ $detail->quantity ?? 1 }}</td>
                <td class="p-3 text-sm text-right text-gray-800">{{ format_currency($detail->unit_price ?? 0) }}</td>
                @if(checkInvoiceControl('show_discount'))
                    <td class="p-3 text-sm text-right text-gray-600">{{ format_currency($detail->discount ?? 0) }}</td>
                @endif
                @if(checkInvoiceControl('show_order_tax'))
                    <td class="p-3 text-sm text-right text-gray-600">{{ format_currency($detail->tax_amount ?? 0) }}</td>
                @endif
                <td class="p-3 text-sm text-right font-semibold text-gray-800">{{ format_currency($detail->sub_total ?? 0) }}</td>
            </tr>
            @empty
            <tr class="border-b border-gray-200">
                <td colspan="6" class="p-3 text-sm text-center text-gray-500">No items available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary -->
    <div class="flex justify-end">
        <div class="w-full md:w-1/2 lg:w-1/3">
            @if(checkInvoiceControl('show_order_tax'))
                <div class="flex justify-between border-b border-gray-200 py-2">
                    <span class="text-gray-600">Order Tax:</span>
                    <span class="font-medium text-gray-800">{{ format_currency($data->tax_amount ?? 0) }}</span>
                </div>
            @endif
            @if(checkInvoiceControl('show_discount'))
                <div class="flex justify-between border-b border-gray-200 py-2">
                    <span class="text-gray-600">Discount:</span>
                    <span class="font-medium text-gray-800">{{ format_currency($data->discount_amount ?? $data->discount ?? 0) }}</span>
                </div>
            @endif
            @if(checkInvoiceControl('show_shipping'))
                <div class="flex justify-between border-b border-gray-200 py-2">
                    <span class="text-gray-600">Shipping:</span>
                    <span class="font-medium text-gray-800">{{ format_currency($data->shipping_amount ?? 0) }}</span>
                </div>
            @endif
            <div class="flex justify-between py-4 text-xl font-bold border-b-2 border-gray-800">
                <span class="text-gray-800">Grand Total:</span>
                <span class="text-blue-600">{{ format_currency($data->total_amount ?? 0) }}</span>
            </div>
            @if(isset($data->paid_amount))
            <div class="flex justify-between py-2 text-md">
                <span class="text-gray-600">Paid Amount:</span>
                <span class="font-medium text-green-600">{{ format_currency($data->paid_amount) }}</span>
            </div>
            <div class="flex justify-between py-2 text-md font-semibold">
                <span class="text-gray-600">Due Amount:</span>
                <span class="text-red-600">{{ format_currency($data->due_amount ?? ($data->total_amount - $data->paid_amount)) }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    @if(settings('invoice_footer_text'))
    <div class="mt-16 pt-8 border-t border-gray-300 text-center text-sm text-gray-500">
        {{ settings('invoice_footer_text') }}
    </div>
    @endif
</body>
</html>