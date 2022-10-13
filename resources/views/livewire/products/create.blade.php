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
                    <div class="flex flex-wrap -mx-1 space-y-2">
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="code" :value="__('Code')" required />
                            <x-input id="code" class="block mt-1 w-full" type="text" name="code"
                                wire:model="product.code" placeholder="{{ __('Enter Product Code') }}" required
                                autofocus />
                            <x-input-error :messages="$errors->get('product.code')" for="product.code" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="name" :value="__('Product Name')" required />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                wire:model="product.name" placeholder="{{ __('Enter Product Name') }}" required />
                            <x-input-error :messages="$errors->get('product.name')" for="product.name" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="quantity" :value="__('Quantity')" required />
                            <x-input id="quantity" class="block mt-1 w-full" type="number" name="quantity"
                                wire:model="product.quantity" placeholder="{{ __('Enter Product Quantity') }}"
                                required />
                            <x-input-error :messages="$errors->get('product.quantity')" for="product.quantity" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="price" :value="__('Price')" required />
                            <x-input id="price" class="block mt-1 w-full" type="number" name="price"
                                wire:model="product.price" placeholder="{{ __('Enter Product Price') }}" required />
                            <x-input-error :messages="$errors->get('product.price')" for="product.price" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="cost" :value="__('Cost')" required />
                            <x-input type="number" wire:model="product.cost" id="cost" name="cost"
                                class="block mt-1 w-full" placeholder="{{ __('Enter Product Cost') }}" required />
                            <x-input-error :messages="$errors->get('product.cost')" for="product.cost" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="category" :value="__('Category')" required />
                            <x-select-list
                                class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                id="category_id" name="category_id" wire:model="product.category_id"
                                :options="$this->listsForFields['categories']" />
                            <x-input-error :messages="$errors->get('product.category_id')" for="product.category_id" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="stock_alert" :value="__('Stock Alert')" />
                            <x-input id="stock_alert" class="block mt-1 w-full" type="number" name="stock_alert"
                                wire:model="product.stock_alert" />
                            <x-input-error :messages="$errors->get('product.stock_alert')" for="product.stock_alert" class="mt-2" />
                        </div>
                    </div>

                    <x-accordion title="{{ __('Details') }}">
                        <div class="flex flex-wrap -mx-1">
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="brand" :value="__('Brand')" />
                                <x-select-list
                                    class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                    id="brand_id" name="brand_id" wire:model="product.brand_id" :options="$this->listsForFields['brands']" />
                                <x-input-error :messages="$errors->get('product.brand_id')" for="product.brand_id" class="mt-2" />
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="order_tax" :value="__('Tax')" />
                                <x-input id="order_tax" class="block mt-1 w-full" type="number" name="order_tax"
                                    wire:model="product.order_tax" placeholder="{{ __('Enter Tax') }}" />
                                <x-input-error :messages="$errors->get('product.order_tax')" for="product.order_tax" class="mt-2" />
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="tax_type" :value="__('Tax type')" />
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
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="unit" :value="__('Unit')"
                                    tooltip="{{ __('This text will be placed after Product Quantity') }}" />
                                <x-input id="unit" class="block mt-1 w-full" type="text" name="unit"
                                    wire:model="product.unit" placeholder="{{ __('Enter Unit') }}" />
                                <x-input-error :messages="$errors->get('product.unit')" for="product.unit" class="mt-2" />
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
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
                                <x-label for="note" :value="__('Description')" />
                                <textarea wire:model="product.note" name="note"
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" rows="3"></textarea>
                            </div>
                        </div>
                    </x-accordion>

                    <div class="w-full">
                        <x-label for="image" :value="__('Image')" />
                        <x-fileupload wire:model="image" :file="$image" accept="image/jpg,image/jpeg,image/png" />
                        <x-input-error :messages="$errors->get('image')" for="image" class="mt-2" />
                    </div>

                    <div class="flex justify-start my-2 space-x-2">
                        <x-button primary type="button" wire:click="create" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                        <x-button secondary type="button" wire:click="$toggle('createProduct')"
                            wire:loading.attr="disabled">
                            {{ __('Close') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Create Modal -->
</div>
