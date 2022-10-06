@extends('layouts.app')

@section('title', 'Currencies')

@section('breadcrumb')

    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Currencies') }}</li>
    </ol>
@endsection

@section('content')
    <div class="card bg-white dark:bg-dark-eval-1">
        <div class="p-6 rounded-t rounded-r mb-0 border-b border-blueGray-200">
            <a href="{{ route('currencies.create') }}"
                class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">
                {{ __('Add Currency') }} <i class="bi bi-plus"></i>
            </a>

            <hr>

            <div class="p-4">
                <livwire:currency.index />
            </div>
        </div>
    </div>
@endsection
