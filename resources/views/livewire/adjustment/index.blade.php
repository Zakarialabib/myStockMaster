<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($this->selected)
                <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                    <i class="fas fa-trash"></i>
                </x-button>
            @endif
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <input type="text" wire:model.debounce.300ms="search"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('reference')" :direction="$sorts['reference'] ?? null">
                {{ __('Reference') }}
            </x-table.th>
            <x-table.th>{{ __('Actions') }}</x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($adjustments as $adjustment)
                <x-table.tr wire:key="row-{{ $adjustment->id }}">
                    <x-table.td>
                        <input wire:model="selected" value="{{ $adjustment->id }}" />
                    </x-table.td>
                    <x-table.td>{{ $adjustment->date }}</x-table.td>
                    <x-table.td>{{ $adjustment->reference }}</x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">

                            <x-button primary type="button" wire:click="showModal({{ $adjustment->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>

                            <x-button info href="{{ route('adjustments.edit', $adjustment->id) }}"
                                wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>

                            <x-button danger type="button" wire:click="confirm('delete', {{ $adjustment->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>

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

    @if ($showModal)
        <x-modal wire:model="showModal">
            <x-slot name="title">
                {{ __('Show Adjustment') }} - {{ $adjustment->date }}
            </x-slot>

            <x-slot name="content">
                <div class="p-4">
                    <div>
                        <x-table-responsive>
                            <x-table.tr>
                                <x-table.th>{{ __('Date') }}</x-table.th>
                                <x-table.td>
                                    {{ $adjustment->date }}
                                </x-table.td>
                            </x-table.tr>

                            <x-table.tr>
                                <x-table.th>{{ __('Reference') }}</x-table.th>
                                <x-table.td>
                                    {{ $adjustment->reference }}
                                </x-table.td>
                            </x-table.tr>

                            <div class="border-t border-gray-300 py-3">
                                <x-table.tr>
                                    <x-table.th>{{ __('Product Name') }}</x-table.th>
                                    <x-table.th>{{ __('Code') }}</x-table.th>
                                    <x-table.th>{{ __('Quantity') }}</x-table.th>
                                    <x-table.th>{{ __('Type') }}</x-table.th>
                                </x-table.tr>
                                <x-table.tbody>
                                    @foreach ($adjustment->adjustedProducts as $adjustedProduct)
                                        <x-table.tr>
                                            <x-table.td>{{ $adjustedProduct->product->name }}</x-table.td>
                                            <x-table.td>{{ $adjustedProduct->product->code }}</x-table.td>
                                            <x-table.td>{{ $adjustedProduct->quantity }}</x-table.td>
                                            <x-table.td>
                                                @if ($adjustedProduct->type == 'add')
                                                    {{ __('(+) Addition') }}
                                                @else
                                                    {{ __('(-) Subtraction') }}
                                                @endif
                                            </x-table.td>
                                        </x-table.tr>
                                    @endforeach
                                </x-table.tbody>
                        </x-table-responsive>
                    </div>
                </div>
            </x-slot>
        </x-modal>
    @endif

</div>

@push('scripts')
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
