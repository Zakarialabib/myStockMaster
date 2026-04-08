<div>
    @section('title', __('Expense Category'))
    
    <x-page-container 
        :title="__('Expense Category List')"
        :breadcrumbs="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Expense Category List')]
        ]"
        :show-filters="true">
        
        <x-slot name="actions">
            <div class="flex justify-end space-x-2">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger" class="inline-flex">
                        <x-button primary type="button" class="text-white flex items-center">
                            <i class="fas fa-ellipsis-v"></i>
                        </x-button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link wire:click="$dispatch('importModal')" wire:loading.attr="disabled">
                            <i class="fas fa-upload mr-2"></i> {{ __('Excel Import') }}
                        </x-dropdown-link>
                        <x-dropdown-link wire:click="exportSelected" wire:loading.attr="disabled">
                            <i class="fas fa-download mr-2"></i> {{ __('Export PDF') }}
                        </x-dropdown-link>
                        <x-dropdown-link wire:click="downloadSelected" wire:loading.attr="disabled">
                            <i class="fas fa-download mr-2"></i> {{ __('Export Excel') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
                <x-button 
                    variant="primary" 
                    icon="fas fa-plus" 
                    wire:click="dispatchTo('expense-categories.create', 'createModal')">
                    {{ __('Create Expense Category') }}
                </x-button>
            </div>
        </x-slot>

        <x-slot name="filters">
            <x-datatable.filters 
                :per-page="$perPage" 
                :per-page-options="$paginationOptions" 
                :selected-count="$this->selectedCount" 
                :search="$search"
                search-placeholder="{{ __('Search expense categories...') }}"
                wire:model.live.perPage="perPage"
                wire:model.live.search="search"
                wire:click.deleteSelected="deleteSelected"
                wire:click.resetSelected="resetSelected"
                :can-delete="true"
            />
        </x-slot>

        <!-- Table Section -->
        <x-table class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <x-slot name="thead">
                <x-table.th>
                    <input type="checkbox" wire:model.live="selectPage" />
                </x-table.th>
                <x-table.th sortable :direction="$sortBy === 'name' ? $sortDirection : null" field="name" wire:click="sortingBy('name')">
                    {{ __('Name') }}
                </x-table.th>
                <x-table.th sortable :direction="$sortBy === 'description' ? $sortDirection : null" field="description" wire:click="sortingBy('description')">
                    {{ __('Description') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>

            <x-table.tbody>
            @forelse($expenseCategories as $expenseCategory)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $expenseCategory->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <x-table.td class="pr-0">
                        <input wire:model.live="selected" type="checkbox" value="{{ $expenseCategory->id }}" />
                    </x-table.td>
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
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </x-button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link wire:click="$dispatch('editModal',{ id : {{ $expenseCategory->id }}})" wire:loading.attr="disabled">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="delete({{ $expenseCategory->id }})" wire:confirm="{{ __('Are you sure you want to delete this record?') }}" wire:loading.attr="disabled">
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
                    <x-table.td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-receipt text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('No expense categories found') }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Get started by creating your first expense category') }}</p>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
            </x-table.tbody>
        </x-table>

        <div class="pt-3">
            {{ $expenseCategories->links() }}
        </div>

        <!-- Modals -->
        <livewire:expense-categories.edit />
        <livewire:expense-categories.create />
        
    </x-page-container>
</div>
