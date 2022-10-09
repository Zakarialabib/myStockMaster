<div>
    <x-modal wire:model="createExpense">
        <x-slot name="title">
            {{ __('Create Expense') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-1">
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="expense.reference" :value="__('Reference')" />
                        <x-input wire:model="expense.reference" id="expense.reference" class="block mt-1 w-full"
                            type="text" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="expense.date" :value="__('Date')" />
                        <x-input wire:model="expense.date" id="expense.date" class="block mt-1 w-full" type="date" />
                    </div>

                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="expense.expense_category_id" :value="__('Expense Category')" />
                        <x-select-list
                            class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                            required id="permissions" name="permissions" wire:model="permissions" :options="$this->listsForFields['expensecategories']" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="expense.amount" :value="__('Amount')" required />
                        <x-input wire:model="expense.amount" id="expense.amount" class="block mt-1 w-full"
                            type="number" />
                    </div>
                    <div class="w-full mb-4">
                        <x-label for="expense.details" :value="__('Description')" />
                        <textarea class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" rows="6"
                            wire:model="expense.details" id="expense.details"></textarea>
                    </div>
                </div>
                <div class="w-full flex justify-end space-x-2">
                    <x-button primary wire:click="create" wire:loading.attr="disabled">
                        {{ __('Create') }}
                    </x-button>
                    <x-button secondary wire:click="$set('createExpense', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>

</div>
