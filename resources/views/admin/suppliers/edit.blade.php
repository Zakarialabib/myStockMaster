@extends('layouts.app')

@section('title', 'Update Supplier')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
        <li class="breadcrumb-item active">{{__('Edit')}}</li>
    </ol>
@endsection

@section('content')
    <div class="container px-4 mx-auto">
        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('patch')
            <div class="row">
                <div class="w-full px-4">
                    @include('utils.alerts')
                    <div class="mb-4">
                        <button class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">Update Supplier <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="w-full px-4">
                    <div class="card">
                        <div class="p-4">
                            <div class="flex flex-wrap -mx-1">
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="supplier_name">Supplier Name <span class="text-red-500">*</span></label>
                                        <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="supplier_name" required value="{{ $supplier->supplier_name }}">
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="supplier_email">{{__('Email')}} <span class="text-red-500">*</span></label>
                                        <input type="email" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="supplier_email" required value="{{ $supplier->supplier_email }}">
                                    </div>
                                </div>
                            
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="supplier_phone">{{__('Phone')}} <span class="text-red-500">*</span></label>
                                        <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="supplier_phone" required value="{{ $supplier->supplier_phone }}">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="city">{{__('City')}} <span class="text-red-500">*</span></label>
                                        <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="city" required value="{{ $supplier->city }}">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="country">{{__('Country')}} <span class="text-red-500">*</span></label>
                                        <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="country" required value="{{ $supplier->country }}">
                                    </div>
                                </div>
                            
                                <div class="w-full px-4">
                                    <div class="mb-4">
                                        <label for="address">Address <span class="text-red-500">*</span></label>
                                        <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="address" required value="{{ $supplier->address }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

