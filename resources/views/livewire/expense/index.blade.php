<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            <button
                class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled"
                {{ $this->selectedCount ? '' : 'disabled' }}>
                {{ __('Delete Selected') }}
            </button>

            <button
                class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                type="button" wire:click="confirm('downloadSelected')" wire:loading.attr="disabled"
                {{ $this->selectedCount ? '' : 'disabled' }}>
                {{ __('Export Selected Excel') }}
            </button>
            <button
                class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                type="button" wire:click="confirm('downloadAll')" wire:loading.attr="disabled">
                {{ __('Export All Excel') }}
            </button>
            <button
                class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                type="button" wire:click="confirm('exportSelected')" wire:loading.attr="disabled"
                {{ $this->selectedCount ? '' : 'disabled' }}>
                {{ __('Export Selected PDF') }}
            </button>
            <button
                class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                type="button" wire:click="confirm('exportAll')" wire:loading.attr="disabled">
                {{ __('Export All PDF') }}
            </button>

        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <div class="w-full">
                <input wire:model="search"
                    class="w-full p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300"
                    placeholder="Search" />
            </div>
        </div>
    </div>

    <div wire:loading.delay>
        <div class="d-flex justify-content-center">
            <x-loading />
        </div>
    </div>


    <x-table>
        <x-slot name="thead">
            <x-table.th class="pr-0 w-8">
                <x-input type="checkbox" class="rounded-tl-md rounded-bl-md" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                {{ __('Id') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('expense_category_id')" :direction="$sorts['expense_category_id'] ?? null">
                {{ __('Expense Category') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Entry Date') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('amount')" :direction="$sorts['amount'] ?? null">
                {{ __('Amount') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('description')" :direction="$sorts['description'] ?? null">
                {{ __('Description') }}
            </x-table.th>
            <x-table.th />
        </x-slot>

        <x-table.tbody>
            @forelse ($expenses as $expense)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $expense->id }}">
                    <x-table.td class="pr-0">
                        <x-table.checkbox wire:model="selected" value="{{ $expense->id }}" />
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->id }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->expenseCategory->name ?? '' }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->date }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->amount }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->description }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button alert wire:click="showModal({{ $expense->id }})" wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="editModal({{ $expense->id }})" wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $expense->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="7">
                        <div class="flex justify-center items-center space-x-2">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"
                                    clip-rule="evenodd"></path>
                                <path fill-rule="evenodd"
                                    d="M10 4a1 1 0 100 2 1 1 0 000-2zm0 8a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span
                                class="font-medium py-8 text-gray-400 text-xl">{{ __('No expenses found...') }}</span>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="p-4">
        <div class="pt-3">
            @if ($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $expenses->links() }}
        </div>
    </div>

    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Expense Details') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col">
                <div class="flex flex-row">
                    <div class="flex flex-col w-1/2">
                        <x-label for="expense_category_id" :value="__('Expense Category')" />
                        <x-input wire:model="expense.expense_category_id" id="expense_category_id"
                            class="block mt-1 w-full" type="text" disabled />
                    </div>
                    <div class="flex flex-col w-1/2">
                        <x-label for="date" :value="__('Entry Date')" />
                        <x-input wire:model="expense.date" id="date" class="block mt-1 w-full" type="text"
                            disabled />
                    </div>
                </div>
                <x-button primary wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-button>
        </x-slot>

    </x-modal>


    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Expense') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap -mx-1">
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="expense.reference" :value="__('Reference')" />
                        <x-input wire:model="expense.reference" id="expense.reference" class="block mt-1 w-full"
                            type="text" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="expense.date" :value="__('Date')" />
                        <x-input wire:model="expense.date" id="expense.date" class="block mt-1 w-full"
                            type="date" />
                    </div>

                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="expense.expense_category_id" :value="__('Expense Category')" />
                        <x-select-list
                            class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                            required id="expense_category_id" name="expense_category_id"
                            wire:model="expense.expense_category_id" :options="$this->listsForFields['expensecategories']" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="expense.amount" :value="__('Amount')" required />
                        <x-input wire:model="expense.amount" id="expense.amount" class="block mt-1 w-full"
                            type="number" />
                    </div>
                    <div class="w-fullmb-4">
                        <x-label for="expense.details" :value="__('Description')" />
                        <textarea class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" rows="6"
                            wire:model="expense.details" id="expense.details"></textarea>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button secondary wire:click="$toggle('editModal')" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-button>
                        <x-button primary class="ml-4" wire:click="update" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

    <livewire:expense.create />

</div>

@push('page_scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', expenseId => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('delete', expenseId)
                    }
                })
            })
        })
    </script>
@endpush
