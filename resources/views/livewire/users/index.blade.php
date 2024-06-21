<div>
    @section('title', __('Users List'))
    <x-theme.breadcrumb :title="__('User List')" :parent="route('suppliers.index')" :parentName="__('User List')">
        <x-button primary type="button" wire:click="dispatchTo('users.create', 'createModal')">
            {{ __('Create User') }}
        </x-button>
    </x-theme.breadcrumb>
    <div class="flex flex-wrap justify-center items-center">
        <div class="md:w-1/3  sm:w-full flex flex-wrap my-2">
            <select wire:model.live="perPage"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-auto sm:text-sm border-gray-300 rounded-md focus:outline-none focus:shadow-outline-blue transition duration-150 ease-in-out">
                @foreach ($paginationOptions as $value)
                    <option value="
                {{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($selected)
                <x-button danger wire:click="deleteSelected" class="ml-3">
                    <i class="fas fa-trash"></i>
                </x-button>
            @endif
            @if ($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
        </div>
        <div class="md:w-1/3 sm:w-full px-3">
            <select required id="warehouse_id" name="warehouse_id" wire:model.live="warehouse_id"
                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                <option value="">{{ __('Select warehouse') }}</option>
                @foreach ($this->warehouses as $index => $warehouse)
                    <option value="{{ $index }}">{{ $warehouse }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
        </div>
        <div class="md:w-1/3  sm:w-full">
            <x-input wire:model.live="search" placeholder="{{ __('Search') }}" autofocus />
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model.live="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('created_at')" field="created_at" :direction="$sorts['created_at'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('name')" field="name" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('email')" field="email" :direction="$sorts['email'] ?? null">
                {{ __('Email') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('phone')" field="phone" :direction="$sorts['phone'] ?? null">
                {{ __('Phone') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortingBy('status')" field="status" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
            </x-table.th>
            <x-table.th>
                {{ __('Roles') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse($users as $user)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $user->id }}">
                    <x-table.td>
                        <input type="checkbox" value="{{ $user->id }}" wire:model.live="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $user->created_at->format('d / m / Y') }}
                    </x-table.td>
                    <x-table.td>
                        <button wire:click="$dispatch('showModal', {{ $user->id }})" type="button">
                            {{ $user->name }}
                        </button>
                    </x-table.td>
                    <x-table.td>
                        <a class="text-blue-500" href="mailto:{{ $user->email }}">
                            {{ $user->email }}
                        </a>
                    </x-table.td>
                    <x-table.td>
                        <a class="text-blue-500" href="tel:{{ $user->phone }}">
                            {{ $user->phone }}
                        </a>
                    </x-table.td>
                    <x-table.td>
                        <livewire:utils.toggle-button :model="$user" field="status" key="{{ $user->id }}"
                            lazy />
                    </x-table.td>
                    <x-table.td>
                        @foreach ($user->roles as $role)
                            <x-badge type="primary">
                                {{ $role->name }}
                            </x-badge>
                        @endforeach
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button secondary wire:click="$dispatch('showModal', { id :'{{ $user->id }}' })"
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="$dispatch('editModal', { id : '{{ $user->id }}' })"
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$dispatch('deleteModal', { id : '{{ $user->id }}' })"
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="10" class="text-center">
                        {{ __('No entries found.') }}
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
        {{ $users->links() }}
    </div>

    <livewire:users.show :user="$user" />

    <livewire:users.edit :user="$user" />

    <livewire:users.create />

</div>
