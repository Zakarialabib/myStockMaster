@extends('layouts.app')

@section('title', 'Sales Return Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Sales Return Report</li>
    </ol>
@endsection

@section('content')
    <div class="px-4 mx-auto">
        <livewire:reports.sales-return-report :customers="\App\Models\Customer::all()"/>
    </div>
@endsection
