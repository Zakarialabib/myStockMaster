<div>
    <!-- Edit Modal -->
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Customer Group') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full px-3 mb-4">
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" type="text" class="block mt-1 w-full" wire:model.lazy="customergroup.name" />
                    <x-input-error :messages="$errors->first('customergroup.name')" />
                </div>
                <div class="w-full px-3 mb-4">
                    <x-label for="percentage" :value="__('Percentage')" />
                    <x-input id="percentage" class="block mt-1 w-full" type="text" name="percentage"
                        wire:model.lazy="customergroup.percentage" />
                    <x-input-error :messages="$errors->get('customergroup.percentage')" for="percentage" class="mt-2" />
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
