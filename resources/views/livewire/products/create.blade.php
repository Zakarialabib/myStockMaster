<div>
    <x-modal wire:model="createModal" name="createModal">
        <x-slot name="title">
            {{ __('Create Product') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit="create" x-data="{ step: @entangle('step') }">
                <x-validation-errors class="mb-4" :errors="$errors" />
                
                <!-- Step 1: Definition -->
                <div x-show="step === 1">
                    <div class="flex flex-wrap mb-4">
                        <div class="md:w-1/2 sm:w-full px-3">
                            <x-label for="name" :value="__('Product Name')" required />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" wire:model="form.name" required />
                            <x-input-error :messages="$errors->get('form.name')" for="form.name" class="mt-2" />
                        </div>
                        <div class="md:w-1/2 sm:w-full px-3">
                            <x-label for="code" :value="__('Code')" required />
                            <x-input id="code" class="block mt-1 w-full" type="text" name="code" wire:model="form.code" required autofocus />
                            <x-input-error :messages="$errors->get('form.code')" for="form.code" class="mt-2" />
                        </div>
                        <div class="md:w-1/3 sm:w-full px-3 mt-4">
                            <x-label for="category" :value="__('Category')" required />
                            <x-select-list :options="$this->categories" id="category_create" name="category_create" wire:model.live="form.category_id" />
                            <x-input-error :messages="$errors->get('form.category_id')" for="form.category_id" class="mt-2" />
                        </div>
                        <div class="md:w-1/3 sm:w-full px-3 mt-4">
                            <x-label for="brand_id" :value="__('Brand')" />
                            <x-select-list :options="$this->brands" id="brand_edit" name="brand_edit" wire:model="form.brand_id" />
                            <x-input-error :messages="$errors->get('form.brand_id')" for="form.brand_id" class="mt-2" />
                        </div>
                        <div class="md:w-1/3 sm:w-full px-3 mt-4">
                            <x-label for="barcode_symbology" :value="__('Barcode Symbology')" />
                            <x-select wire:model="form.barcode_symbology" name="barcode_symbology" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" required>
                                <option value="C128">Code 128</option>
                                <option value="C39">Code 39</option>
                                <option value="UPCA">UPC-A</option>
                                <option value="UPCE">UPC-E</option>
                                <option value="EAN13">EAN-13</option>
                                <option value="EAN8">EAN-8</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('form.barcode_symbology')" for="form.barcode_symbology" class="mt-2" />
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap mb-4">
                        <div class="w-1/2 px-3">
                            <x-label for="availability" :value="__('Availability')" />
                            <x-input.checkbox wire:model="form.availability" id="availability" />
                            <x-input-error :messages="$errors->get('form.availability')" class="mt-2" />
                        </div>
                        <div class="w-1/2 px-3">
                            <x-label for="seasonality" :value="__('Seasonality')" />
                            <x-input wire:model="form.seasonality" id="seasonality" type="text" class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('form.seasonality')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Step 2: Economics -->
                <div x-show="step === 2" style="display: none;">
                    <div class="flex flex-col justify-center px-2 mt-2 w-full mb-4">
                        <h4 class="font-semibold text-center">{{ __('Initial Warehouse Stock') }}</h4>
                        <div class="w-full">
                            <h4 class="font-semibold text-center">{{ $this->warehouse?->name }}</h4>
                        </div>
                        <div class="flex flex-wrap items-center w-full gap-2 py-4">
                            <div class="flex-1">
                                <x-label for="cost" :value="__('Cost')" required />
                                <input type="text" required class="w-full" wire:model="form.cost" id="cost" name="cost" />
                                <x-input-error :messages="$errors->get('form.cost')" for="form.cost" class="mt-2" />
                            </div>
                            <div class="flex-1">
                                <x-label for="price" :value="__('Price')" required />
                                <input id="price" required class="w-full" type="text" name="price" wire:model="form.price" />
                                <x-input-error :messages="$errors->get('form.price')" for="form.price" class="mt-2" />
                            </div>
                            <div class="flex-1">
                                <x-label for="old_price" :value="__('Old Price')" required />
                                <input type="text" required class="w-full" wire:model="form.productWarehouse.old_price" id="old_price" name="old_price" />
                                <x-input-error :messages="$errors->get('form.productWarehouse.old_price')" for="form.productWarehouse.old_price" class="mt-2" />
                            </div>
                            <div class="flex-1">
                                <x-label for="qty" :value="__('Quantity')" />
                                <input type="text" required class="w-full" wire:model="form.productWarehouse.qty" id="qty" name="qty" />
                            </div>
                            <div class="flex-1">
                                <x-label for="stock_alert" :value="__('Stock Alert')" required />
                                <input type="text" required class="w-full" wire:model="form.stock_alert" id="stock_alert" name="stock_alert" />
                                <x-input-error :messages="$errors->get('form.stock_alert')" for="form.stock_alert" class="mt-2" />
                            </div>
                            <div class="flex-1 mt-6">
                                <x-label for="is_ecommerce" :value="__('Is Ecommerce')" required />
                                <x-input.checkbox id="is_ecommerce" type="checkbox" name="is_ecommerce" wire:model="form.productWarehouse.is_ecommerce" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap mb-4">
                        <div class="lg:w-1/3 sm:w-1/2 px-2">
                            <x-label for="tax_type" :value="__('Tax type')" />
                            <x-select wire:model="form.tax_type" required class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" name="tax_type" id="tax_type">
                                <option value="0">{{ __('Exlusive') }}</option>
                                <option value="1">{{ __('Inclusive') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('form.tax_type')" for="form.tax_type" class="mt-2" />
                        </div>
                        <div class="lg:w-1/3 sm:w-1/2 px-2">
                            <x-label for="order_tax" :value="__('Tax amount')" />
                            <x-input id="order_tax" class="block mt-1 w-full" type="text" name="order_tax" wire:model="form.order_tax" />
                            <x-input-error :messages="$errors->get('form.order_tax')" for="form.order_tax" class="mt-2" />
                        </div>
                        <div class="lg:w-1/3 sm:w-1/2 px-2">
                            <x-label for="unit" :value="__('Unit')" />
                            <x-input id="unit" class="block mt-1 w-full" type="text" name="unit" wire:model="form.unit" />
                            <x-input-error :messages="$errors->get('form.unit')" for="form.unit" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Step 3: Media/Notes -->
                <div x-show="step === 3" style="display: none;">
                    <div class="flex flex-wrap mb-4">
                        <div class="w-full px-2 my-2">
                            <x-label for="image" :value="__('Product Image')" />
                            <x-media-upload title="{{ __('Image') }}" name="form.image" wire:model="form.image" :file="$form->image" single types="PNG / JPEG / WEBP" fileTypes="image/*" />
                            <x-input-error :messages="$errors->get('form.image')" for="form.image" class="mt-2" />
                        </div>
                        <div class="w-full px-2 my-2">
                            <x-label for="gallery" :value="__('Product Gallery')" />
                            <x-media-upload title="{{ __('Gallery') }}" name="form.gallery" wire:model="form.gallery" :file="$form->gallery" multiple types="PNG / JPEG / WEBP" fileTypes="image/*" />
                            <x-input-error :messages="$errors->get('form.gallery')" for="form.gallery" class="mt-2" />
                        </div>
                        <div class="w-full px-2 mt-4">
                            <x-label for="note" :value="__('Note / Description')" />
                            <textarea rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 border-gray-300 rounded-md shadow-sm" name="note" wire:model="form.note"></textarea>
                            <x-input-error :messages="$errors->get('form.note')" for="form.note" class="mt-2" />
                        </div>
                        <div class="w-full px-2 mt-4">
                            <x-label for="embeded_video" :value="__('Embeded Video')" />
                            <x-input id="embeded_video" class="block mt-1 w-full" type="text" name="embeded_video" wire:model="form.embeded_video" />
                            <x-input-error :messages="$errors->get('form.embeded_video')" for="form.embeded_video" class="mt-2" />
                        </div>
                        <div class="w-full px-2 mt-4">
                            <x-label for="usage" :value="__('Usage')" />
                            <textarea rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 border-gray-300 rounded-md shadow-sm" name="usage" wire:model="form.usage"></textarea>
                            <x-input-error :messages="$errors->get('form.usage')" for="form.usage" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-between w-full mt-6">
                    <div>
                        <x-button type="button" secondary x-show="step > 1" @click="step--">
                            {{ __('Previous') }}
                        </x-button>
                    </div>
                    
                    <div>
                        <x-button type="button" primary x-show="step < 3" @click="step++">
                            {{ __('Next') }}
                        </x-button>
                        
                        <x-button type="submit" primary x-show="step === 3" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
