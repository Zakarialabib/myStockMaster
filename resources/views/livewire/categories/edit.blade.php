<div>
    <!-- Edit Modal -->
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Category') }} {{ $category?->name }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="update">
                <div class="mb-6">
                    <div class="mt-4 w-full">
                        <x-label for="code" :value="__('Code')" />
                        <x-input id="code" class="block mt-1 w-full" type="text" name="code" disabled
                            wire:model.lazy="category.code" />
                        <x-input-error :messages="$errors->get('category.code')" for="code" class="mt-2" />
                    </div>
                    <div class="my-4 p w-full">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                            wire:model.lazy="category.name" />
                        <x-input-error :messages="$errors->get('category.name')" for="name" class="mt-2" />
                    </div>
                    <div class="w-full">
                        <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Edit Modal -->
</div>
