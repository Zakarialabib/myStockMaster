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
                    class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                    type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled"
                    {{ $this->selectedCount ? '' : 'disabled' }}>
                    <x-heroicon-o-trash class="h-4 w-4" />
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
                            {{ $role->title }}
                        </x-table.td>
                        <x-table.td class="flex flex-wrap">
                            @foreach ($role->permissions as $key => $entry)
                                <span class="badge badge-relationship">{{ $entry->title }}</span>
                            @endforeach
                        </x-table.td>
                        <x-table.td>
                            <div class="inline-flex">
                                @can('role_show')
                                    <a class="btn btn-sm text-white bg-blue-500 border-blue-800 hover:bg-blue-600 active:bg-blue-700 focus:ring-blue-300 mr-2"
                                        href="{{ route('admin.roles.show', $role) }}">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                @endcan
                                @can('role_edit')
                                    <a class="btn btn-sm text-white bg-green-500 border-green-800 hover:bg-green-600 active:bg-green-700 focus:ring-green-300mr-2"
                                        href="{{ route('admin.roles.edit', $role) }}">
                                        <x-heroicon-o-pencil-alt class="h-4 w-4" />
                                    </a>
                                @endcan
                                @can('role_delete')
                                    <button
                                        class="btn btn-sm text-white bg-red-500 border-red-800 hover:bg-red-600 active:bg-red-700 focus:ring-red-300 mr-2"
                                        type="button" wire:click="confirm('delete', {{ $role->id }})"
                                        wire:loading.attr="disabled">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                @endcan
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

    <div class="card-body">
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
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
            if (!confirm("{{ __('Are you sure') }}")) {
                return
            }
            @this[e.callback](...e.argv)
        });
    </script>
@endpush
