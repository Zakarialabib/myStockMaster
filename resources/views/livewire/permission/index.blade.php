<div>
    @section('title', __('Permissions List'))
    <x-page-container :title="__('Permissions List')" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Permissions List')]]" :show-filters="true">
        
        <x-slot name="actions">
            @can('permission_create')
                <x-button variant="primary" icon="fas fa-plus" wire:click="dispatchTo('permission.create', 'createModal')">
                    {{ __('Create Permission') }}
                </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <x-datatable.filters 
                :per-page-options="$paginationOptions"
                :selected-count="$this->selectedCount"
                :can-delete="auth()->user()->can('permission_delete')"
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
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse($permissions as $permission)
                    <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $permission->id }}">
                        <x-table.td>
                            <x-input.checkbox value="{{ $permission->id }}" wire:model.live="selected" />
                        </x-table.td>
                        <x-table.td>
                            {{ $permission->name }}
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
                                        @can('permission_edit')
                                            <x-dropdown-link wire:click="$dispatch('editModal', {{ $permission->id }} )" wire:loading.attr="disabled">
                                                <i class="fas fa-edit"></i>
                                                {{ __('Edit') }}
                                            </x-dropdown-link>
                                        @endcan
                                        @can('permission_delete')
                                            <x-dropdown-link wire:click="delete({{ $permission->id }})" wire:confirm="{{ __('Are you sure you want to delete this permission?') }}" wire:loading.attr="disabled">
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
                        <x-table.td colspan="3" class="text-center">
                            {{ __('No entries found.') }}
                        </x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>

        <div class="p-4">
            <div class="pt-3">
                {{ $permissions->links() }}
            </div>
        </div>
    </x-page-container>
    
    @livewire('permission.create')

    @livewire('permission.edit', ['permission' => $permission])
</div>
