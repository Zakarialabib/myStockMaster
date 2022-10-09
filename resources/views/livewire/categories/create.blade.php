<div>
    <!-- Create Modal -->
    <x-modal wire:model="createCategory">
        <x-slot name="title">
            {{ __('Create Category') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit.prevent="create">
                <div>
                    <x-input id="code" type="text" name="code"
                        wire:model.defer="category.code" hidden />

                    <div class="mt-4">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                            wire:model.defer="category.name" />
                        <x-input-error :messages="$errors->get('category.name')" for="category.name" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-end">
                        <x-button primary wire:click="create" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                        <x-button primary type="button" wire:click="$set('createCategory', false)">
                            {{ __('Cancel') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Create Modal -->
</div>
