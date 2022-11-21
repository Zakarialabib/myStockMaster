<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap space-x-2 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @if ($selected)
                <x-button danger type="button" wire:click="$toggle('showDeleteModal')" wire:loading.attr="disabled">
                    <i class="fas fa-trash"></i>
                </x-button>
                <x-button success type="button" wire:click="downloadSelected" wire:loading.attr="disabled">
                    {{ __('EXCEL') }}
                </x-button>
                <x-button warning type="button" wire:click="exportSelected" wire:loading.attr="disabled">
                    {{ __('PDF') }}
                </x-button>
            @endif

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
            <x-table.th >
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('reference')" :direction="$sorts['reference'] ?? null">
                {{ __('Reference') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('category_id')" :direction="$sorts['category_id'] ?? null">
                {{ __('Expense Category') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Entry Date') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('amount')" :direction="$sorts['amount'] ?? null">
                {{ __('Amount') }}
            </x-table.th>

            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
            <x-table.th />
        </x-slot>

        <x-table.tbody>
            @forelse ($expenses as $expense)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $expense->id }}">
                    <x-table.td class="pr-0">
                        <input wire:model="selected" type="checkbox" value="{{ $expense->id }}" />
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->reference }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->category->name ?? '' }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->date }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->amount }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button info wire:click="showModal({{ $expense->id }})" 
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="editModal({{ $expense->id }})" 
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $expense->id }})"
                                type="button" wire:loading.attr="disabled">
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

    <div>
        <livewire:expense.create />
    </div>

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
                            <x-input wire:model="expense.reference" id="expense.reference" class="block mt-1 w-full"
                                type="text" />
                        </div>
                        <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                            <x-label for="expense.date" :value="__('Date')" />
                            <x-input-date wire:model="expense.date" id="expense.date" name="expense.date"
                                class="block mt-1 w-full" />
                        </div>

                        <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                            <x-label for="expense.category_id" :value="__('Expense Category')" />
                            <x-select-list
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                required id="category_id" name="category_id" wire:model="expense.category_id"
                                :options="$this->listsForFields['expensecategories']" />
                        </div>
                        <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                            <x-label for="expense.amount" :value="__('Amount')" required />
                            <x-input wire:model="expense.amount" id="expense.amount" class="block mt-1 w-full"
                                type="number" />
                        </div>
                        <div class="w-full px-4 mb-4">
                            <x-label for="expense.details" :value="__('Description')" />
                            <textarea
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                rows="6" wire:model="expense.details" id="expense.details"></textarea>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    </div>

    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Expense Details') }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full">
                <div class="flex flex-wrap">
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="category_id" :value="__('Expense Category')" />
                        <x-input wire:model="expense.category_id" id="category_id" class="block mt-1 w-full"
                            type="text" disabled />
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="date" :value="__('Entry Date')" />
                        <x-input wire:model="expense.date" id="date" class="block mt-1 w-full" type="text"
                            disabled />
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="reference" :value="__('Reference')" />
                        <x-input wire:model="expense.reference" id="reference" class="block mt-1 w-full"
                            type="text" disabled />
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="amount" :value="__('Amount')" />
                        <x-input wire:model="expense.amount" id="amount" class="block mt-1 w-full" type="text"
                            disabled />
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="details" :value="__('Description')" />
                        <x-input wire:model="expense.details" id="details" class="block mt-1 w-full"
                            type="text" disabled />
                    </div>
                </div>
            </div>
        </x-slot>
    </x-modal>

</div>

@push('scripts')
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
