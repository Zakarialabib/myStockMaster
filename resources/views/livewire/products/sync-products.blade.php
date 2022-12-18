<div>
    <!-- Create Modal -->
    <x-modal wire:model="syncModal">
        <x-slot name="title">
            {{ __('Sync Your Online Store with Products') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="sync">
                <div class="space-y-4">
                    <div class="mt-4">
                        <x-label for="type" :value="__('Type')" />
                        <select wire:model="type" id="type" name="type"
                            class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500">
                            <option value="shopify">{{ 'Shopify' }}</option>
                            <option value="woocommerce">{{ 'Woocommerce' }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" for="type" class="mt-2" />
                    </div>

                    {{-- display store url --}}

                    <div class="mt-4">
                        <x-label for="type" :value="__('Type')" />
                        <x-input id="store_url" class="block mt-1 w-full" type="text" name="store_url"
                            wire:model="store_url" required />
                        <x-input-error :messages="$errors->get('store_url')" for="store_url" class="mt-2" />
                    </div>


                    <div class="w-full flex justify-start px-3">
                        <x-button primary wire:click="sync" wire:loading.attr="disabled">
                            {{ __('Sync') }}
                        </x-button>
                    </div>

                    {{-- Display Status informations --}}

                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
