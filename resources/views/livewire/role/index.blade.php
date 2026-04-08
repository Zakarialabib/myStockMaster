<div>

    <x-page-container :title="__('Roles List')" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Roles List')]]" :show-filters="true">
        
        <x-slot name="actions">
            @can('role_create')
                <x-button variant="primary" icon="fas fa-plus" wire:click="dispatchTo('role.create', 'createModal')">
                    {{ __('Create Role') }}
                </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <x-datatable.filters 
                :per-page-options="$paginationOptions"
                :selected-count="$this->selectedCount"
                :can-delete="auth()->user()->can('role_delete')"
            />
        </x-slot>

        <x-table>
            <x-slot name="thead">
                <x-table.th class="w-12">
                    <x-input.checkbox wire:model.live="selectPage" />
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('name')" field="name" :direction="$sortBy === 'name' ? $sortDirection : null">
                    {{ __('Title') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Permissions count') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse($roles as $role)
                    <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $role->id }}">
                        <x-table.td>
                            <x-input.checkbox value="{{ $role->id }}" wire:model.live="selected" />
                        </x-table.td>
                        <x-table.td>
                            {{ $role->name }}
                        </x-table.td>
                        <x-table.td>
                            {{ $role->permissions->count() }}
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
                                        @can('role_edit')
                                            <x-dropdown-link wire:click="$dispatch('editModal', { id: {{ $role->id }} } )" wire:loading.attr="disabled">
                                                <i class="fas fa-edit"></i>
                                                {{ __('Edit') }}
                                            </x-dropdown-link>
                                        @endcan
                                        @can('role_delete')
                                            <x-dropdown-link wire:click="delete({{ $role->id }})" wire:confirm="{{ __('Are you sure you want to delete this role?') }}" wire:loading.attr="disabled">
                                                <i class="fas fa-trash"></i>
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        @endcan
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td colspan="4" class="text-center">
                            {{ __('No entries found.') }}
                        </x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>

        <!-- Pagination Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4 mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                @if ($this->selectedCount)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-blue-500 dark:text-blue-400"></i>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $this->selectedCount }}</span>
                            {{ __('of') }} {{ $roles->total() }} {{ __('entries selected') }}
                        </p>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Showing') }} {{ $roles->firstItem() ?? 0 }} {{ __('to') }}
                        {{ $roles->lastItem() ?? 0 }} {{ __('of') }} {{ $roles->total() }}
                        {{ __('results') }}
                    </p>
                @endif
                <div class="flex justify-center sm:justify-end">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
        </x-page-container>

    @livewire('role.create')

    @livewire('role.edit', ['role' => $role])

</div>
