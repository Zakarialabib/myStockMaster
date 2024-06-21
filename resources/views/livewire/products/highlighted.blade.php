<div>
    <x-modal wire:model="highlightModal">

        <x-slot name="title">
            {{ __('Highlight') }} - {{ $product?->name}}
        </x-slot>
        
        <x-slot name="content">
            <form wire:submit="saveHighlight">
                <div class="flex flex-wrap">
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="featured" :value="__('Featured product')" />
                        <input id="featured" class="block mt-1 w-full" type="checkbox" name="featured"
                            wire:model="featured" />
                        <x-input-error :messages="$errors->get('featured')" for="featured" class="mt-2" />
                    </div>
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="hot" :value="__('Hot product')" />
                        <input id="hot" class="block mt-1 w-full" type="checkbox" name="hot"
                            wire:model="hot" />
                        <x-input-error :messages="$errors->get('hot')" for="hot" class="mt-2" />
                    </div>
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="best" :value="__('Best product')" />
                        <input id="best" class="block mt-1 w-full" type="checkbox" name="best"
                            wire:model="best" />
                        <x-input-error :messages="$errors->get('best')" for="best" class="mt-2" />
                    </div>
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="top" :value="__('Top product')" />
                        <input id="top" class="block mt-1 w-full" type="checkbox" name="top"
                            wire:model="top" />
                        <x-input-error :messages="$errors->get('top')" for="top" class="mt-2" />
                    </div>
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="latest" :value="__('Latest product')" />
                        <input id="latest" class="block mt-1 w-full" type="checkbox" name="latest"
                            wire:model="latest" />
                        <x-input-error :messages="$errors->get('latest')" for="latest" class="mt-2" />
                    </div>
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="big" :value="__('Big saving')" />
                        <input id="big" class="block mt-1 w-full" type="checkbox" name="big"
                            wire:model="big" />
                        <x-input-error :messages="$errors->get('big')" for="big" class="mt-2" />
                    </div>
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="trending" :value="__('Trending')" />
                        <input id="trending" class="block mt-1 w-full" type="checkbox" name="trending"
                            wire:model="trending" />
                        <x-input-error :messages="$errors->get('trending')" for="trending" class="mt-2" />
                    </div>
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="sale" :value="__('Sale')" />
                        <input id="sale" class="block mt-1 w-full" type="checkbox" name="sale"
                            wire:model="sale" />
                        <x-input-error :messages="$errors->get('sale')" for="sale" class="mt-2" />
                    </div>
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="is_discount" :value="__('Is Discount')" />
                        <input id="is_discount" class="block mt-1 w-full" type="checkbox" name="is_discount"
                            wire:model="is_discount" />
                        <x-input-error :messages="$errors->get('is_discount')" for="is_discount" class="mt-2" />
                    </div>
                    <div class="sm:w-1/2 mb-4 px-2">
                        <x-label for="discount_date" :value="__('Discount Date')" />
                        <x-input id="discount_date" class="block mt-1 w-full" type="date" name="discount_date"
                            wire:model="discount_date" />
                        <x-input-error :messages="$errors->get('discount_date')" for="discount_date" class="mt-2" />
                    </div>
                </div>
                <div class="w-full px-3 flex justify-center">
                    <x-button primary type="submit" class="block w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
