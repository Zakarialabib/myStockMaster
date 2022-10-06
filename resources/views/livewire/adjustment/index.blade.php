<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            {{-- @can('permission_delete') --}}
                <button
                    class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                    type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled"
                    {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{__('Delete')}}
                </button>
            {{-- @endcan --}}
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <div class="my-2 my-md-0">
                <input type="text" wire:model.debounce.300ms="search"
                    class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>
    <div wire:loading.delay>
        Loading...
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>#</x-table.th>
            <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                    {{__('Name')}}
                    @include('components.table.sort', ['field' => 'name'])
                </div>
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('description')" :direction="$sorts['description'] ?? null">
                    {{__('Description')}}
                    @include('components.table.sort', ['field' => 'description'])
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null">
                    {{__('Created At')}}
                    @include('components.table.sort', ['field' => 'created_at'])
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('updated_at')" :direction="$sorts['updated_at'] ?? null">   
                    {{__('Updated At')}}
                @include('components.table.sort', ['field' => 'updated_at'])
            </x-table.th>
            <x-table.th>{{__('Actions')}}</x-table.th>
        </x-slot>
        <x-slot name="tbody">
            @forelse ($adjustments as $adjustment)
                <x-table.tr wire:key="row-{{ $adjustment->id }}">
                    <x-table.td>
                        <input wire:model="selected" value="{{ $adjustment->id }}" />
                    </x-table.td>
                    <x-table.td>{{ $adjustment->name }}</x-table.td>
                    <x-table.td>{{ $adjustment->description }}</x-table.td>
                    <x-table.td>{{ $adjustment->created_at->format('d/m/Y') }}</x-table.td>
                    <x-table.td>{{ $adjustment->updated_at->format('d/m/Y') }}</x-table.td>
                    <x-table.td>
                        <div class="flex justify-center">
                            {{-- @can('permission_show') --}}
                                <a href="{{ route('adjustments.show', $adjustment->id) }}"
                                    class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                                    type="button">
                                    {{__('Show')}}
                                </a>
                            {{-- @endcan
                            @can('permission_edit') --}}
                                <a href="{{ route('adjustments.edit', $adjustment->id) }}"
                                    class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                                    type="button">
                                    {{__('Edit')}}
                                </a>
                            {{-- @endcan
                            @can('permission_delete') --}}
                                <button
                                    class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                                    type="button" wire:click="confirm('delete', {{ $adjustment->id }})"
                                    wire:loading.attr="disabled">
                                    {{__('Delete')}}
                            </button>
                            {{-- @endcan --}}
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="6">
                        <div class="flex justify-center">
                            {{__('No results found for the query')}} "{{ $search }}".
                        </div>
                    </x-table.td>
                </x-table.tr>   
            @endforelse
        </x-slot>
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
            {{ $adjustments->links() }}
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        window.addEventListener('confirm', event => {
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
                    window.livewire.emit('delete', event.detail.id)
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        })
    </script>
@endpush
