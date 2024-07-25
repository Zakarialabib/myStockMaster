<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Expense') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="expense.reference" :value="__('Reference')" />
                        <x-input wire:model.lazy="expense.reference" id="expense.reference" type="text" required />
                        <x-input-error :messages="$errors->get('expense.reference')" class="mt-2" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="expense.date" :value="__('Date')" />
                        <x-input-date wire:model.lazy="expense.date" id="expense.date" name="expense.date"
                            required />
                        <x-input-error :messages="$errors->get('expense.date')" class="mt-2" />
                    </div>

                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="expense.category_id" :value="__('Expense Category')" />
                        <select required
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="category_expense" name="category_expense" wire:model="expense.category_id">
                            @foreach ($this->expensecategories as $expensecategory)
                                <option value="{{ $expensecategory->id }}">
                                    {{ $expensecategory->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('expense.category_id')" class="mt-2" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="expense.warehouse_id" :value="__('Expense Category')" />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="warehouse_expense" name="warehouse_expense" wire:model="expense.warehouse_id">
                            <option value=""></option>
                            @foreach ($this->warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('expense.warehouse_id')" class="mt-2" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="expense.amount" :value="__('Amount')" required />
                        <x-input wire:model.lazy="expense.amount" id="expense.amount" type="text" required />
                        <x-input-error :messages="$errors->get('expense.amount')" class="mt-2" />
                    </div>
                    <div class="w-full px-4 mb-4">
                        <x-label for="expense.details" :value="__('Description')" />
                        <textarea
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            rows="6" wire:model.lazy="expense.details" id="expense.details"></textarea>
                        <x-input-error :messages="$errors->get('expense.details')" class="mt-2" />
                    </div>
                </div>
                <div class="w-full px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
