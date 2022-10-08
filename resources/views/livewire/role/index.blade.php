<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @can('role_delete')
                <button
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled"
                    {{ $this->selectedCount ? '' : 'disabled' }}>
                            <i class="fas fa-trash"></i>
                </button>
            @endcan
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <input type="text" wire:model.debounce.300ms="search"
                class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                placeholder="{{ __('Search') }}" />
        </div>
    </div>
    <div wire:loading.delay>
        Loading...
    </div>

    <div>
        <x-table>
            <x-slot name="thead">
                <x-table.th>#</x-table.th>
                <x-table.th sortable wire:click="sortBy('title')" :direction="$sorts['title'] ?? null">
                    {{ __('Title') }}
                    @include('components.table.sort', ['field' => 'title'])
                </x-table.th>
                <x-table.th>
                    {{ __('Permissions') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Actions') }}
                </x-table.th>
            </x-slot>
            <x-table.tbody>
                @forelse($roles as $role)
                    <x-table.tr>
                        <x-table.td>
                            <input type="checkbox" value="{{ $role->id }}" wire:model="selected">
                        </x-table.td>
                        <x-table.td>
                            {{ $role->name }}
                        </x-table.td>
                        <x-table.td class="flex flex-wrap">
                            @foreach ($role->permissions as $key => $entry)
                                <span class="badge badge-relationship">{{ $entry->title }}</span>
                            @endforeach
                        </x-table.td>
                        <x-table.td>
                            <div class="inline-flex">
                                <x-primary-button
                                    class="bg-blue-500 border-blue-800 hover:bg-blue-600 active:bg-blue-700 focus:ring-blue-300 mr-2"
                                    wire:click="showModal({{ $role->id }})" wire:loading.attr="disabled">
                                    <i class="fas fa-eye"></i>
                                </x-primary-button>

                                <x-primary-button
                                    class="bg-green-500 border-green-800 hover:bg-green-600 active:bg-green-700 focus:ring-green-300mr-2"
                                    wire:click="editModal({{ $role->id }})" wire:loading.attr="disabled">
                                    <i class="fas fa-edit"></i>
                                </x-primary-button>

                                <x-primary-button
                                    class=" bg-red-500 border-red-800 hover:bg-red-600 active:bg-red-700 focus:ring-red-300 mr-2"
                                    type="button" wire:click="confirm('delete', {{ $role->id }})"
                                    wire:loading.attr="disabled">
                                    <i class="fas fa-trash"></i>
                                </x-primary-button>

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
    </div>

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
            {{ $roles->links() }}
        </div>
    </div>

    <x-modal>
        <x-slot name="title">
            {{ __('show') }}
        </x-slot>

        <x-slot name="content">
            <x-table>
                <x-slot name="thead">
                    <x-table.th>#</x-table.th>
                    <x-table.th>{{ __('Title') }}</x-table.th>
                    <x-table.th>{{ __('Permissions') }}</x-table.th>
                </x-slot>
                <x-table.tbody>
                    <x-table.tr>
                        <x-table.td>
                            {{ $role->id }}
                        </x-table.td>
                        <x-table.td>
                            {{ $role->title }}
                        </x-table.td>
                        <x-table.td class="flex flex-wrap">
                            @foreach ($role->permissions as $key => $entry)
                                <span class="badge badge-relationship">{{ $entry->title }}</span>
                            @endforeach
                        </x-table.td>
                    </x-table.tr>
                </x-table.tbody>
            </x-table>
        </x-slot>
    </x-modal>



    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('edit') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="form-group">
                    <label for="title">{{ __('Title') }}</label>
                    <input type="text" class="form-control" id="title" wire:model="role.title">
                    @error('role.title')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <x-select-list
                        class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                        required id="permissions" name="permissions" wire:model="permissions" :options="$this->listsForFields['permissions']"
                        multiple />
                </div>
                <div class="w-full flex justify-end">
                    <x-primary-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-primary-button>

                    <x-primary-button class="ml-2" wire:click="$set('editModal', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-primary-button>

                </div>
            </form>

        </x-slot>
    </x-modal>

    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('create') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="store">
                <div class="form-group">
                    <label for="title">{{ __('Title') }}</label>
                    <input type="text" class="form-control" id="title" wire:model="role.title">
                    @error('role.title')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <x-select-list
                        class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                        required id="permissions" name="permissions" wire:model="permissions" :options="$this->listsForFields['permissions']"
                        multiple />
                </div>
                <div class="w-full flex justify-end">
                    <x-primary-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-primary-button>

                    <x-primary-button class="ml-2" wire:click="$set('createModal', false)"
                        wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-primary-button>

                </div>
            </form>
        </x-slot>
    </x-modal>
</div>



@push('page_scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', roleId => {
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
                        window.livewire.emit('delete', roleId)
                    }
                })
            })
        })
    </script>
@endpush

