@extends('layouts.app')

@section('title', 'Adjustment Details')



@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('adjustments.index') }}">{{__('Adjustments')}}</a></li>
        <li class="breadcrumb-item active">{{__('Details')}}</li>
    </ol>
@endsection

@section('content')
    <div class="container px-4 mx-auto">
        <div class="row">
            <div class="col-12">
                <x-card>
                    <div class="p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2">
                                        {{__('Date')}}
                                    </th>
                                    <th colspan="2">
                                        {{__('Reference')}}
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        {{ $adjustment->date }}
                                    </td>
                                    <td colspan="2">
                                        {{ $adjustment->reference }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>{{__('Product Name')}}</th>
                                    <th>{{__('Code')}}</th>
                                    <th>{{__('Quantity')}}</th>
                                    <th>{{__('Type')}}</th>
                                </tr>

                                @foreach($adjustment->adjustedProducts as $adjustedProduct)
                                    <tr>
                                        <td>{{ $adjustedProduct->product->product_name }}</td>
                                        <td>{{ $adjustedProduct->product->product_code }}</td>
                                        <td>{{ $adjustedProduct->quantity }}</td>
                                        <td>
                                            @if($adjustedProduct->type == 'add')
                                                (+) Addition
                                            @else
                                                (-) Subtraction
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection
