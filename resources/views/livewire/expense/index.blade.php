<div>
    @section('title', __('Expenses'))
    <x-theme.breadcrumb :title="__('Expenses List')" :parent="route('expenses.index')" :parentName="__('Expenses List')">
        <x-button primary type="button" wire:click="dispatchTo('expense.create', 'createModal')">
            {{ __('Create Expense') }}
        </x-button>
    </x-theme.breadcrumb>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap gap-6 w-full items-center">
            <select wire:model.live="perPage"
                class="w-auto shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm border-gray-300 rounded-md focus:outline-none focus:shadow-outline-blue transition duration-150 ease-in-out">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($selected)
                <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                    <i class="fas fa-trash"></i>
                </x-button>
                <x-button success type="button" wire:click="downloadSelected" wire:loading.attr="disabled">
                    {{ __('EXCEL') }}
                </x-button>
                <x-button warning type="button" wire:click="exportSelected" wire:loading.attr="disabled">
                    {{ __('PDF') }}
                </x-button>
            @endif
            @if ($this->selectedCount)
                <p class="text-sm  my-auto">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full ">
            <x-input wire:model.live="search" placeholder="{{ __('Search') }}" autofocus />
        </div>
    </div>


    <div class="grid gap-4 grid-cols-2 justify-center mb-2">
        <div class="w-full flex flex-wrap">
            <div class="w-full md:w-1/2 px-2">
                <label>{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                <x-input wire:model.live="startDate" type="date" name="startDate" value="$startDate" />
                @error('startDate')
                    <span class="text-danger mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full md:w-1/2 px-2">
                <label>{{ __('End Date') }} <span class="text-red-500">*</span></label>
                <x-input wire:model.live="endDate" type="date" name="endDate" value="$endDate" />
                @error('endDate')
                    <span class="text-danger mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="gap-2 inline-flex items-center mx-0 px-2">
            <x-button type="button" primary wire:click="filterByType('day')">{{ __('Today') }}</x-button>
            <x-button type="button" info wire:click="filterByType('month')">{{ __('This Month') }}</x-button>
            <x-button type="button" warning wire:click="filterByType('year')">{{ __('This Year') }}</x-button>
        </div>
    </div>
    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model.live="selectPage" />
            </x-table.th>
            <x-table.th sortable :direction="$sorts['user_id'] ?? null" field="user_id" wire:click="sortingBy('user_id')">
                {{ __('User') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['reference'] ?? null" field="reference" wire:click="sortingBy('reference')">
                {{ __('Reference') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['category_id'] ?? null" field="category_id" wire:click="sortingBy('category_id')">
                {{ __('Expense Category') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['warehouse_id'] ?? null" field="warehouse_id" wire:click="sortingBy('warehouse_id')">
                {{ __('Warehouse') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['date'] ?? null" field="date" wire:click="sortingBy('date')">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['amount'] ?? null" field="amount" wire:click="sortingBy('amount')">
                {{ __('Amount') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($expenses as $expense)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $expense->id }}">
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
                            <x-button info wire:click="showModal('{{ $expense->id }}')" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary
                                wire:click="dispatchTo('expense.edit','editModal',{ id : {{ $expense->id }}})"
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="deleteModal('{{ $expense->id }}')" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
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

    <livewire:expense.edit :expense="$expense" />

    <livewire:expense.create />

    <livewire:cash-register.create />

    <x-modal wire:model="showModal">
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
                        <x-label for="description" :value="__('Description')" />
                        {{ $this->expense?->description }}
                    </div>
                </div>
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:model="importModal">
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

                    {{-- <x-table-responsive>
                        <x-table.tr>
                            <x-table.th>{{ __('Name') }}</x-table.th>
                            <x-table.td>{{ __('Required') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Phone') }}</x-table.th>
                            <x-table.td>{{ __('Required') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Email') }}</x-table.th>
                            <x-table.td>{{ __('Optional') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Address') }}</x-table.th>
                            <x-table.td>{{ __('Optional') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('City') }}</x-table.th>
                            <x-table.td>{{ __('Optional') }}</x-table.td>
                        </x-table.tr>
                        <x-table.tr>
                            <x-table.th>{{ __('Tax Number') }}</x-table.th>
                            <x-table.td>{{ __('Optional') }}</x-table.td>
                        </x-table.tr>
                    </x-table-responsive> --}}

                    <div class="w-full flex justify-start">
                        <x-button primary type="submit" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

</div>
