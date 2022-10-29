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
                    <x-input id="code" type="hidden" name="code"
                        wire:model="code" />

                    <div class="my-4">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                            wire:model="name" />
                        <x-input-error :messages="$errors->get('name')" for="name" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-start space-x-2">
                        <x-button primary wire:click="create" wire:loading.attr="disabled" type="button">
                            {{ __('Create') }}
                        </x-button>

                        <x-button secondary type="button" wire:loading.attr="disabled" wire:click="$set('createCategory', false)">
                            {{ __('Cancel') }}
                        </x-button>

                        <span class="sr-only" wire:loading wire:target="create">
                            {{ __('Creating...') }}
                        </span>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Create Modal -->
</div>
