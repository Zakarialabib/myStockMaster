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
                {{ __('Delete') }}
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
            <x-table.th class="pr-0 w-8">
                <x-table.input type="checkbox" class="rounded-tl rounded-bl" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                {{ __('Id') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('code')" :direction="$sorts['code'] ?? null">
                {{ __('Code') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('symbol')" :direction="$sorts['symbol'] ?? null">
                {{ __('Symbol') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('rate')" :direction="$sorts['rate'] ?? null">
                {{ __('Rate') }}
            </x-table.th>
            <x-table.th sortable multi-column wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
            </x-table.th>
            <x-table.th />
        </x-slot>
        <x-slot name="tbody">
            @forelse ($currencies as $currency)
                <x-table.tr wire:key="row-{{ $currency->id }}">
                    <x-table.td class="pr-0">
                        <x-table.checkbox wire:model="selected" value="{{ $currency->id }}" />
                    </x-table.td>
                    <x-table.td>
                        {{ $currency->id }}
                    </x-table.td>
                    <x-table.td>
                        {{ $currency->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $currency->code }}
                    </x-table.td>
                    <x-table.td>
                        {{ $currency->symbol }}
                    </x-table.td>
                    <x-table.td>
                        {{ $currency->rate }}
                    </x-table.td>
                    <x-table.td>
                        {{ $currency->status }}
                    </x-table.td>
                    <x-table.td class="whitespace-no-wrap row-action--icon">
                        {{-- @can('permission_show') --}}
                        <a href="{{ route('currencies.show', $currency) }}"
                            class="mr-3 text-blue-500 dark:text-gray-300 hover:text-blue-700">
                            <i class="bi bi-eye"></i>
                        </a>
                        {{-- @endcan --}}
                        {{-- @can('permission_edit') --}}
                        <a href="{{ route('currencies.edit', $currency) }}"
                            class="mr-3 text-indigo-500 dark:text-gray-300 hover:text-indigo-700">
                            <i class="bi bi-pencil"></i>
                        </a>
                        {{-- @endcan --}}
                        {{-- @can('permission_delete') --}}
                        <a href="#" class="mr-3 text-red-500 dark:text-gray-300 hover:text-red-700"
                            wire:click="confirm('delete', {{ $currency->id }})" wire:loading.attr="disabled">
                            <i class="bi bi-trash"></i>
                        </a>
                        {{-- @endcan --}}
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="8">
                        <div class="flex items-center justify-center">
                            <span class="dark:text-gray-300">{{ __('No results found') }}</span>
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
            {{ $currencies->links() }}
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
