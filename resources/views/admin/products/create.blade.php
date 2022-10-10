@extends('layouts.app')

@section('title', 'Create Product')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Add') }}</li>
    </ol>
@endsection

@section('content')
    <div class="px-4 mx-auto">
        <form id="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="w-full px-4">
                    @include('utils.alerts')
                    <div class="mb-4">
                        <button
                            class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">Create
                            Product <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="w-full px-4">
                    <x-card>
                        <div class="p-4">
                            <div class="flex flex-wrap -mx-1">
                                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="name">{{ __('Product Name') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="name" required value="{{ old('name') }}">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="code">{{ __('Code') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="code" required value="{{ old('code') }}">
                                    </div>
                                </div>

                                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="category_id">{{ __('Category') }} <span
                                                class="text-red-500">*</span></label>
                                        <select
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="category_id" id="category_id" required>
                                            <option value="" selected disabled>Select Category</option>
                                            @foreach (\App\Models\Category::all() as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="barcode_symbology">{{ __('Barcode Symbology') }} <span
                                                class="text-red-500">*</span></label>
                                        <select
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="barcode_symbology" id="barcode_symbology" required>
                                            <option value="" selected disabled>Select Symbology</option>
                                            <option value="C128">Code 128</option>
                                            <option value="C39">Code 39</option>
                                            <option value="UPCA">UPC-A</option>
                                            <option value="UPCE">UPC-E</option>
                                            <option value="EAN13">EAN-13</option>
                                            <option value="EAN8">EAN-8</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="cost">{{ __('Cost') }} <span
                                                class="text-red-500">*</span></label>
                                        <input id="cost" type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="cost" required value="{{ old('cost') }}">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="price">{{ __('Price') }} <span
                                                class="text-red-500">*</span></label>
                                        <input id="price" type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="price" required value="{{ old('price') }}">
                                    </div>
                                </div>

                                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="quantity">{{ __('Quantity') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="number"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="quantity" required value="{{ old('quantity') }}"
                                            min="1">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="stock_alert">{{ __('Alert Quantity') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="number"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="stock_alert" required value="{{ old('stock_alert') }}"
                                            min="0" max="100">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label for="order_tax">{{ __('Tax (%)') }}</label>
                                        <input type="number"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="order_tax" value="{{ old('order_tax') }}"
                                            min="1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label for="tax_type">{{ __('Tax Type') }}</label>
                                        <select
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="tax_type" id="tax_type">
                                            <option value="" selected disabled>{{ __('Select Tax Type') }}</option>
                                            <option value="1">{{ __('Exclusive') }}</option>
                                            <option value="2">{{ __('Inclusive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label for="unit">{{ __('Unit') }} <i
                                                class="bi bi-question-circle-fill text-info" data-toggle="tooltip"
                                                data-placement="top"
                                                title="This text will be placed after Product Quantity."></i> <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="unit" value="{{ old('unit') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="note">{{ __('Note') }}</label>
                                <textarea name="note" id="note" rows="4 "
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"></textarea>
                            </div>
                        </div>
                    </x-card>
                </div>

                <div class="w-full px-4">
                    <x-card>
                        <div class="p-4">
                            <div class="mb-4">
                                <label for="image">{{ __('Product Images') }} <i
                                        class="bi bi-question-circle-fill text-info" data-toggle="tooltip"
                                        data-placement="top"
                                        title="Max Files: 3, Max File Size: 1MB, Image Size: 400x400"></i></label>
                                <div class="dropzone d-flex flex-wrap align-items-center justify-content-center"
                                    id="document-dropzone">
                                    <div class="dz-message" data-dz-message>
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('third_party_scripts')
    <script src="{{ asset('js/dropzone.js') }}"></script>
@endsection

@push('page_scripts')
    <script>
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: '{{ route('dropzone.upload') }}',
            maxFilesize: 1,
            acceptedFiles: '.jpg, .jpeg, .png',
            maxFiles: 3,
            addRemoveLinks: true,
            dictRemoveFile: "<i class='bi bi-x-circle text-danger'></i> remove",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">');
                uploadedDocumentMap[file.name] = response.name;
            },
            removedfile: function(file) {
                file.previewElement.remove();
                var name = '';
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name;
                } else {
                    name = uploadedDocumentMap[file.name];
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('dropzone.delete') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'file_name': `${name}`
                    },
                });
                $('form').find('input[name="document[]"][value="' + name + '"]').remove();
            },
            init: function() {
                @if (isset($product) && $product->getMedia('images'))
                    var files = {!! json_encode($product->getMedia('images')) !!};
                    for (var i in files) {
                        var file = files[i];
                        this.options.addedfile.call(this, file);
                        this.options.thumbnail.call(this, file, file.original_url);
                        file.previewElement.classList.add('dz-complete');
                        $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">');
                    }
                @endif
            }
        }
    </script>

    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#cost').maskMoney({
                prefix: '{{ settings()->currency->symbol }}',
                thousands: '{{ settings()->currency->thousand_separator }}',
                decimal: '{{ settings()->currency->decimal_separator }}',
            });
            $('#price').maskMoney({
                prefix: '{{ settings()->currency->symbol }}',
                thousands: '{{ settings()->currency->thousand_separator }}',
                decimal: '{{ settings()->currency->decimal_separator }}',
            });

            $('#product-form').submit(function() {
                var cost = $('#cost').maskMoney('unmasked')[0];
                var price = $('#price').maskMoney('unmasked')[0];
                $('#cost').val(cost);
                $('#price').val(price);
            });
        });
    </script>
@endpush
