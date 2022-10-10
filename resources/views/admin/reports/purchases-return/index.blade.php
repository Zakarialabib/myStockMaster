@extends('layouts.app')

@section('title', 'Purchases Return Report')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Purchases Return Report</li>
    </ol>
@endsection

@section('content')
    <div class="px-4 mx-auto">
        <livewire:reports.purchases-return-report :suppliers="\App\Models\Supplier::all()"/>
    </div>
@endsection
