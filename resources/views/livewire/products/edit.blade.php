<div>
    @section('title', __('Edit Product') . ' ' . $product->name)
    <x-theme.breadcrumb :title="__('Edit Product')" :parent="route('products.index')" :parentName="__('Product List')" :childrenName="__('Edit Product') . ' ' . $product->name" />

    <form wire:submit="update">
        <x-validation-errors class="mb-4" :errors="$errors" />
        <div class="flex flex-wrap mb-4">
            <div class="md:w-1/4 sm:w-full border border-gray-300">
                <div class="w-full px-2">
                    <x-label for="category" :value="__('Category')" required />
                    <x-select-list :options="$this->categories" id="category_create" name="category_create"
                        wire:model.live="category_id" />
                    <x-input-error :messages="$errors->get('category_id')" for="category_id" class="mt-2" />
                </div>
                <div class="w-full px-2">
                    <x-label for="brand_id" :value="__('Brand')" />
                    <x-select-list :options="$this->brands" id="brand_edit" name="brand_edit" wire:model="brand_id" />
                    <x-input-error :messages="$errors->get('brand_id')" for="brand_id" class="mt-2" />
                </div>

                {{-- <div class="flex justify-center items-center sm:w-full px-2 gap-2">
                    <x-label for="featured" :value="__('Favorite proudct')" />
                    <x-input.checkbox id="featured" type="checkbox" name="featured" wire:model="featured" />

                    <x-label for="best" :value="__('Best proudct')" />
                    <x-input.checkbox id="best" type="checkbox" name="best" wire:model="best" />

                    <x-label for="hot" :value="__('Hot proudct')" />
                    <x-input.checkbox id="hot" type="checkbox" name="hot" wire:model="hot" />

                </div> --}}

                <div class="w-full px-2 my-2">
                    <x-label for="image" :value="__('Product Image')" />
                    <x-media-upload title="{{ __('Image') }}" name="image" wire:model="image" :file="$image"
                        single types="PNG / JPEG / WEBP" fileTypes="image/*" />
                    <x-input-error :messages="$errors->get('image')" for="image" class="mt-2" />
                </div>

                <div class="w-full px-2 my-2">
                    <x-label for="gallery" :value="__('Product Gallery')" />
                    <x-media-upload title="{{ __('Gallery') }}" name="gallery" wire:model="gallery" :file="$gallery"
                        multiple types="PNG / JPEG / WEBP" fileTypes="image/*" />
                    <x-input-error :messages="$errors->get('gallery')" for="gallery" class="mt-2" />
                </div>
            </div>
            <div class="md:w-3/4 sm:w-full flex flex-wrap items-center">

                <div class="md:w-1/2 sm:w-full px-3">
                    <x-label for="code" :value="__('Code')" required />
                    <x-input id="code" class="block mt-1 w-full" type="text" name="code" wire:model="code"
                        disabled />
                    <x-input-error :messages="$errors->get('code')" for="code" class="mt-2" />
                </div>
                <div class="md:w-1/2 sm:w-full px-2">
                    <x-label for="name" :value="__('Product Name')" required />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" wire:model="name"
                        placeholder="{{ __('Enter Product Name') }}" required />
                    <x-input-error :messages="$errors->get('name')" for="name" class="mt-2" />
                </div>

                <div class="flex flex-col justify-center px-2 mt-2 w-full">
                    <h4 class="font-semibold text-center">{{ __('Initial Warehouse Stock') }}</h4>
                    @if ($productWarehouses)
                        @foreach ($productWarehouses as $warehouse)
                            <div class="w-full">
                                <h4 class="font-semibold text-center">{{ $warehouse->name }}</h4>
                            </div>
                            <div class="flex items-center w-full gap-2 py-4">
                                <div class="flex-1">
                                    <x-label for="price_{{ $warehouse->id }}" :value="__('Price')" required />
                                    <input id="price_{{ $warehouse->id }}" required class="w-full" type="text"
                                        name="price_{{ $warehouse->id }}"
                                        wire:model="productWarehouse.{{ $warehouse->id }}.price" />
                                    <x-input-error :messages="$errors->get('prices.' . $warehouse->id)" for="price_{{ $warehouse->id }}" class="mt-2" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="cost_{{ $warehouse->id }}" :value="__('Cost')" required />
                                    <input type="text" required class="w-full"
                                        wire:model="productWarehouse.{{ $warehouse->id }}.cost"
                                        id="cost_{{ $warehouse->id }}" name="cost_{{ $warehouse->id }}" />
                                    <x-input-error :messages="$errors->get('costs.' . $warehouse->id)" for="cost_{{ $warehouse->id }}" class="mt-2" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="old_price_{{ $warehouse->id }}" :value="__('Old Price')" required />
                                    <input type="text" required class="w-full"
                                        wire:model="productWarehouse.{{ $warehouse->id }}.old_price"
                                        id="old_price_{{ $warehouse->id }}" name="old_price_{{ $warehouse->id }}" />
                                    <x-input-error :messages="$errors->get('old_price.' . $warehouse->id)" for="old_price_{{ $warehouse->id }}"
                                        class="mt-2" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="qty_{{ $warehouse->id }}" :value="__('Quantity')" />
                                    <input type="text" required class="w-full"
                                        wire:model="productWarehouse.{{ $warehouse->id }}.qty"
                                        id="qty_{{ $warehouse->id }}" name="qty_{{ $warehouse->id }}" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="stock_alert" :value="__('Stock Alert')" required />
                                    <input type="text" required class="w-full"
                                        wire:model="productWarehouse.{{ $warehouse->id }}.stock_alert"
                                        id="stock_alert_{{ $warehouse->id }}"
                                        name="stock_alert_{{ $warehouse->id }}" />
                                    <x-input-error :messages="$errors->get('stock_alert')" for="stock_alert" class="mt-2" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="is_ecommerce" :value="__('Is Ecommerce')" required />
                                    <x-input.checkbox id="is_ecommerce_{{ $warehouse->id }}" type="checkbox"
                                        name="is_ecommerce_{{ $warehouse->id }}"
                                        wire:model="productWarehouse.{{ $warehouse->id }}.is_ecommerce" />
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="w-full px-2">
                    <x-label for="description" :value="__('Description')" />
                    <textarea rows="3"
                        class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" name="description"
                        wire:model="description">
                        </textarea>
                </div>
                <div class="lg:w-1/3 sm:w-1/2 px-2">
                    <x-label for="tax_type" :value="__('Tax type')" />
                    <select wire:model="tax_type" name="tax_type"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                        <option value="0">{{ __('Inclusive') }}</option>
                        <option value="1">{{ __('Exclusive') }}</option>
                    </select>
                </div>

                
                <div class="lg:w-1/3 sm:w-1/2 px-2">
                    <x-label for="tax_amount" :value="__('Tax amount')" />
                    <x-input id="tax_amount" class="block mt-1 w-full" type="text" name="tax_amount"
                        wire:model="tax_amount" />
                    <x-input-error :messages="$errors->get('tax_amount')" for="tax_amount" class="mt-2" />
                </div>

                <div class="lg:w-1/3 sm:w-1/2 px-2">
                    <x-label for="barcode_symbology" :value="__('Barcode Symbology')" />
                    <select wire:model="barcode_symbology" name="barcode_symbology"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        required>
                        <option selected value="C128">Code 128</option>
                        <option value="C39">Code 39</option>
                        <option value="UPCA">UPC-A</option>
                        <option value="UPCE">UPC-E</option>
                        <option value="EAN13">EAN-13</option>
                        <option value="EAN8">EAN-8</option>
                    </select>
                </div>

                <div class="w-full px-2">
                    <x-label for="video" :value="__('Embeded Video')" />
                    <x-input id="embeded_video" class="block mt-1 w-full" type="text" name="embeded_video"
                        wire:model="embeded_video" />
                    <x-input-error :messages="$errors->get('embeded_video')" for="embeded_video" class="mt-2" />
                </div>

                <div class="w-full px-2">
                    <x-label for="usage" :value="__('Usage')" />
                    <textarea rows="3"
                        class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" name="usage"
                        wire:model="usage">
                            </textarea>
                    <x-input-error :messages="$errors->get('usage')" for="usage" class="mt-2" />
                </div>

            </div>
        </div>
        <div class="w-full px-4">
            <x-button primary type="submit" wire:loading.attr="disabled" class="w-full text-center">
                {{ __('Update') }}
            </x-button>
        </div>
    </form>
</div>
