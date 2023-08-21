<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
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
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <x-input wire:model.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('email')" :direction="$sorts['email'] ?? null">
                {{ __('Email') }}
            </x-table.th>
            <x-table.th>
                {{ __('Phone') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
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
                        <input type="checkbox" value="{{ $user->id }}" wire:model="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $user->created_at->format('d / m / Y') }}
                    </x-table.td>
                    <x-table.td>
                        <button wire:click="$emit('showModal', {{ $user->id }})" type="button">
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
                        <livewire:toggle-button :model="$user" field="status" key="{{ $user->id }}" />
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
                            <x-button secondary wire:click="$emit('showModal', {{ $user->id }})" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="$emit('editModal', {{ $user->id }})" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $user->id }})" type="button"
                                wire:loading.attr="disabled">
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

    <div class="p-4">
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
    </div>

    <livewire:users.show :user="$user" />

    <livewire:users.edit :user="$user" />

    <livewire:users.create />

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                window.livewire.on('deleteModal', UserId => {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.livewire.emit('delete', UserId)
                        }
                    })
                })
            })
        </script>
    @endpush
</div>
