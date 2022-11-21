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
                    <div class="flex flex-wrap -mx-2 mb-3">
                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="code" :value="__('Code')" required />
                            <x-input id="code" class="block mt-1 w-full" type="text" name="code"
                                wire:model="code" placeholder="{{ __('Enter Product Code') }}" required
                                autofocus />
                            <x-input-error :messages="$errors->get('code')" for="code" class="mt-2" />
                        </div>
                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="name" :value="__('Product Name')" required />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                wire:model="name" placeholder="{{ __('Enter Product Name') }}" required />
                            <x-input-error :messages="$errors->get('name')" for="name" class="mt-2" />
                        </div>
                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="quantity" :value="__('Quantity')" required />
                            <x-input id="quantity" class="block mt-1 w-full" type="number" name="quantity"
                                wire:model="quantity" placeholder="{{ __('Enter Product Quantity') }}"
                                required />
                            <x-input-error :messages="$errors->get('quantity')" for="quantity" class="mt-2" />
                        </div>
                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="price" :value="__('Price')" required />
                            <x-input id="price" class="block mt-1 w-full" type="number" name="price"
                                wire:model="price" placeholder="{{ __('Enter Product Price') }}" required />
                            <x-input-error :messages="$errors->get('price')" for="price" class="mt-2" />
                        </div>
                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="cost" :value="__('Cost')" required />
                            <x-input type="number" wire:model="cost" id="cost" name="cost"
                                class="block mt-1 w-full" placeholder="{{ __('Enter Product Cost') }}" required />
                            <x-input-error :messages="$errors->get('cost')" for="cost" class="mt-2" />
                        </div>
                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="category" :value="__('Category')" required />
                            <x-select-list
                                class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                id="category_id" name="category_id" wire:model="category_id"
                                :options="$this->listsForFields['categories']" />
                            <x-input-error :messages="$errors->get('category_id')" for="category_id" class="mt-2" />
                        </div>
                        <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                            <x-label for="stock_alert" :value="__('Stock Alert')" />
                            <x-input id="stock_alert" class="block mt-1 w-full" type="number" name="stock_alert"
                                wire:model="stock_alert" />
                            <x-input-error :messages="$errors->get('stock_alert')" for="stock_alert" class="mt-2" />
                        </div>
                    </div>

                    <x-accordion title="{{ __('Details') }}">
                        <div class="flex flex-wrap -mx-2 mb-3">
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="warehouse" :value="__('Warehouse')" />
                                <x-select-list
                                    class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                    id="warehouse_id" name="warehouse_id" wire:model="warehouse_id" :options="$this->listsForFields['warehouses']" />
                                <x-input-error :messages="$errors->get('warehouse_id')" for="warehouse_id" class="mt-2" />
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="brand" :value="__('Brand')" />
                                <x-select-list
                                    class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                    id="brand_id" name="brand_id" wire:model="brand_id" :options="$this->listsForFields['brands']" />
                                <x-input-error :messages="$errors->get('brand_id')" for="brand_id" class="mt-2" />
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="order_tax" :value="__('Tax')" />
                                <x-input id="order_tax" class="block mt-1 w-full" type="number" name="order_tax"
                                    wire:model="order_tax" placeholder="{{ __('Enter Tax') }}" />
                                <x-input-error :messages="$errors->get('order_tax')" for="order_tax" class="mt-2" />
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="tax_type" :value="__('Tax type')" />
                                <select
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                    wire:model="tax_type" name="tax_type">
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
                                    wire:model="unit" placeholder="{{ __('Enter Unit') }}" />
                                <x-input-error :messages="$errors->get('unit')" for="unit" class="mt-2" />
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="barcode_symbology" :value="__('Barcode Symbology')" required />
                                <select wire:model="barcode_symbology"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                    name="barcode_symbology" id="barcode_symbology" required>
                                    <option value="C128" selected>Code 128</option>
                                    <option value="C39">Code 39</option>
                                    <option value="UPCA">UPC-A</option>
                                    <option value="UPCE">UPC-E</option>
                                    <option value="EAN13">EAN-13</option>
                                    <option value="EAN8">EAN-8</option>
                                </select>
                                <x-input-error :messages="$errors->get('barcode_symbology')" for="barcode_symbology" class="mt-2" />
                            </div>
                            <div class="w-full">
                                <x-label for="note" :value="__('Description')" />
                                <textarea wire:model="note" name="note"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" rows="3"></textarea>
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
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Create Modal -->
</div>
