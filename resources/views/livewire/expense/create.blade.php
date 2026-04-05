<div>
    <x-modal wire:model="createModal" name="createModal">
        <x-slot name="title">
            {{ __('Create Expense') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit="create" x-data="{ frequency: @entangle('form.frequency') }">
                <div class="flex flex-wrap mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="reference" :value="__('Reference')" required />
                        <x-input wire:model="form.reference" id="reference" class="block mt-1 w-full" type="text" required />
                        <x-input-error :messages="$errors->get('form.reference')" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="date" :value="__('Date')" required />
                        <x-input-date wire:model="form.date" id="date" name="date" type="date" required />
                        <x-input-error :messages="$errors->get('form.date')" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="category_id" :value="__('Expense Category')" required />
                        <select required
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="category_id" name="category_id" wire:model.live="form.category_id">
                            <option value="">{{ __('Select...') }}</option>
                            @foreach ($this->expenseCategories as $expensecategory)
                                <option value="{{ $expensecategory->id }}">
                                    {{ $expensecategory->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('form.category_id')" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="amount" :value="__('Amount')" required />
                        <x-input wire:model="form.amount" id="amount" class="block mt-1 w-full" type="text" required />
                        <x-input-error :messages="$errors->get('form.amount')" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="warehouse_id" :value="__('Warehouse')" required />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="warehouse_id" name="warehouse_id" wire:model.live="form.warehouse_id">
                            <option value="">{{ __('Select...') }}</option>
                            @foreach ($this->warehouses as $index => $warehouse)
                                <option value="{{ $index }}">{{ $warehouse }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('form.warehouse_id')" class="mt-2" />
                    </div>
                    <div x-show="frequency !== 'none'" class="w-full flex flex-wrap">
                        <div class="md:w-1/2 sm:w-full px-3">
                            <x-label for="start_date" :value="__('Start Date')" />
                            <x-input-date wire:model="form.start_date" id="start_date" class="block mt-1 w-full" type="date" />
                            <x-input-error :messages="$errors->get('form.start_date')" class="mt-2" />
                        </div>
                        <div class="md:w-1/2 sm:w-full px-3">
                            <x-label for="end_date" :value="__('End Date')" />
                            <x-input-date wire:model="form.end_date" id="end_date" class="block mt-1 w-full" type="date" />
                            <x-input-error :messages="$errors->get('form.end_date')" class="mt-2" />
                        </div>
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="frequency" :value="__('Frequency')" />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="frequency" name="frequency" wire:model.live="form.frequency">
                            <option value="none">{{ __('None') }}</option>
                            <option value="daily">{{ __('Daily') }}</option>
                            <option value="weekly">{{ __('Weekly') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                            <option value="yearly">{{ __('Yearly') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('form.frequency')" class="mt-2" />
                    </div>
                    <div class="w-full px-3">
                        <x-label for="document" :value="__('Document')" />
                        <x-input wire:model="form.document" id="document" class="block mt-1 w-full" type="file" />
                        <x-input-error :messages="$errors->get('form.document')" class="mt-2" />
                    </div>
                    <div class="w-full px-3">
                        <x-label for="description" :value="__('Description')" />
                        <textarea
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            rows="2" wire:model="form.description" id="description"></textarea>
                        <x-input-error :messages="$errors->get('form.description')" class="mt-2" />
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
