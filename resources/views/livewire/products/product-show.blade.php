{{-- <div class="modal" tabindex="-1" wire:ignore.self aria-hidden="true" role="dialog"> --}}
<div class="modal-dialog">

    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ $product->name }}</h5>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        <div class="modal-body">

            <div class="container px-4 mx-auto mb-4">
                <div class="row mb-3">
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        {!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG(
                            $product->code,
                            $product->barcode_symbology,
                            2,
                            110,
                        ) !!}
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        @forelse($product->getMedia('images') as $media)
                            <img src="{{ $media->getUrl() }}" alt="Product Image" class="img-fluid img-thumbnail mb-2">
                        @empty
                            <img src="{{ $product->getFirstMediaUrl('images') }}" alt="Product Image"
                                class="img-fluid img-thumbnail mb-2">
                        @endforelse
                    </div>
                </div>
                <div class="row">
                    <div class="w-full px-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <tr>
                                    <th>{{__('Product Code')}}</th>
                                    <td>{{ $product->code }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Barcode Symbology')}}</th>
                                    <td>{{ $product->barcode_symbology }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Category')}}</th>
                                    <td>{{ $product->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Cost')}}</th>
                                    <td>{{ format_currency($product->cost) }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Price')}}</th>
                                    <td>{{ format_currency($product->price) }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Quantity')}}</th>
                                    <td>{{ $product->quantity . ' ' . $product->unit }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Stock Worth')}}</th>
                                    <td>
                                        COST::
                                        {{ format_currency($product->cost * $product->quantity) }}
                                        /
                                        PRICE::
                                        {{ format_currency($product->price * $product->quantity) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{__('Alert Quantity')}}</th>
                                    <td>{{ $product->stock_alert }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Tax (%)')}}</th>
                                    <td>{{ $product->order_tax ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Tax Type')}}</th>
                                    <td>
                                        @if ($product->tax_type == 1)
                                            Exclusive
                                        @elseif($product->tax_type == 2)
                                            Inclusive
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Note</th>
                                    <td>{{ $product->note ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>
