<div>
    @section('title', __('Edit Product') . ' ' . $product->name)
    <x-theme.breadcrumb :title="__('Edit Product')" :parent="route('products.index')" :parentName="__('Product List')" :childrenName="__('Edit Product') . ' ' . $product->name" />

    <form wire:submit="update" x-data="{ step: 1 }" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <x-validation-errors class="mb-4" :errors="$errors" />

        <!-- Step 1: Definition -->
        <div x-show="step === 1">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Step 1: Product Definition') }}</h2>
            <div class="flex flex-wrap mb-4">
                <div class="md:w-1/2 sm:w-full px-3 mb-4">
                    <x-label for="name" :value="__('Product Name')" required />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" wire:model="name" required />
                    <x-input-error :messages="$errors->get('name')" for="name" class="mt-2" />
                </div>
                <div class="md:w-1/2 sm:w-full px-3 mb-4">
                    <x-label for="code" :value="__('Code')" required />
                    <x-input id="code" class="block mt-1 w-full" type="text" name="code" wire:model="code" required autofocus />
                    <x-input-error :messages="$errors->get('code')" for="code" class="mt-2" />
                </div>
                <div class="md:w-1/3 sm:w-full px-3 mb-4">
                    <x-label for="category" :value="__('Category')" required />
                    <x-select id="category_create" name="category_create" wire:model.live="category_id">
                        <option value="">{{ __('Select Category') }}</option>
                        @foreach($this->categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('category_id')" for="category_id" class="mt-2" />
                </div>
                <div class="md:w-1/3 sm:w-full px-3 mb-4">
                    <x-label for="brand_id" :value="__('Brand')" />
                    <x-select id="brand_edit" name="brand_edit" wire:model="brand_id">
                        <option value="">{{ __('Select Brand') }}</option>
                        @foreach($this->brands as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('brand_id')" for="brand_id" class="mt-2" />
                </div>
                <div class="md:w-1/3 sm:w-full px-3 mb-4">
                    <x-label for="barcode_symbology" :value="__('Barcode Symbology')" />
                    <x-select wire:model="barcode_symbology" name="barcode_symbology" required>
                        <option value="C128">Code 128</option>
                        <option value="C39">Code 39</option>
                        <option value="UPCA">UPC-A</option>
                        <option value="UPCE">UPC-E</option>
                        <option value="EAN13">EAN-13</option>
                        <option value="EAN8">EAN-8</option>
                    </x-select>
                    <x-input-error :messages="$errors->get('barcode_symbology')" for="barcode_symbology" class="mt-2" />
                </div>
            </div>

            <div class="flex flex-wrap mb-4">
                <div class="w-1/2 px-3">
                    <x-label for="availability" :value="__('Availability')" />
                    <x-input.checkbox wire:model="availability" id="availability" />
                    <x-input-error :messages="$errors->get('availability')" class="mt-2" />
                </div>
                <div class="w-1/2 px-3">
                    <x-label for="seasonality" :value="__('Seasonality')" />
                    <x-input wire:model="seasonality" id="seasonality" type="text" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('seasonality')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Step 2: Economics -->
        <div x-show="step === 2" style="display: none;">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Step 2: Economics & Stock') }}</h2>
            
            <div class="w-full mb-6">
                @foreach ($productWarehouses as $warehouse)
                    <div class="p-4 mb-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900">
                        <h4 class="font-semibold text-lg mb-4">{{ $warehouse->name }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-label for="cost_{{ $warehouse->id }}" :value="__('Cost')" required />
                                <x-input type="text" required class="w-full" wire:model="productWarehouse.{{ $warehouse->id }}.cost" id="cost_{{ $warehouse->id }}" />
                            </div>
                            <div>
                                <x-label for="price_{{ $warehouse->id }}" :value="__('Price')" required />
                                <x-input required class="w-full" type="text" wire:model="productWarehouse.{{ $warehouse->id }}.price" id="price_{{ $warehouse->id }}" />
                            </div>
                            <div>
                                <x-label for="old_price_{{ $warehouse->id }}" :value="__('Old Price')" required />
                                <x-input type="text" required class="w-full" wire:model="productWarehouse.{{ $warehouse->id }}.old_price" id="old_price_{{ $warehouse->id }}" />
                            </div>
                            <div>
                                <x-label for="qty_{{ $warehouse->id }}" :value="__('Quantity')" />
                                <x-input type="text" required class="w-full" wire:model="productWarehouse.{{ $warehouse->id }}.qty" id="qty_{{ $warehouse->id }}" />
                            </div>
                            <div>
                                <x-label for="stock_alert_{{ $warehouse->id }}" :value="__('Stock Alert')" required />
                                <x-input type="text" required class="w-full" wire:model="productWarehouse.{{ $warehouse->id }}.stock_alert" id="stock_alert_{{ $warehouse->id }}" />
                            </div>
                            <div class="flex items-center mt-6">
                                <x-input.checkbox id="is_ecommerce_{{ $warehouse->id }}" wire:model="productWarehouse.{{ $warehouse->id }}.is_ecommerce" />
                                <x-label for="is_ecommerce_{{ $warehouse->id }}" :value="__('Is Ecommerce')" class="ml-2 mb-0" required />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-wrap mb-4">
                <div class="lg:w-1/3 sm:w-1/2 px-2 mb-4">
                    <x-label for="tax_type" :value="__('Tax type')" />
                    <x-select wire:model="tax_type" required id="tax_type">
                        <option value="0">{{ __('Exclusive') }}</option>
                        <option value="1">{{ __('Inclusive') }}</option>
                    </x-select>
                    <x-input-error :messages="$errors->get('tax_type')" for="tax_type" class="mt-2" />
                </div>
                <div class="lg:w-1/3 sm:w-1/2 px-2 mb-4">
                    <x-label for="tax_amount" :value="__('Tax amount')" />
                    <x-input id="tax_amount" class="block mt-1 w-full" type="text" wire:model="tax_amount" />
                    <x-input-error :messages="$errors->get('tax_amount')" for="tax_amount" class="mt-2" />
                </div>
                <div class="lg:w-1/3 sm:w-1/2 px-2 mb-4">
                    <x-label for="unit" :value="__('Unit')" />
                    <x-input id="unit" class="block mt-1 w-full" type="text" wire:model="unit" />
                    <x-input-error :messages="$errors->get('unit')" for="unit" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Step 3: Media/Notes -->
        <div x-show="step === 3" style="display: none;">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Step 3: Media & Details') }}</h2>
            
            <div class="flex flex-wrap mb-4">
                <div class="w-full px-2 my-2">
                    <x-label for="image" :value="__('Product Image')" />
                    <x-media-upload title="{{ __('Image') }}" name="image" wire:model="image" :file="$image" single types="PNG / JPEG / WEBP" fileTypes="image/*" />
                    <x-input-error :messages="$errors->get('image')" for="image" class="mt-2" />
                </div>
                <div class="w-full px-2 my-2">
                    <x-label for="gallery" :value="__('Product Gallery')" />
                    <x-media-upload title="{{ __('Gallery') }}" name="gallery" wire:model="gallery" :file="$gallery" multiple types="PNG / JPEG / WEBP" fileTypes="image/*" />
                    <x-input-error :messages="$errors->get('gallery')" for="gallery" class="mt-2" />
                </div>
                <div class="w-full px-2 mt-4">
                    <x-label for="description" :value="__('Note / Description')" />
                    <textarea rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 border-gray-300 rounded-md shadow-sm" wire:model="description"></textarea>
                    <x-input-error :messages="$errors->get('description')" for="description" class="mt-2" />
                </div>
                <div class="w-full px-2 mt-4">
                    <x-label for="embeded_video" :value="__('Embedded Video')" />
                    <x-input id="embeded_video" class="block mt-1 w-full" type="text" wire:model="embeded_video" />
                    <x-input-error :messages="$errors->get('embeded_video')" for="embeded_video" class="mt-2" />
                </div>
                <div class="w-full px-2 mt-4">
                    <x-label for="usage" :value="__('Usage')" />
                    <textarea rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 border-gray-300 rounded-md shadow-sm" wire:model="usage"></textarea>
                    <x-input-error :messages="$errors->get('usage')" for="usage" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between w-full mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div>
                <x-button type="button" secondary x-show="step > 1" @click="step--">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Previous') }}
                </x-button>
            </div>
            <div>
                <x-button type="button" primary x-show="step < 3" @click="step++">
                    {{ __('Next') }} <i class="fas fa-arrow-right ml-2"></i>
                </x-button>
                <x-button type="submit" primary x-show="step === 3" wire:loading.attr="disabled">
                    <i class="fas fa-save mr-2"></i> {{ __('Save Changes') }}
                </x-button>
            </div>
        </div>
    </form>
</div>