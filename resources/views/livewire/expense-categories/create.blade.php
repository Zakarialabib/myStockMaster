<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Create Expense Category') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit="create" class="space-y-4">
                <div>
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" type="text" class="block mt-1 w-full px-3" wire:model="form.name" />
                    <x-input-error :messages="$errors->first('form.name')" />
                </div>
                <div>
                    <x-label for="description" :value="__('Description')" />
                    <textarea id="description" class="block mt-1 w-full px-3" type="text" name="description"
                        wire:model="form.description"></textarea>
                    <x-input-error :messages="$errors->get('form.description')" for="form.description" class="mt-2" />
                </div>
                <div class="w-full">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
