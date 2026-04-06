@extends('layouts.print')

@section('title', __('Sale Invoice'))

@section('header_right')
    <div class="text-sm text-gray-600 mt-4 text-right">
        <p><span class="font-semibold">{{ __('Invoice No') }}:</span> {{ $sale->reference }}</p>
        <p><span class="font-semibold">{{ __('Date') }}:</span> {{ format_date($sale->date) }}</p>
        <p class="mt-2">
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $sale->status->getBadgeType() }}-100 text-{{ $sale->status->getBadgeType() }}-800">{{ $sale->status->getName() }}</span>
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $sale->payment_status->getBadgeType() }}-100 text-{{ $sale->payment_status->getBadgeType() }}-800">{{ $sale->payment_status->getName() }}</span>
        </p>
    </div>
@endsection

@section('content')
    <div class="mb-8">
        <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">{{ __('Billed To') }}</h3>
        <p class="font-semibold text-gray-900">{{ $customer->name }}</p>
        <p class="text-sm text-gray-600">{{ $customer->address }}</p>
        <p class="text-sm text-gray-600">{{ $customer->phone }}</p>
        <p class="text-sm text-gray-600">{{ $customer->email }}</p>
        <p class="text-sm text-gray-600 mt-1"><span class="font-semibold">{{ __('Tax Number') }}:</span> {{ $customer->tax_number ?? 'N/A' }}</p>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-900 uppercase tracking-wider">{{ __('Product') }}</th>
                    <th scope="col" class="px-4 py-3 text-center font-semibold text-gray-900 uppercase tracking-wider">{{ __('Quantity') }}</th>
                    <th scope="col" class="px-4 py-3 text-right font-semibold text-gray-900 uppercase tracking-wider">{{ __('Unit Price') }}</th>
                    <th scope="col" class="px-4 py-3 text-right font-semibold text-gray-900 uppercase tracking-wider">{{ __('Discount') }}</th>
                    <th scope="col" class="px-4 py-3 text-right font-semibold text-gray-900 uppercase tracking-wider">{{ __('Tax') }}</th>
                    <th scope="col" class="px-4 py-3 text-right font-semibold text-gray-900 uppercase tracking-wider">{{ __('SubTotal') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($sale->saleDetails as $item)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-900">{{ $item->product->name }} <span class="text-xs text-gray-500 block">{{ $item->product->code }}</span></td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-gray-700">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-gray-700">{{ format_currency($item->unit_price) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-gray-700">{{ format_currency($item->discount) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-gray-700">{{ format_currency($item->tax) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right font-medium text-gray-900">{{ format_currency($item->sub_total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <div class="w-full md:w-1/2 lg:w-1/3 space-y-3 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>{{ __('Order Tax') }}</span>
                <span>{{ format_currency($sale->tax_amount) }} ({{ $sale->tax_percentage }}%)</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>{{ __('Discount') }}</span>
                <span>{{ format_currency($sale->discount_amount) }} ({{ $sale->discount_percentage }}%)</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>{{ __('Shipping') }}</span>
                <span>{{ format_currency($sale->shipping_amount) }}</span>
            </div>
            <div class="flex justify-between font-bold text-lg text-gray-900 border-t pt-3 mt-3">
                <span>{{ __('Grand Total') }}</span>
                <span>{{ format_currency($sale->total_amount) }}</span>
            </div>
            <div class="flex justify-between text-gray-600 mt-2">
                <span>{{ __('Paid') }}</span>
                <span>{{ format_currency($sale->paid_amount) }}</span>
            </div>
            <div class="flex justify-between font-semibold text-red-600">
                <span>{{ __('Due') }}</span>
                <span>{{ format_currency($sale->due_amount) }}</span>
            </div>
        </div>
    </div>

    @if($sale->note)
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h4 class="font-semibold text-gray-800 mb-2">{{ __('Note') }}:</h4>
            <p class="text-sm text-gray-600 italic">{{ $sale->note }}</p>
        </div>
    @endif
@endsection