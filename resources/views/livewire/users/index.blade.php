<div>
    @section('title', __('Users List'))
    <x-page-container 
        :title="__('Users List')"
        :breadcrumbs="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Users List')]
        ]"
        :show-filters="true">
        
        <x-slot name="actions">
            @can('user_create')
                <x-button 
                    variant="primary" 
                    icon="fas fa-plus" 
                    wire:click="dispatchTo('users.create', 'createModal')">
                    {{ __('Create User') }}
                </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Show') }}</label>
                    <x-input.select wire:model.live="perPage">
                        @foreach ($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </x-input.select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Warehouse') }}</label>
                    <x-input.select wire:model.live="warehouse_id">
                        <option value="">{{ __('Select warehouse') }}</option>
                        @foreach ($this->warehouses as $index => $warehouse)
                            <option value="{{ $index }}">{{ $warehouse }}</option>
                        @endforeach
                    </x-input.select>
                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Search') }}</label>
                    <x-input.text wire:model.live.debounce.500ms="search" placeholder="{{ __('Search users...') }}" icon="fas fa-search" />
                </div>
            </div>
            @if ($selected)
                <div class="flex items-center space-x-2 mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center space-x-2 text-blue-700 dark:text-blue-300">
                        <i class="fas fa-info-circle w-4 h-4"></i>
                        <span class="text-sm font-medium">{{ $this->selectedCount ?? count($selected) }} {{ __('selected') }}</span>
                    </div>
                    @can('user_delete')
                        <x-button wire:click="deleteSelected" variant="danger" size="sm" icon="fas fa-trash">
                            {{ __('Delete Selected') }}
                        </x-button>
                    @endcan
                    <x-button wire:click="$set('selected', [])" variant="secondary" size="sm" icon="fas fa-times">
                        {{ __('Clear Selected') }}
                    </x-button>
                </div>
            @endif
        </x-slot>

        <x-table>
            <x-slot name="thead">
                <x-table.th class="w-12">
                    <x-input.checkbox wire:model.live="selectPage" />
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('created_at')" field="created_at" :direction="$sorts['created_at'] ?? null" class="min-w-32">
                    {{ __('Date') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('name')" field="name" :direction="$sorts['name'] ?? null" class="min-w-40">
                    {{ __('Name') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('email')" field="email" :direction="$sorts['email'] ?? null" class="min-w-48">
                    {{ __('Email') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('phone')" field="phone" :direction="$sorts['phone'] ?? null" class="min-w-32">
                    {{ __('Phone') }}
                </x-table.th>
                <x-table.th sortable wire:click="sortingBy('status')" field="status" :direction="$sorts['status'] ?? null" class="min-w-24">
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
                    <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $user->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <x-table.td>
                            <x-input.checkbox value="{{ $user->id }}" wire:model.live="selected" />
                        </x-table.td>
                        <x-table.td class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <button wire:click="$dispatch('showModal', {{ $user->id }})" type="button" class="font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $user->name }}
                                    </button>
                                </div>
                            </div>
                        </x-table.td>
                        <x-table.td>
                            <a class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300" href="mailto:{{ $user->email }}">
                                {{ $user->email }}
                            </a>
                        </x-table.td>
                        <x-table.td>
                            @if($user->phone)
                                <a class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300" href="tel:{{ $user->phone }}">
                                    {{ $user->phone }}
                                </a>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">{{ __('N/A') }}</span>
                            @endif
                        </x-table.td>
                        <x-table.td>
                            <livewire:utils.toggle-button :model="$user" field="status" key="{{ $user->id }}" lazy />
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
                            <div class="flex items-center space-x-2">
                                @can('user_show')
                                    <x-button variant="secondary" size="sm" wire:click="$dispatch('showModal', { id :'{{ $user->id }}' })" wire:loading.attr="disabled">
                                        <i class="fas fa-eye w-4 h-4"></i>
                                    </x-button>
                                @endcan
                                @can('user_edit')
                                    <x-button variant="primary" size="sm" wire:click="$dispatch('editModal', { id : '{{ $user->id }}' })" wire:loading.attr="disabled">
                                        <i class="fas fa-edit w-4 h-4"></i>
                                    </x-button>
                                @endcan
                                @can('user_delete')
                                    <x-button variant="danger" size="sm" wire:click="delete({{ $user->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this record?') }}" wire:loading.attr="disabled">
                                        <i class="fas fa-trash w-4 h-4"></i>
                                    </x-button>
                                @endcan
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
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

        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                @if ($this->selectedCount)
                    <span class="font-medium text-gray-700 dark:text-gray-300">
                        {{ $this->selectedCount }} {{ __('selected') }}
                    </span>
                @endif
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </x-page-container>

    <livewire:users.show :user="$user" />
    <livewire:users.edit :user="$user" />
    <livewire:users.create />

</div>
