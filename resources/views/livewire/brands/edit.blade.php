<div>
    <!-- Edit Modal -->
    <x-modal wire:model="editModal" name="editModal">
        <x-slot name="title">
            {{ __('Edit Brand') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit="update">

                <div class="w-full px-3 mb-4">
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" wire:model="form.name" />
                    <x-input-error :messages="$errors->get('form.name')" for="form.name" class="mt-2" />
                </div>
                <div class="w-full px-3 mb-4">
                    <x-label for="origin" :value="__('Origin')" />
                    <x-input id="origin" class="block mt-1 w-full" type="text" name="origin" wire:model="form.origin" />
                    <x-input-error :messages="$errors->get('form.origin')" for="form.origin" class="mt-2" />
                </div>

                <div class="w-full px-3 mb-4">
                    <x-label for="description" :value="__('Description')" />
                    <textarea id="description" class="block mt-1 w-full" type="text" name="description" wire:model="form.description"></textarea>
                    <x-input-error :messages="$errors->get('form.description')" for="form.description" class="mt-2" />
                </div>

                <div class="w-full px-3 mb-4">
                    <x-label for="image" :value="__('Image')" />
                    <x-media-upload title="{{ __('Image') }}" name="form.image" wire:model="form.image" :file="$form->image"
                        :preview="$form->image && is_string($form->image) ? asset('images/brands/' . $form->image) : null"
                        single types="PNG / JPEG / WEBP" fileTypes="image/*" />
                    <x-input-error :messages="$errors->get('form.image')" for="form.image" class="mt-2" />
                </div>

                <div class="w-full px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Edit Modal -->
</div>
