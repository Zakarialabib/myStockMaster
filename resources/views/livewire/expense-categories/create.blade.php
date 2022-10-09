<div>
    <x-modal wire:model="createExpenseCategory">
        <x-slot name="title">
            {{ __('Create Expense Category') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="create">
                <div class="flex flex-wrap justify-center">
                    <div class="w-full">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" type="text" class="block mt-1 w-full"
                            wire:model="expenseCategory.name" />
                        <x-input-error :messages="$errors->first('expenseCategory.name')" />
                    </div>
                    <div class="w-full">
                        <x-label for="description" :value="__('Description')" />
                        <x-input id="description" type="text" class="block mt-1 w-full"
                            wire:model="expenseCategory.description" />
                        <x-input-error :messages="$errors->first('expenseCategory.description')" />
                    </div>
                    <div class="w-full flex justify-end space-x-2">
                        <x-button primary wire:click="create" wire:loading.attr="disabled">
                            {{ __('Create') }}
                        </x-button>
                        <x-button secondary wire:click="$set('createExpenseCategory', false)"
                            wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
