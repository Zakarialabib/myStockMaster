<div>
    <!-- Create Modal -->
    <x-modal wire:model="createModal" name="createModal">
        <x-slot name="title">
            {{ __('Create Category') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit="create">
                <div>
                    <div class="my-4">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" autofocus
                            wire:model="name" />
                        <x-input-error :messages="$errors->get('name')" for="name" class="mt-2" />
                    </div>
                    <div class="my-4">
                        <x-label for="description" :value="__('Description')" />
                        <x-input id="description" class="block mt-1 w-full" type="text" name="description"
                            wire:model="description" />
                        <x-input-error :messages="$errors->get('description')" for="description" class="mt-2" />
                    </div>

                    <div class="w-full px-3 mb-4">
                        <x-label for="image" :value="__('Image')" />
                        <x-media-upload title="{{ __('Image') }}" name="image" wire:model="image" :file="$image"
                            single types="PNG / JPEG / WEBP" fileTypes="image/*" />
                        <x-input-error :messages="$errors->get('image')" for="image" class="mt-2" />
                    </div>
                    <div class="w-full">
                        <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Create Modal -->
</div>
