<div>
    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create Product') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit="create">
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
                            <x-media-upload title="{{ __('Image') }}" name="image" wire:model="image"
                                :file="$image" single types="PNG / JPEG / WEBP" fileTypes="image/*" />
                            <x-input-error :messages="$errors->get('image')" for="image" class="mt-2" />
                        </div>

                        <div class="w-full px-2 my-2">
                            <x-label for="gallery" :value="__('Product Gallery')" />
                            <x-media-upload title="{{ __('Gallery') }}" name="gallery" wire:model="gallery"
                                :file="$gallery" multiple types="PNG / JPEG / WEBP" fileTypes="image/*" />
                            <x-input-error :messages="$errors->get('gallery')" for="gallery" class="mt-2" />
                        </div>
                    </div>
                    <div class="md:w-3/4 sm:w-full flex flex-wrap items-center">

                        <div class="md:w-1/2 sm:w-full px-3">
                            <x-label for="code" :value="__('Code')" required />
                            <x-input id="code" class="block mt-1 w-full" type="text" name="code"
                                wire:model="code" required autofocus />
                            <x-input-error :messages="$errors->get('code')" for="code" class="mt-2" />
                        </div>
                        <div class="md:w-1/2 sm:w-full px-2">
                            <x-label for="name" :value="__('Product Name')" required />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                wire:model="name" placeholder="{{ __('Enter Product Name') }}" required />
                            <x-input-error :messages="$errors->get('name')" for="name" class="mt-2" />
                        </div>

                        <div class="flex flex-col justify-center px-2 mt-2 w-full">
                            <h4 class="font-semibold text-center">{{ __('Initial Warehouse Stock') }}</h4>

                            <div class="w-full">
                                <h4 class="font-semibold text-center">{{ $this->warehouse?->name }}</h4>
                            </div>
                            <div class="flex items-center w-full gap-2 py-4">
                                <div class="flex-1">
                                    <x-label for="price" :value="__('Price')" required />
                                    <input id="price" required class="w-full" type="text" name="price"
                                        wire:model="productWarehouse.price" />
                                    <x-input-error :messages="$errors->get('productWarehouse.price')" for="price" class="mt-2" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="cost" :value="__('Cost')" required />
                                    <input type="text" required class="w-full" wire:model="productWarehouse.cost"
                                        id="cost" name="cost" />
                                    <x-input-error :messages="$errors->get('costs')" for="cost" class="mt-2" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="old_price" :value="__('Old Price')" required />
                                    <input type="text" required class="w-full"
                                        wire:model="productWarehouse.old_price" id="old_price" name="old_price" />
                                    <x-input-error :messages="$errors->get('old_price')" for="old_price" class="mt-2" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="qty" :value="__('Quantity')" />
                                    <input type="text" required class="w-full" wire:model="productWarehouse.qty"
                                        id="qty" name="qty" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="stock_alert" :value="__('Stock Alert')" required />
                                    <input type="text" required class="w-full"
                                        wire:model="productWarehouse.stock_alert" id="stock_alert"
                                        name="stock_alert" />
                                    <x-input-error :messages="$errors->get('stock_alert')" for="stock_alert" class="mt-2" />
                                </div>
                                <div class="flex-1">
                                    <x-label for="is_ecommerce" :value="__('Is Ecommerce')" required />
                                    <x-input.checkbox id="is_ecommerce" type="checkbox" name="is_ecommerce"
                                        wire:model="productWarehouse.is_ecommerce" />
                                </div>
                            </div>
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
                            <select wire:model="tax_type" required
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                name="tax_type" id="tax_type">
                                <option value="0"> {{ __('Exlusive') }}</option>
                                <option value="1"> {{ __('Inclusive') }}</option>
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
                            <x-input id="embeded_video" class="block mt-1 w-full" type="text"
                                name="embeded_video" wire:model="embeded_video" />
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
                <div class="w-full my-3">
                    <x-button primary type="submit" wire:loading.attr="disabled" class="w-full">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
