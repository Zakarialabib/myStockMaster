@extends('layouts.app')

@section('title', __('Create Payment'))

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchase</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->reference }}</a></li>
        <li class="breadcrumb-item active">Add Payment</li>
    </ol>
@endsection

@section('content')
   
@endsection

