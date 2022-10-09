<div class="modal-dialog">

    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{__('Update Product')}} : {{ $product->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="product-form" action="{{ route('products.update', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="p-4">
                                <div class="flex flex-wrap -mx-1">
                                    <div class="lg:w-1/2 sm:w-1/2 px-2">
                                        <div class="mb-4">
                                            <label for="name" autofocus>{{ __('Product Name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="name" required
                                                value="{{ $product->name }}">
                                        </div>
                                    </div>
                                    <div class="lg:w-1/2 sm:w-1/2 px-2">
                                        <div class="mb-4">
                                            <label for="code">{{ __('Code') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="code" required
                                                value="{{ $product->code }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap -mx-1">
                                    <div class="w-full px-4">
                                        <div class="mb-4">
                                            <label for="category_id">{{ __('Category') }} <span
                                                    class="text-danger">*</span></label>
                                            <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="category_id" id="category_id" required>
                                                @foreach (\App\Models\Category::all() as $category)
                                                    <option
                                                        {{ $category->id == $product->category->id ? 'selected' : '' }}
                                                        value="{{ $category->id }}">{{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="flex flex-wrap -mx-1">
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="cost">{{ __('Cost') }} <span
                                                        class="text-danger">*</span></label>
                                                <input id="cost" type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                                    min="0" name="cost" required
                                                    value="{{ $product->cost }}">
                                            </div>
                                        </div>
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="price">{{ __('Price') }} <span
                                                        class="text-danger">*</span></label>
                                                <input id="price" type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                                    min="0" name="price" required
                                                    value="{{ $product->price }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap -mx-1">
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="quantity">{{ __('Quantity') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="quantity"
                                                    required value="{{ $product->quantity }}" min="1">
                                            </div>
                                        </div>
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="stock_alert">{{ __('Alert Quantity') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="stock_alert"
                                                    required value="{{ $product->stock_alert }}"
                                                    min="0">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingOne">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                        aria-expanded="false" aria-controls="flush-collapseOne">
                                                        {{ __('More details') }}
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingOne"
                                                data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <div class="flex flex-wrap -mx-1">
                                                        <div class="col-md-4">
                                                            <div class="mb-4">
                                                                <label for="order_tax">{{__('Tax')}} (%)</label>
                                                                <input type="number" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                                                    name="order_tax"
                                                                    value="{{ $product->order_tax }}"
                                                                    min="0" max="100">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4">
                                                                <label for="tax_type">{{__('Tax type')}}</label>
                                                                <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="tax_type"
                                                                    id="tax_type">
                                                                    <option value="" selected>{{__('None')}}</option>
                                                                    <option
                                                                        {{ $product->tax_type == 1 ? 'selected' : '' }}
                                                                        value="1">{{__('Exclusive')}}</option>
                                                                    <option
                                                                        {{ $product->tax_type == 2 ? 'selected' : '' }}
                                                                        value="2">{{__('Inclusive')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4">
                                                                <label for="unit">{{__('Unit')}} <i
                                                                        class="bi bi-question-circle-fill text-info"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="This text will be placed after Product Quantity."></i>
                                                                    <span class="text-red-500">*</span></label>
                                                                <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                                                    name="unit"
                                                                    value="{{ old('unit') ?? $product->unit }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="barcode_symbology">{{__('Barcode Symbology')}} <span
                                                                class="text-danger">*</span></label>
                                                        <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="barcode_symbology"
                                                            id="barcode_symbology" required>
                                                            <option selected
                                                                {{ $product->barcode_symbology == 'C128' ? 'selected' : '' }}
                                                                value="C128">Code 128</option>
                                                            <option
                                                                {{ $product->barcode_symbology == 'C39' ? 'selected' : '' }}
                                                                value="C39">Code 39</option>
                                                            <option
                                                                {{ $product->barcode_symbology == 'UPCA' ? 'selected' : '' }}
                                                                value="UPCA">UPC-A</option>
                                                            <option
                                                                {{ $product->barcode_symbology == 'UPCE' ? 'selected' : '' }}
                                                                value="UPCE">UPC-E</option>
                                                            <option
                                                                {{ $product->barcode_symbology == 'EAN13' ? 'selected' : '' }}
                                                                value="EAN13">EAN-13</option>
                                                            <option
                                                                {{ $product->barcode_symbology == 'EAN8' ? 'selected' : '' }}
                                                                value="EAN8">EAN-8</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="note">{{__('Note')}}</label>
                                                        <textarea name="note" id="note" rows="4 " class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded">{{ $product->note }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <div class="card">
                            <div class="p-4">
                                <div class="mb-4">
                                    <label for="image">{{__('Product Images')}} <i
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
                        </div>
                    </div>
                </div>
                <div class="w-full px-4">
                    @include('utils.alerts')
                    <div class="mb-4">
                        <button class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">{{__('Update')}}<i class="bi bi-check"></i></button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
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

            $('#cost').maskMoney('mask');
            $('#price').maskMoney('mask');

            $('#product-form').submit(function() {
                var cost = $('#cost').maskMoney('unmasked')[0];
                var price = $('#price').maskMoney('unmasked')[0];
                $('#cost').val(cost);
                $('#price').val(price);
            });
        });
    </script>
@endpush
