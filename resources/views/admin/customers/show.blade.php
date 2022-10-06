@extends('layouts.app')

@section('title', 'Customer Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">{{__('Customers')}}</a></li>
        <li class="breadcrumb-item active">{{__('Details')}}</li>
    </ol>
@endsection

@section('content')
    <div class="container px-4 mx-auto">
        <div class="row">
            <div class="w-full px-4">
                <x-card>
                    <div class="p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>{{__('Customer Name')}}</th>
                                    <td>{{ $customer->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Customer Email')}}</th>
                                    <td>{{ $customer->customer_email }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Customer Phone')}}</th>
                                    <td>{{ $customer->customer_phone }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('City')}}</th>
                                    <td>{{ $customer->city }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Country')}}</th>
                                    <td>{{ $customer->country }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Address')}}</th>
                                    <td>{{ $customer->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection

