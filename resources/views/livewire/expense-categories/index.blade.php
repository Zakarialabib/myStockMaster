<div>
    @section('title', __('Expense Category'))
    
    <x-page-container 
        :title="__('Expense Category List')"
        :breadcrumbs="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Expense Category List')]
        ]">
        
        <x-slot name="actions">
            <x-button 
                variant="primary" 
                icon="fas fa-plus" 
                wire:click="dispatchTo('expense-categories.create', 'createModal')">
                {{ __('Create Expense Category') }}
            </x-button>
        </x-slot>
        <!-- Controls Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 lg:gap-6">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Show') }}</label>
                        <x-input.select wire:model.live="perPage" size="sm" class="w-20">
                            @foreach ($paginationOptions as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </x-input.select>
                        <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('entries') }}</span>
                    </div>
                    @if ($selected)
                        <x-button 
                            variant="danger" 
                            icon="fas fa-trash" 
                            size="sm"
                            wire:click="deleteSelected">
                            {{ __('Delete Selected') }}
                        </x-button>
                    @endif
                    @if ($this->selectedCount)
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <i class="fas fa-info-circle text-purple-500 dark:text-purple-400 text-sm"></i>
                                <span class="text-sm text-purple-700 dark:text-purple-300">
                                    <span class="font-semibold">{{ $this->selectedCount }}</span> {{ __('selected') }}
                                </span>
                            </div>
                            <button wire:click="resetSelected" wire:loading.attr="disabled"
                                class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 cursor-pointer transition-colors">
                                {{ __('Clear') }}
                            </button>
                        </div>
                    @endif
                </div>
                <div class="w-full lg:w-80">
                    <x-input.text 
                        wire:model.live="search" 
                        placeholder="{{ __('Search expense categories...') }}" 
                        icon="fas fa-search"
                        autofocus />
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <x-table 
            :headers="[
                ['key' => 'checkbox', 'label' => '', 'sortable' => false],
                ['key' => 'name', 'label' => __('Name'), 'sortable' => true, 'icon' => 'fas fa-tag'],
                ['key' => 'description', 'label' => __('Description'), 'sortable' => false, 'icon' => 'fas fa-align-left'],
                ['key' => 'actions', 'label' => __('Actions'), 'sortable' => false, 'icon' => 'fas fa-cogs']
            ]"
            :show-checkbox="true"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            @forelse($expenseCategories as $expenseCategory)
                <tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $expenseCategory->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <x-table.td :checkbox="true" :value="$expenseCategory->id" wire:model.live="selected" />
                    <x-table.td>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <i class="fas fa-receipt text-purple-600 dark:text-purple-400 text-sm"></i>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $expenseCategory->name }}</span>
                        </div>
                    </x-table.td>
                    <x-table.td>
                        <p class="text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate" title="{{ $expenseCategory->description }}">
                            {{ $expenseCategory->description ?: __('No description provided') }}
                        </p>
                    </x-table.td>
                    <x-table.td :actions="true">
                        <div class="flex items-center gap-2">
                            <x-button 
                                variant="primary" 
                                icon="fas fa-edit" 
                                size="sm"
                                wire:click="$dispatch('editModal',{ id : {{ $expenseCategory->id }}})" 
                                wire:loading.attr="disabled" />
                            <x-button 
                                variant="danger" 
                                icon="fas fa-trash" 
                                size="sm"
                                wire:click="$dispatch('deleteModal', {id : {{ $expenseCategory->id }}})" 
                                wire:loading.attr="disabled" />
                        </div>
                    </x-table.td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-receipt text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('No expense categories found') }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Get started by creating your first expense category') }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>

        <!-- Pagination Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4 mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                @if ($this->selectedCount)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-purple-500 dark:text-purple-400"></i>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-purple-600 dark:text-purple-400">{{ $this->selectedCount }}</span>
                            {{ __('of') }} {{ $expenseCategories->total() }} {{ __('entries selected') }}
                        </p>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Showing') }} {{ $expenseCategories->firstItem() ?? 0 }} {{ __('to') }}
                        {{ $expenseCategories->lastItem() ?? 0 }} {{ __('of') }} {{ $expenseCategories->total() }}
                        {{ __('results') }}
                    </p>
                @endif
                <div class="flex justify-center sm:justify-end">
                    {{ $expenseCategories->links() }}
                </div>
            </div>
        </div>

        <!-- Modals -->
        <livewire:expense-categories.edit :expenseCategory="$expenseCategory" />
        <livewire:expense-categories.create />
        
    </x-page-container>
</div>