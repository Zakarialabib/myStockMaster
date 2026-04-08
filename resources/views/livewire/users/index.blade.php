<div>

    <x-page-container :title="__('Users List')" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Users List')]]" :show-filters="true">

        <x-slot name="actions">
            @can('user_create')
                <x-button variant="primary" icon="fas fa-plus" wire:click="dispatchTo('users.create', 'createModal')">
                    {{ __('Create User') }}
                </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <x-datatable.filters 
                :per-page-options="$paginationOptions"
                :selected-count="$this->selectedCount ?? count($selected)"
                :can-delete="auth()->user()->can('user_delete')"
            >
                <x-slot name="extraFilters">
                    <div class="w-full md:w-64">
                        <x-input.select wire:model.live="warehouse_id">
                            <option value="">{{ __('Select warehouse') }}</option>
                            @foreach ($this->warehouses as $index => $warehouse)
                                <option value="{{ $index }}">{{ $warehouse }}</option>
                            @endforeach
                        </x-input.select>
                        <x-input-error :messages="$errors->get('warehouse_id')" class="mt-1" />
                    </div>
                </x-slot>
            </x-datatable.filters>
        </x-slot>

        <x-table>
            <x-slot name="thead">
                <x-table.th class="w-12">
                    <x-input.checkbox wire:model.live="selectPage" />
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('created_at')" field="created_at" :direction="$sortBy === 'created_at' ? $sortDirection : null"
                    class="min-w-32">
                    {{ __('Date') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('name')" field="name" :direction="$sortBy === 'name' ? $sortDirection : null" class="min-w-40">
                    {{ __('Name') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('email')" field="email" :direction="$sortBy === 'email' ? $sortDirection : null" class="min-w-48">
                    {{ __('Email') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('phone')" field="phone" :direction="$sortBy === 'phone' ? $sortDirection : null" class="min-w-32">
                    {{ __('Phone') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('status')" field="status" :direction="$sortBy === 'status' ? $sortDirection : null" class="min-w-24">
                    {{ __('Status') }}
                </x-table.th>
                <x-table.th class="min-w-32">
                    {{ __('Roles') }}
                </x-table.th>
                <x-table.th class="min-w-32">
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse($users as $user)
                    <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $user->id }}"
                        class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <x-table.td>
                            <x-input.checkbox value="{{ $user->id }}" wire:model.live="selected" />
                        </x-table.td>
                        <x-table.td class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center space-x-3">
                                <div
                                    class="shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <button wire:click="$dispatch('showModal', {{ $user->id }})" type="button"
                                        class="font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $user->name }}
                                    </button>
                                </div>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <a class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                href="mailto:{{ $user->email }}">
                                {{ $user->email }}
                            </a>
                        </x-table.td>
                        <x-table.td>
                            @if ($user->phone)
                                <a class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                    href="tel:{{ $user->phone }}">
                                    {{ $user->phone }}
                                </a>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">{{ __('N/A') }}</span>
                            @endif
                        </x-table.td>
                        <x-table.td>
                            <livewire:toggle-button :model="$user" field="status" key="{{ $user->id }}" />
                        </x-table.td>
                        <x-table.td>
                            <div class="flex flex-wrap gap-1">
                                @forelse ($user->roles as $role)
                                    <x-badge variant="primary" size="sm">
                                        {{ $role->name }}
                                    </x-badge>
                                @empty
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">{{ __('No roles') }}</span>
                                @endforelse
                            </div>
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
                                        @can('user_show')
                                            <x-dropdown-link wire:click="$dispatch('showModal', { id :'{{ $user->id }}' })" wire:loading.attr="disabled">
                                                <i class="fas fa-eye w-4 h-4"></i>
                                                {{ __('Show') }}
                                            </x-dropdown-link>
                                        @endcan
                                        @can('user_edit')
                                            <x-dropdown-link wire:click="$dispatch('editModal', { id : '{{ $user->id }}' })" wire:loading.attr="disabled">
                                                <i class="fas fa-edit w-4 h-4"></i>
                                                {{ __('Edit') }}
                                            </x-dropdown-link>
                                        @endcan
                                        @can('user_delete')
                                            <x-dropdown-link wire:click="delete({{ $user->id }})" wire:confirm="{{ __('Are you sure you want to delete this record?') }}" wire:loading.attr="disabled">
                                                <i class="fas fa-trash w-4 h-4"></i>
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
                        <x-table.td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div
                                    class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <div class="text-gray-500 dark:text-gray-400">
                                    <p class="text-lg font-medium">{{ __('No users found') }}</p>
                                    <p class="text-sm">{{ __('Try adjusting your search or filter criteria') }}</p>
                                </div>
                            </div>
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
                            {{ __('of') }} {{ $users->total() }} {{ __('entries selected') }}
                        </p>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Showing') }} {{ $users->firstItem() ?? 0 }} {{ __('to') }}
                        {{ $users->lastItem() ?? 0 }} {{ __('of') }} {{ $users->total() }}
                        {{ __('results') }}
                    </p>
                @endif
                <div class="flex justify-center sm:justify-end">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        </x-page-container>

    <livewire:users.show :user="$user" />
    <livewire:users.edit :user="$user" />
    <livewire:users.create />

</div>
