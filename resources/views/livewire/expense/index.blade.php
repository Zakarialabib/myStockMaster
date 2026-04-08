<div>
    @section('title', __('Expenses'))

    <x-page-container :title="__('Expenses List')" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Expenses List'), 'url' => route('expenses.index')]]" :show-filters="true">

        <x-slot name="actions">
            <x-dropdown align="right" width="48" class="w-auto mr-2">
                <x-slot name="trigger" class="inline-flex">
                    <x-button secondary type="button" class="text-white flex items-center">
                        <i class="fas fa-angle-double-down w-4 h-4"></i>
                    </x-button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link wire:click="$dispatch('importModal')" wire:loading.attr="disabled">
                        {{ __('Excel Import') }}
                    </x-dropdown-link>
                    <x-dropdown-link wire:click="exportSelected" wire:loading.attr="disabled">
                        {{ __('Export PDF') }}
                    </x-dropdown-link>
                    <x-dropdown-link wire:click="downloadSelected" wire:loading.attr="disabled">
                        {{ __('Export Excel') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>

            <x-button primary type="button" wire:click="dispatchTo('expense.create', 'createModal')">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Create Expense') }}
            </x-button>
        </x-slot>

        <x-slot name="filters">
            <x-datatable.filters 
                :per-page="$perPage" 
                :per-page-options="$paginationOptions" 
                :selected-count="$this->selectedCount" 
                :search="$search"
                search-placeholder="{{ __('Search...') }}"
                wire:model.live.perPage="perPage"
                wire:model.live.search="search"
                wire:click.deleteSelected="deleteSelected"
                wire:click.resetSelected="resetSelected"
                :can-delete="true"
            >
                <x-slot name="extraFilters">
                    <div class="flex items-center gap-2">
                        <x-input wire:model.live="startDate" type="date" name="startDate" />
                        <x-input wire:model.live="endDate" type="date" name="endDate" />
                        <x-button type="button" primary size="sm" wire:click="filterByType('day')">{{ __('Today') }}</x-button>
                        <x-button type="button" info size="sm" wire:click="filterByType('month')">{{ __('This Month') }}</x-button>
                        <x-button type="button" warning size="sm" wire:click="filterByType('year')">{{ __('This Year') }}</x-button>
                    </div>
                </x-slot>
            </x-datatable.filters>
        </x-slot>

        <x-table class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model.live="selectPage" />
            </x-table.th>
            <x-table.th sortable :direction="$sortBy === 'user_id' ? $sortDirection : null" field="user_id" wire:click="sortingBy('user_id')">
                 {{ __('User') }}
             </x-table.th>
             <x-table.th sortable :direction="$sortBy === 'reference' ? $sortDirection : null" field="reference" wire:click="sortingBy('reference')">
                 {{ __('Reference') }}
             </x-table.th>
             <x-table.th sortable :direction="$sortBy === 'category_id' ? $sortDirection : null" field="category_id" wire:click="sortingBy('category_id')">
                 {{ __('Expense Category') }}
             </x-table.th>
             <x-table.th sortable :direction="$sortBy === 'warehouse_id' ? $sortDirection : null" field="warehouse_id" wire:click="sortingBy('warehouse_id')">
                 {{ __('Warehouse') }}
             </x-table.th>
             <x-table.th sortable :direction="$sortBy === 'date' ? $sortDirection : null" field="date" wire:click="sortingBy('date')">
                 {{ __('Date') }}
             </x-table.th>
             <x-table.th sortable :direction="$sortBy === 'amount' ? $sortDirection : null" field="amount" wire:click="sortingBy('amount')">
                 {{ __('Amount') }}
             </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($expenses as $expense)
                <x-table.tr wire:key="row-{{ $expense->id }}">
                    <x-table.td class="pr-0">
                        <input wire:model.live="selected" type="checkbox" value="{{ $expense->id }}" />
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->user->name ?? '' }}
                    </x-table.td>
                    <x-table.td>
                        <button type="button" wire:click="showModal({{ $expense->id }})">
                            {{ $expense->reference }}
                        </button>
                    </x-table.td>
                    <x-table.td>
                        <x-badge type="info">
                            <small>{{ $expense->category?->name ?? '' }}</small>
                        </x-badge>
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->warehouse?->name ?? '' }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->date }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($expense->amount) }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </x-button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link wire:click="showModal('{{ $expense->id }}')" wire:loading.attr="disabled">
                                        <i class="fas fa-eye"></i>
                                        {{ __('Show') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="dispatchTo('expense.edit','editModal',{ id : {{ $expense->id }}})" wire:loading.attr="disabled">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="deleteModal('{{ $expense->id }}')" wire:loading.attr="disabled">
                                        <i class="fas fa-trash"></i>
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="8">
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

    <div class="pt-3">
        {{ $expenses->links() }}
    </div>

    <livewire:expense.edit />

    <livewire:expense.create />

    <livewire:cash-register.create />

    <x-modal wire:model="showModal" name="showModal">
        <x-slot name="title">
            {{ __('Expense Details') }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full">
                <div class="flex flex-wrap">
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="category_id" :value="__('Expense Category')" />
                        {{ $this->expense?->category?->name }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="category_id" :value="__('Warehouse')" />
                        {{ $this->expense?->warehouse?->name }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="date" :value="__('Entry Date')" />
                        {{ $this->expense?->date }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="reference" :value="__('Reference')" />
                        {{ $this->expense?->reference }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="amount" :value="__('Amount')" />
                        {{ $this->expense?->amount }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="start_date" :value="__('Start Date')" />
                        {{ $this->expense?->start_date }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="end_date" :value="__('End Date')" />
                        {{ $this->expense?->end_date }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="frequency" :value="__('Frequency')" />
                        {{ $this->expense?->frequency }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="description" :value="__('Description')" />
                        {{ $this->expense?->description }}
                    </div>
                </div>
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:model="importModal" name="importModal">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                {{ __('Import Excel') }}
                <x-button primary wire:click="downloadSample" type="button">
                    {{ __('Download Sample') }}
                </x-button>
            </div>
        </x-slot>

        <x-slot name="content">
            <form wire:submit="importExcel">
                <div class="space-y-4">
                    <div class="mt-4">
                        <x-label for="file" :value="__('Import')" />
                        <x-input id="file" class="block mt-1 w-full" type="file" name="file"
                            wire:model="file" />
                        <x-input-error :messages="$errors->get('file')" for="file" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-start">
                        <x-button primary type="submit" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    </x-page-container>
</div>
