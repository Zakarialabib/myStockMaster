<div>
    <div class="modal-dialog w-auto">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create Product') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">
                <form id="product-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="w-full px-4">
                            @include('utils.alerts')
                            <div class="mb-4">
                                <button
                                    class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">Create
                                    {{ __('Product') }} <i class="bi bi-check"></i></button>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="p-4">
                                    <div class="flex flex-wrap -mx-1">
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="product_name">{{ __('Product Name') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="product_name" required
                                                    value="{{ old('product_name') }}">
                                            </div>
                                        </div>
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="product_code">{{ __('Code') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="product_code" required
                                                    value="{{ old('product_code') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap -mx-1">
                                        <div class="w-full px-2">
                                            <div class="mb-4">
                                                <label for="category_id">{{__('Category')}} <span
                                                        class="text-danger">*</span></label>
                                                <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="category_id" id="category_id"
                                                    required>
                                                    <option value="" selected disabled>Select Category</option>
                                                    @foreach (\App\Models\Category::all() as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap -mx-1">
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="product_cost">{{ __('Cost') }} <span
                                                        class="text-danger">*</span></label>
                                                <input id="product_cost" type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                                    name="product_cost" required value="{{ old('product_cost') }}">
                                            </div>
                                        </div>
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="product_price">{{ __('Price') }} <span
                                                        class="text-danger">*</span></label>
                                                <input id="product_price" type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                                    name="product_price" required value="{{ old('product_price') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap -mx-1">
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="product_quantity">{{ __('Quantity') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="product_quantity"
                                                    required value="{{ old('product_quantity') }}" min="1">
                                            </div>
                                        </div>
                                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="product_stock_alert">{{ __('Alert Quantity') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="product_stock_alert"
                                                    required value="{{ old('product_stock_alert') }}" min="0"
                                                    max="100">
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
                                                                <label for="product_order_tax">{{ __('Tax') }}
                                                                    (%)</label>
                                                                <input type="number" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                                                    name="product_order_tax"
                                                                    value="{{ old('product_order_tax') }}"
                                                                    min="1">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4">
                                                                <label
                                                                    for="product_tax_type">{{ __('Tax type') }}</label>
                                                                <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="product_tax_type"
                                                                    id="product_tax_type">
                                                                    <option value="" selected disabled>
                                                                        {{ __('Select Tax
                                                                                                                                                                                                                                                                                                                                                            Type') }}
                                                                    </option>
                                                                    <option value="1">Exclusive</option>
                                                                    <option value="2">Inclusive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4">
                                                                <label for="product_unit">{{ __('Unit') }} <i
                                                                        class="bi bi-question-circle-fill text-info"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="This text will be placed after Product Quantity."></i>
                                                                    <span class="text-red-500">*</span></label>
                                                                <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                                                    name="product_unit"
                                                                    value="{{ old('product_unit') }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="barcode_symbology">{{ __('Barcode Symbology') }}
                                                            <span class="text-red-500">*</span></label>
                                                        <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="product_barcode_symbology"
                                                            id="barcode_symbology" required>
                                                            <option value="C128" selected>Code 128</option>
                                                            <option value="C39">Code 39</option>
                                                            <option value="UPCA">UPC-A</option>
                                                            <option value="UPCE">UPC-E</option>
                                                            <option value="EAN13">EAN-13</option>
                                                            <option value="EAN8">EAN-8</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="product_note">{{ __('Note') }}</label>
                                                        <textarea name="product_note" id="product_note" rows="4 " class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"></textarea>
                                                        < </div>
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
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
