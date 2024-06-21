<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Expense') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit="update">
                <div class="flex flex-wrap mb-3">
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="reference" :value="__('Reference')" />
                        <x-input wire:model="reference" id="reference" type="text" required />
                        <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="date" :value="__('Date')" />
                        <x-input-date wire:model="date" id="date" name="date" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="category_id" :value="__('Expense Category')" />
                        <select required
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="category_expense" name="category_expense" wire:model="category_id">
                            @foreach ($this->expenseCategories as $expensecategory)
                                <option value="{{ $expensecategory->id }}">
                                    {{ $expensecategory->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="warehouse_id" :value="__('Expense Category')" />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="warehouse_expense" name="warehouse_expense" wire:model="warehouse_id">
                            <option value=""></option>
                            @foreach ($this->warehouses as $index => $warehouse)
                                <option value="{{ $index }}" @if ($warehouse_id == $index) selected @endif>
                                    {{ $warehouse }}</option>
                                {{ $warehouse }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="amount" :value="__('Amount')" required />
                        <x-input wire:model="amount" id="amount" type="text" required />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>
                    <div class="w-full px-4 mb-4">
                        <x-label for="description" :value="__('Description')" />
                        <textarea
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            rows="6" wire:model="description" id="description"></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
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
