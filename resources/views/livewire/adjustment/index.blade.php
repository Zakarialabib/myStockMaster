<div>
    @section('title', __('Adjustments'))

    <x-theme.breadcrumb :title="__('Adjustments List')" :parent="route('adjustments.index')" :parentName="__('Adjustments List')">
        @can('adjustment_create')
            <x-button href="{{ route('adjustments.create') }}" primary>
                {{ __('Create Adjustment') }}
            </x-button>
        @endcan
    </x-theme.breadcrumb>

    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model.live="perPage"
                class="w-20 block p-3 leading-5 bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($this->selected)
                @can('adjustment_delete')
                    <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                        <i class="fas fa-trash"></i>
                    </x-button>
                @endcan
            @endif
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <x-input wire:model.live.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model.live="selectPage" />
            </x-table.th>
            <x-table.th sortable :direction="$sorts['date'] ?? null" field="date" wire:click="sortingBy('date')">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable :direction="$sorts['reference'] ?? null" field="reference" wire:click="sortingBy('reference')">
                {{ __('Reference') }}
            </x-table.th>
            <x-table.th>{{ __('Actions') }}</x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($adjustments as $adjustment)
                <x-table.tr wire:key="row-{{ $adjustment->id }}">
                    <x-table.td>
                        <input wire:model.live="selected" type="checkbox" />
                    </x-table.td>
                    <x-table.td>{{ $adjustment->date }}</x-table.td>
                    <x-table.td>{{ $adjustment->reference }}</x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">

                            <x-button primary type="button" wire:click="$dispatch('showModal', {{ $adjustment->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            @can('adjustment_update')
                                <x-button info href="{{ route('adjustments.edit', $adjustment->id) }}"
                                    wire:loading.attr="disabled">
                                    <i class="fas fa-edit"></i>
                                </x-button>
                            @endcan
                            @can('adjustment_delete')
                                <x-button danger type="button"
                                    wire:click="$dispatch('deleteModal', {{ $adjustment->id }})"
                                    wire:loading.attr="disabled">
                                    <i class="fas fa-trash"></i>
                                </x-button>
                            @endcan
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="6">
                        <div class="flex justify-center">
                            {{ __('No results found') }}
                        </div>
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
            {{ $adjustments->links() }}
        </div>
    </div>


    @if ($adjustment)
        @livewire('adjustment.show', ['adjustment' => $adjustment])
    @endif


    @push('scripts')
        <script>
            document.addEventListener('livewire:init', function() {
                window.livewire.on('deleteModal', adjustmentId => {
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
                            window.Livewire.dispatch('delete', adjustmentId)
                        }
                    })
                })
            })
        </script>
    @endpush

</div>
