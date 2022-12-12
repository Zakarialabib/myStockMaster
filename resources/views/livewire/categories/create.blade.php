<div>
    <!-- Create Modal -->
    <x-modal wire:model="createCategory">
        <x-slot name="title">
            {{ __('Create Category') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit.prevent="create">
                <div>
                    <x-input id="code" type="hidden" name="code"
                        wire:model="code" />

                    <div class="my-4">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" autofocus
                            wire:model.lazy="name"  />
                        <x-input-error :messages="$errors->get('name')" for="name" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-start">
                        <x-button primary type="submit" class="w-full text-center"  wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Create Modal -->
</div>
