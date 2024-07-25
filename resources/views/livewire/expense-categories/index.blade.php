<div>
    @section('title', __('Expense Category'))
    <x-theme.breadcrumb :title="__('Expense Category List')" :parent="route('expense-categories.index')" :parentName="__('Expense Category List')">
        <x-button primary type="button" wire:click="dispatchTo('expense-categories.create', 'createModal')">
            {{ __('Create Expense Category') }}
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

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input wire:model.live="selectPage" type="checkbox" />
            </x-table.th>
            <x-table.th sortable :direction="$sorts['name'] ?? null" field="name" wire:click="sortingBy('name')">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th>
                {{ __('Description') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse($expenseCategories as $expenseCategory)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $expenseCategory->id }}">
                    <x-table.td>
                        <input type="checkbox" value="{{ $expenseCategory->id }}" wire:model.live="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $expenseCategory->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expenseCategory->description }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button primary type="button"
                                wire:click="$dispatch('editModal',{ id : {{ $expenseCategory->id }}})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger type="button"
                                wire:click="$dispatch('deleteModal', {id : {{ $expenseCategory->id }}})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="4">
                        <div class="flex justify-center">
                            {{ __('No Expense Categories found.') }}
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="pt-3">
        @if ($this->selectedCount)
            <p class="text-sm leading-5">
                <span class="font-medium">
                    {{ $this->selectedCount }}
                </span>
                {{ __('Entries selected') }}
            </p>
        @endif
        {{ $expenseCategories->links() }}
    </div>

    <livewire:expense-categories.edit :expenseCategory="$expenseCategory" />

    <livewire:expense-categories.create />

</div>
