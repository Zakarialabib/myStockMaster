<div>
    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create Currency') }}
        </x-slot>

        <x-slot name="content">

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit="create">
                <div class="flex flex-wrap mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model="name" />
                        <x-input-error :messages="$errors->get('name')" for="name" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="code" :value="__('Code')" required />
                        <x-input id="code" class="block mt-1 w-full" type="text"
                            wire:model="code" />
                        <x-input-error :messages="$errors->get('code')" for="code" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="locale" :value="__('Locale')" required />
                        <x-input id="locale" class="block mt-1 w-full" type="text"
                            wire:model="locale" />
                        <x-input-error :messages="$errors->get('locale')" for="locale" class="mt-2" />
                    </div>
                </div>
                <div class="w-full px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
