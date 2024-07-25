<div>
    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create Expense') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="reference" :value="__('Reference')" required />
                        <x-input wire:model.lazy="expense.reference" id="reference" class="block mt-1 w-full" type="text"
                            required />
                        <x-input-error :messages="$errors->get('expense.reference')" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="date" :value="__('Date')" required />
                        <x-input-date wire:model.lazy="expense.date" name="date" type="date" required />
                        <x-input-error :messages="$errors->get('expense.date')" class="mt-2" />
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="category_expense" :value="__('Expense Category')" required />
                        <select required
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="category_expense" name="category_expense" wire:model="expense.category_id">
                            <option value="">
                                {{ __('Select Expense Category') }}
                            </option>
                            @foreach ($this->expensecategories as $expensecategory)
                                <option value="{{ $expensecategory->id }}">
                                    {{ $expensecategory->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('expense.category_id')" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="amount" :value="__('Amount')" required />
                        <x-input wire:model.lazy="expense.amount" id="amount" class="block mt-1 w-full"
                            type="text" required />
                        <x-input-error :messages="$errors->get('expense.amount')" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="warehouse_expense" :value="__('Warehouse')" required />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="warehouse_expense" name="warehouse_expense" wire:model="expense.warehouse_id">
                            <option value="">
                                {{ __('Select Warehouse') }}
                            </option>
                            @foreach ($this->warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('expense.warehouse_id')" class="mt-2" />
                    </div>
                    <div class="w-full px-3">
                        <x-label for="details" :value="__('Description')" />
                        <textarea
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            rows="2" wire:model.defer="expense.details" id="details"></textarea>
                        <x-input-error :messages="$errors->get('expense.details')" class="mt-2" />
                    </div>
                </div>
                <div class="w-full pb-2 px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
