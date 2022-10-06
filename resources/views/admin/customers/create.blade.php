@extends('layouts.app')

@section('title', 'Create Customer')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">{{ __('Customers') }}</a></li>
        <li class="breadcrumb-item active">{{__('Add')}}</li>
    </ol>
@endsection

@section('content')
    <div class="container px-4 mx-auto">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="w-full px-4">
                    @include('utils.alerts')
                    <div class="mb-4">
                        <button
                            class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">{{ __('Create Customer') }}
                            <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="w-full px-4">
                    <x-card>
                        <div class="p-4">
                            <div class="flex flex-wrap -mx-1">
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="customer_name">{{ __('Customer Name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="customer_name" required>
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="customer_email">{{ __('Email') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="email"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="customer_email" required>
                                    </div>
                                </div>

                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="customer_phone">{{ __('Phone') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="customer_phone" required>
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="city">{{ __('City') }} <span class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="city" required>
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="country">{{ __('Country') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="country" required>
                                    </div>
                                </div>
                            
                                <div class="w-full px-4">
                                    <div class="mb-4">
                                        <label for="address">{{ __('Address') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="address" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </form>
    </div>
@endsection
