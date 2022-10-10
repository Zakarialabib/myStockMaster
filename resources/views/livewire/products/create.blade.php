<div>
    <!-- Create Modal -->
    <x-modal wire:model="createProduct">
        <x-slot name="title">
            {{ __('Create Product') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="create">
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                <div>
                    <div class="flex flex-wrap -mx-1">
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <label for="code">{{ __('Product Code') }}</label>
                            <input type="text" wire:model="product.code"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                id="code" placeholder="{{ __('Enter Product Code') }}">
                            <x-input-error :messages="$errors->get('product.code')" for="product.code" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <label for="name">{{ __('Product Name') }}</label>
                            <input type="text" wire:model="product.name"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                id="name" placeholder="{{ __('Enter Product Name') }}">
                            <x-input-error :messages="$errors->get('product.name')" for="product.name" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <label for="quantity">{{ __('Quantity') }}</label>
                            <input type="text" wire:model="product.quantity"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                id="quantity" placeholder="{{ __('Enter Product Quantity') }}">
                            <x-input-error :messages="$errors->get('product.quantity')" for="product.quantity" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <label for="price">{{ __('Price') }}</label>
                            <input type="text" wire:model="product.price"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                id="price" placeholder="{{ __('Enter Product Price') }}">
                            <x-input-error :messages="$errors->get('product.price')" for="product.price" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <label for="cost">{{ __('Cost') }}</label>
                            <input type="text" wire:model="product.cost"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                id="cost" placeholder="{{ __('Enter Product Cost') }}">
                            <x-input-error :messages="$errors->get('product.cost')" for="product.cost" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <label for="category_id">{{ __('Category') }}</label>
                            <x-select-list
                                class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                id="vendor_id" name="vendor_id" wire:model="product.category_id" :options="$this->listsForFields['categories']" />
                            <x-input-error :messages="$errors->get('product.category_id')" for="product.category_id" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <label for="stock_alert">{{ __('Alert Quantity') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" wire:model="product.stock_alert" name="stock_alert" required
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                min="0" max="100">
                            <x-input-error :messages="$errors->get('product.stock_alert')" for="product.stock_alert" class="mt-2" />
                        </div>
                    </div>

                    <x-accordion title="{{ __('Details') }}">
                        <div class="flex flex-wrap -mx-1">
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <div class="mb-4">
                                    <label for="order_tax">{{ __('Tax (%)') }}</label>
                                    <input type="number" wire:model="product.order_tax" name="order_tax"
                                        class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                        min="1">
                                    <x-input-error :messages="$errors->get('product.order_tax')" for="product.order_tax" class="mt-2" />
                                </div>
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <div class="mb-4">
                                    <label for="tax_type">{{ __('Tax type') }}</label>
                                    <select
                                        class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                        wire:model="product.tax_type" name="tax_type">
                                        <option value="" selected disabled>
                                            {{ __('Select Tax Type') }}
                                        </option>
                                        <option value="1">{{ __('Exclusive') }}</option>
                                        <option value="2">{{ __('Inclusive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <div class="mb-4">
                                    <x-label for="unit" :value="__('Unit')" tooltip="{{__('This text will be placed after Product Quantity')}}" />
                                    <input type="text" wire:model="product.unit" name="unit"
                                        class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                        required>
                                        
                                    <x-input-error :messages="$errors->get('product.unit')" for="product.unit" class="mt-2" />
                                </div>
                            </div>
                            <div class="w-full">
                                <x-label for="barcode_symbology" :value="__('Barcode Symbology')" required />
                                <select wire:model="product.barcode_symbology"
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                    name="barcode_symbology" id="barcode_symbology" required>
                                    <option value="C128" selected>Code 128</option>
                                    <option value="C39">Code 39</option>
                                    <option value="UPCA">UPC-A</option>
                                    <option value="UPCE">UPC-E</option>
                                    <option value="EAN13">EAN-13</option>
                                    <option value="EAN8">EAN-8</option>
                                </select>
                                <x-input-error :messages="$errors->get('product.barcode_symbology')" for="product.barcode_symbology" class="mt-2" />
                            </div>

                            <div class="w-full">
                                <label for="note">{{ __('Note') }}</label>
                                <textarea wire:model="product.note" name="note"
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" rows="3"></textarea>
                            </div>
                        </div>
                    </x-accordion>

                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
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

                    <div class="flex justify-end">
                        <x-button primary wire:click="create" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                        <x-button primary wire:click="$toggle('createProduct')" wire:loading.attr="disabled">
                            {{ __('Close') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Create Modal -->
</div>
