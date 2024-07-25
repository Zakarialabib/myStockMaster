@extends('layouts.app')

@section('title', 'Sale Payments')



@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">Sale Returns</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sales.show', $sale_return) }}">{{ $sale_return->reference }}</a></li>
        <li class="breadcrumb-item active">{{__('Payments')}}</li>
    </ol>
@endsection

@section('content')
    <div class="px-4 mx-auto">
        <div class="row">
            <div class="w-full px-4">
        
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

