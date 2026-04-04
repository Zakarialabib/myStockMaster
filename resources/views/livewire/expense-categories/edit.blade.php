<div>
    <!-- Edit Modal -->
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Edit Expense Category') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit="update" class="space-y-4">
                <div>
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" type="text" class="block mt-1 w-full" wire:model="name" />
                    <x-input-error :messages="$errors->first('name')" />
                </div>
                <div>
                    <x-label for="description" :value="__('Description')" />
                    <textarea id="description" class="block mt-1 w-full" type="text" name="description"
                        wire:model="description"></textarea>
                    <x-input-error :messages="$errors->get('description')" for="description" class="mt-2" />
                </div>
                <div class="w-full">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Edit Modal -->
</div>
