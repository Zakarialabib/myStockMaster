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
                <input wire:model="search" class="w-full p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300"
                    placeholder="Search" />
            </div>
        </div>
    </div>

    <div wire:loading.delay>
        Loading...
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th class="pr-0 w-8">
                <x-table.input type="checkbox" class="rounded-tl-md rounded-bl-md" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                {{ __('Id') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('expense_category_id')" :direction="$sorts['expense_category_id'] ?? null">
                {{ __('Expense Category') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('entry_date')" :direction="$sorts['entry_date'] ?? null">
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

        <x-slot name="tbody">
            @if ($selectPage)
                <x-table.tr class="bg-blue-100 dark:bg-dark-eval-1" wire:key="row-message">
                    <x-table.th colspan="7">
                        @unless ($selectAll)
                            <div>
                                <span>{{__('You have selected')}} <strong>{{ $expenses->count() }}</strong> {{__('expenses, do you want to select all')}} <strong>{{ $expenses->total() }}</strong>?</span>
                                <x-primary-button wire:click="selectAll" class="ml-1">{{__('Select All')}}</x-primary-button>
                            </div>
                        @else
                            <span>{{__('You are currently selecting all')}} <strong>{{ $expenses->total() }}</strong> {{__('expenses')}}.</span>
                        @endif
                    </x-table.th>
                </x-table.tr>
            @endif

            @forelse ($expenses as $expense)
            <x-table.tr wire:key="row-{{ $expense->id }}">
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
                    {{ $expense->entry_date }}
                </x-table.td>
                <x-table.td>
                    {{ $expense->amount }}
                </x-table.td>
                <x-table.td>
                    {{ $expense->description }}
                </x-table.td>
                <x-table.td class="whitespace-no-wrap row-action--icon">
                    <a href="{{ route('admin.expenses.show', $expense) }}" class="mr-3"><i class="fa fa-fw fa-eye text-blue-500"></i></a>
                    <x-primary-button wire:click="showModal({{ $expense->id }})">
                        {{ __('Show') }}
                    </x-primary-button>
                    <a href="{{ route('admin.expenses.edit', $expense) }}" class="mr-3"><i class="fa fa-fw fa-pen text-blue-500"></i></a>
                    <a href="#" wire:click="confirm('delete', {{ $expense->id }})" wire:loading.attr="disabled"><i class="fa fa-fw fa-trash text-blue-500"></i></a>
                </x-table.td>
            </x-table.tr>
            @empty
            <x-table.tr>
                <x-table.td colspan="7">
                    <div class="flex justify-center items-center space-x-2">
                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd"></path>
                            <path fill-rule="evenodd" d="M10 4a1 1 0 100 2 1 1 0 000-2zm0 8a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium py-8 text-gray-400 text-xl">{{ __('No expenses found...') }}</span>
                    </div>
                </x-table.td>
            </x-table.tr>
            @endforelse
        </x-slot>
    </x-table>

    <div class="px-6 py-3">
        {{ $expenses->links() }}
    </div>
</div>


<x-jet-dialog-modal wire:model="showModal">
    <x-slot name="title">
        {{ __('Expense Details') }}
    </x-slot>

    <x-slot name="content">
        <div class="mt-4">
            <x-jet-label for="expense_category_id" value="{{ __('Expense Category') }}" />
            <x-jet-input id="expense_category_id" class="block mt-1 w-full" type="text" name="expense_category_id" :value="$expense->expenseCategory->name ?? ''" required autofocus autocomplete="expense_category_id" />
        </div>

        <div class="mt-4">
            <x-jet-label for="entry_date" value="{{ __('Entry Date') }}" />
            <x-jet-input id="entry_date" class="block mt-1 w-full" type="text" name="entry_date" :value="$expense->entry_date" required autofocus autocomplete="entry_date" />
        </div>

        <div class="mt-4">
            <x-jet-label for="amount" value="{{ __('Amount') }}" />
            <x-jet-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="$expense->amount" required autofocus autocomplete="amount" />
        </div>

        <div class="mt-4">
            <x-jet-label for="description" value="{{ __('Description') }}" />
            <x-jet-input id="description" class="block mt-1 w-full" type="text" name="description" :value="$expense->description" required autofocus autocomplete="description" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
            {{ __('Close') }}
        </x-jet-secondary-button>
    </x-slot>
</x-jet-dialog-modal>


@push('page_scripts')
    <script>
        window.addEventListener('confirmDelete', event => {
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
                    window.livewire.emit('delete', event.detail.id)
                }
            })
        })
    </script>
@endpush
            
