<div>
    @section('title', __('Categories'))

    <x-page-container :title="__('Categories List')" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Categories List')]]" :show-filters="true">

        <x-slot name="actions">
            @can('category_import')
                <x-button variant="primary" icon="fas fa-upload" wire:click="dispatchTo('categories.import', 'importModal')">
                    {{ __('Import Category') }}
                </x-button>
            @endcan
            @can('category_create')
                <x-button variant="primary" icon="fas fa-plus" wire:click="dispatchTo('categories.create', 'createModal')">
                    {{ __('Create Category') }}
                </x-button>
            @endcan
        </x-slot>
        <x-slot name="filters">
            <x-datatable.filters :per-page="$perPage" :pagination-options="$paginationOptions" :selected-count="$this->selectedCount" :search="$search"
                search-placeholder="{{ __('Search categories...') }}" wire:model.live.perPage="perPage"
                wire:model.live.search="search" wire:click.deleteSelected="deleteSelected"
                wire:click.resetSelected="resetSelected" :can-delete="auth()->user()->can('category_delete')" />
        </x-slot>

        <x-table class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <x-slot name="thead">
                <x-table.th class="w-12">
                    <input type="checkbox" wire:model.live="selectPage"
                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-700" />
                </x-table.th>
                <x-table.th sortable wire:click="sortBy('name')" :direction="$sortBy === 'name' ? $sortDirection : null">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-tag mr-2 text-gray-400"></i>
                        <span>{{ __('Name') }}</span>
                    </div>
                </x-table.th>
                <x-table.th>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-boxes mr-2 text-gray-400"></i>
                        <span>{{ __('Products count') }}</span>
                    </div>
                </x-table.th>
                <x-table.th sortable wire:click="sortBy('status')" :direction="$sortBy === 'status' ? $sortDirection : null">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-toggle-on mr-2 text-gray-400"></i>
                        <span>{{ __('Status') }}</span>
                    </div>
                </x-table.th>
                <x-table.th class="text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <i class="fas fa-cogs mr-2 text-gray-400"></i>
                        <span>{{ __('Actions') }}</span>
                    </div>
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse($categories as $category)
                <x-table.tr data-loading wire:key="row-{{ $category->id }}"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <x-table.td :checkbox="true" :value="$category->id" wire:model.live="selected" />
                    <x-table.td>
                        <button type="button" wire:click="openShowModal('{{ $category->id }}')"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors">
                            {{ $category->name }}
                        </button>
                    </x-table.td>
                    <x-table.td>
                        <x-status-badge status="info" :count="$category->products_count" icon="fas fa-boxes" size="sm" />
                    </x-table.td>
                    <x-table.td>
                        <x-status-badge :status="$category->status ? 'active' : 'inactive'" :text="$category->status ? __('Active') : __('Inactive')" size="sm" />
                    </x-table.td>
                    <x-table.td class="text-right">
                        <x-button variant="primary" type="button" class="w-8 h-8 inline-flex items-center justify-center"
                            wire:click="openShowModal('{{ $category->id }}')">
                            <i class="fas fa-eye"></i>
                        </x-button>
                        <x-button variant="primary" type="button" class="w-8 h-8 inline-flex items-center justify-center"
                            wire:click="openEditModal('{{ $category->id }}')">
                            <i class="fas fa-edit"></i>
                        </x-button>
                        <x-button variant="primary" type="button" class="w-8 h-8 inline-flex items-center justify-center"
                            wire:click="openDeleteModal('{{ $category->id }}')">
                            <i class="fas fa-trash-alt"></i>
                        </x-button>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                {{ __('No categories found') }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Get started by creating your first category') }}</p>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
            </x-table.tbody>
        </x-table>

        <!-- Pagination Section -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4 mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                @if ($this->selectedCount)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-blue-500 dark:text-blue-400"></i>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span
                                class="font-semibold text-blue-600 dark:text-blue-400">{{ $this->selectedCount }}</span>
                            {{ __('of') }} {{ $categories->total() }} {{ __('entries selected') }}
                        </p>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Showing') }} {{ $categories->firstItem() ?? 0 }} {{ __('to') }}
                        {{ $categories->lastItem() ?? 0 }} {{ __('of') }} {{ $categories->total() }}
                        {{ __('results') }}
                    </p>
                @endif
                <div class="flex justify-center sm:justify-end">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

        <!-- Show Modal -->
        <x-modal wire:model="showModal" class="sm:max-w-lg">
            <x-slot name="title">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <i class="fas fa-tag text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ __('Category Details') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $category?->name }}</p>
                    </div>
                </div>
            </x-slot>

            <x-slot name="content">
                <div class="space-y-6">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-barcode mr-2 text-gray-400"></i>
                                    {{ __('Category Code') }}
                                </label>
                                <p
                                    class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 px-3 py-2 rounded-md border border-gray-200 dark:border-gray-600">
                                    {{ $category?->code ?? __('N/A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-tag mr-2 text-gray-400"></i>
                                    {{ __('Category Name') }}
                                </label>
                                <p
                                    class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 px-3 py-2 rounded-md border border-gray-200 dark:border-gray-600">
                                    {{ $category?->name ?? __('N/A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @if ($category?->products)
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-boxes text-blue-600 dark:text-blue-400"></i>
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                    {{ __('Products Information') }}</h4>
                            </div>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                {{ __('This category contains') }} <span
                                    class="font-semibold">{{ $category->products_count }}</span>
                                {{ __('products') }}
                            </p>
                        </div>
                    @endif
                </div>
            </x-slot>
        </x-modal>
        <!-- End Show Modal -->

        <livewire:categories.import />
        <livewire:categories.create />
        <livewire:categories.edit :category="$category" />

    </x-page-container>
</div>
