@extends('layouts.app')

@section('title', 'Purchase Payments')



@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">{{__('Purchases')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->reference }}</a></li>
        <li class="breadcrumb-item active">{{__('Payments')}}</li>
    </ol>
@endsection

@section('content')
    <div class="container px-4 mx-auto">
        <div class="row">
            <div class="col-12">
                @include('utils.alerts')
                <div class="card">
                    <div class="p-4">
                        <div class="table-responsive">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

