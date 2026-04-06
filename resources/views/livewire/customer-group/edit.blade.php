<div>
    <!-- Edit Modal -->
    <x-modal wire:model="editModal" name="editModal">
        <x-slot name="title">
            {{ __('Edit Customer Group') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit="update">
                <div class="w-full px-3 mb-4">
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" type="text" class="block mt-1 w-full" wire:model="form.name" />
                    <x-input-error :messages="$errors->first('form.name')" />
                </div>
                <div class="w-full px-3 mb-4">
                    <x-label for="percentage" :value="__('Percentage')" />
                    <x-input id="percentage" class="block mt-1 w-full" type="text" name="percentage"
                        wire:model="form.percentage" />
                    <x-input-error :messages="$errors->get('form.percentage')" for="form.percentage" class="mt-2" />
                </div>
                <div class="w-full px-3 py-2">
                    <x-button primary type="submit" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Edit Modal -->
</div>
