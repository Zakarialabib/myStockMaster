@extends('layouts.app')

@section('title', 'Product Details')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{__('Products')}}</a></li>
        <li class="breadcrumb-item active">{{__('Details')}}</li>
    </ol>
@endsection

@section('content')
    <div class="px-4 mx-auto mb-4">
        <div class="row mb-3">
            <div class="w-full px-4">
                {!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG(
                    $product->code,
                    $product->barcode_symbology,
                    2,
                    110,
                ) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9">
                <div class="card h-100">
                    <div class="p-4">
                        <x-table>
                            <x-table.tr>
                                <x-table.th>{{__('Product Code')}}</x-table.th>
                                <x-table.td>{{ $product->code }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Barcode Symbology')}}</x-table.th>
                                <x-table.td>{{ $product->barcode_symbology }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Name')}}</x-table.th>
                                <x-table.td>{{ $product->name }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Category')}}</x-table.th>
                                <x-table.td>{{ $product->category->name }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Cost')}}</x-table.th>
                                <x-table.td>{{ format_currency($product->cost) }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Price')}}</x-table.th>
                                <x-table.td>{{ format_currency($product->price) }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Quantity')}}</x-table.th>
                                <x-table.td>{{ $product->quantity . ' ' . $product->unit }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Stock Worth')}}</x-table.th>
                                <x-table.td>
                                    {{__('COST')}}:: {{ format_currency($product->cost * $product->quantity) }} /
                                    {{__('PRICE')}}:: {{ format_currency($product->price * $product->quantity) }}
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Alert Quantity')}}</x-table.th>
                                <x-table.td>{{ $product->stock_alert }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Tax (%)')}}</x-table.th>
                                <x-table.td>{{ $product->order_tax ?? 'N/A' }}</x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Tax Type')}}</x-table.th>
                                <x-table.td>
                                    @if ($product->tax_type == 1)
                                        {{__('Exclusive')}}
                                    @elseif($product->tax_type == 2)
                                        {{__('Inclusive')}}
                                    @else
                                        {{__('N/A')}}
                                    @endif
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>{{__('Note')}}</x-table.th>
                                <x-table.td>{{ $product->note ?? 'N/A' }}</x-table.td>
                            </x-table.tr>
                        </x-table>

                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card h-100">
                    <div class="p-4">
                        @forelse($product->getMedia('images') as $media)
                            <img src="{{ $media->getUrl() }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                        @empty
                            <img src="{{ $product->getFirstMediaUrl('images') }}" alt="Product Image"
                                class="img-fluid img-thumbnail mb-2">
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
