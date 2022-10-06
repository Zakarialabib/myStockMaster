@extends('layouts.app')

    @section('title', 'Print Barcode')

    @section('breadcrumb')
        <ol class="breadcrumb border-0 m-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Print Barcode') }}</li>
        </ol>
    @endsection
@section('content')
    <div class="container px-4 mx-auto">
        <div class="row">
            <div class="col-12">
                <livewire:search-product />
            </div>
        </div>

        <div class="row mt-4">
            <div class="w-full px-4">
                <livewire:products.barcode />
            </div>
        </div>
    </div>
@endsection
