@extends('layouts.app')

@section('title', __('Create Sale From Quotation'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">{{ __('Quotations') }}</a></li>
        <li class="breadcrumb-item active">Make Sale</li>
    </ol>
@endsection

@section('content')
    <div class="px-4 mx-auto mb-4">
        <div class="row">
            <div class="col-12">
                <livewire:search-product />
            </div>
        </div>

        <div class="row mt-4">
            <div class="w-full px-4">
                <div class="card">
                    <div class="p-4">
                        @include('utils.alerts')
                        <form id="sale-form" action="{{ route('sales.store') }}" method="POST">
                            @csrf

                            <div class="flex flex-wrap -mx-1">
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="reference">{{ __('Reference') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                            name="reference" required readonly value="SL">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">

                                    <label for="customer_id">{{ __('Customer') }} <span
                                            class="text-red-500">*</span></label>
                                    <select
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                        name="customer_id" id="customer_id" required>
                                        @foreach (\App\Models\Customer::all() as $customer)
                                            <option {{ $sale->customer_id == $customer->id ? 'selected' : '' }}
                                                value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <label for="date">{{ __('Date') }} <span class="text-red-500">*</span></label>
                                    <input type="date"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                        name="date" required value="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>

                            <livewire:product-cart :cartInstance="'sale'" :data="$sale" />

                            <div class="flex flex-wrap -mx-1">
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="status">{{ __('Status') }} <span
                                                class="text-red-500">*</span></label>
                                        <select
                                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                            name="status" id="status" required>
                                            <option value="Pending">{{ __('Pending') }}</option>
                                            <option value="Shipped">{{__('Shipped')}}</option>
                                            <option value="Completed">{{ __('Completed') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="from-group">
                                        <div class="mb-4">
                                            <label for="payment_method">{{__('Payment Method')}} <span
                                                    class="text-red-500">*</span></label>
                                            <select
                                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                                name="payment_method" id="payment_method" required>
                                                <option value="Cash">Cash</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="paid_amount">{{__('Amount Received')}} <span class="text-red-500">*</span></label>
                                        <div class="input-group">
                                            <input id="paid_amount" type="text"
                                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                                name="paid_amount" required>
                                            <div class="input-group-append">
                                                <button id="getTotalAmount"
                                                    class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"
                                                    type="button">
                                                    <i class="bi bi-check-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="note">{{__('Note (If Needed)')}}</label>
                                <textarea name="note" id="note" rows="5"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                            </div>

                            <input type="hidden" name="quotation_id" value="{{ $quotation_id }}">

                            <div class="mt-3">
                                <button type="submit"
                                    class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">
                                    {{__('Create Sale')}} <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#paid_amount').maskMoney({
                prefix: '{{ settings()->currency->symbol }}',
                thousands: '{{ settings()->currency->thousand_separator }}',
                decimal: '{{ settings()->currency->decimal_separator }}',
                allowZero: true,
            });

            $('#getTotalAmount').click(function() {
                $('#paid_amount').maskMoney('mask', {{ Cart::instance('sale')->total() }});
            });

            $('#sale-form').submit(function() {
                var paid_amount = $('#paid_amount').maskMoney('unmasked')[0];
                $('#paid_amount').val(paid_amount);
            });
        });
    </script>
@endpush
