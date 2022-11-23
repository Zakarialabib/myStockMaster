<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
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
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <div class="my-2 my-md-0">
                <input type="text" wire:model.debounce.300ms="search"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>
   
    <x-table>
        <x-slot name="thead">
            <x-table.th >
                <x-input type="checkbox" class="rounded-tl rounded-bl" wire:model="selectPage" />
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
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($currencies as $currency)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $currency->id }}">
                    <x-table.td class="pr-0">
                        <input type="checkbox" value="{{ $currency->id }}" wire:model="selected" />
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
                        {{ $currency->exchange_rate }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button alert wire:click="showModal({{ $currency->id }})" 
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>

                            <x-button primary wire:click="editModal({{ $currency->id }})" 
                                type="button" wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>

                            <x-button danger wire:click="confirm('delete', {{ $currency->id }})"
                                type="button"  wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
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
            {{ $currencies->links() }}
        </div>
    </div>
    
    @if (null !== $showModal)
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Currency') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col">
                <div class="flex flex-col">
                    <x-label for="currency.name" :value="__('Name')" />
                    <x-input id="name" class="block mt-1 w-full" required type="text" disabled
                        wire:model.defer="currency.name" />
                </div>
                <div class="flex flex-col">
                    <x-label for="currency.code" :value="__('Code')" />
                    <x-input id="code" class="block mt-1 w-full" type="text" disabled
                        wire:model.defer="currency.code" />
                </div>
                <div class="flex flex-col">
                    <x-label for="currency.symbol" :value="__('Symbol')" />
                    <x-input id="symbol" class="block mt-1 w-full" type="text" disabled
                        wire:model.defer="currency.symbol" />
                </div>
                <div class="flex flex-col">
                    <x-label for="currency.rate" :value="__('Rate')" />
                    <x-input id="rate" class="block mt-1 w-full" type="text" disabled
                        wire:model.defer="currency.rate" />
                </div>
            </div>
        </x-slot>
    </x-modal>
    @endif
    
    @if (null !== $editModal)
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Currency') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="update">
                <div class="flex flex-col">
                    <div class="flex flex-col">
                        <x-label for="currency.name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model.defer="currency.name" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="currency.code" :value="__('Code')" />
                        <x-input id="code" class="block mt-1 w-full" type="text"
                            wire:model.defer="currency.code" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="currency.symbol" :value="__('Symbol')" />
                        <x-input id="symbol" class="block mt-1 w-full" type="text"
                            wire:model.defer="currency.symbol" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="currency.rate" :value="__('Rate')" />
                        <x-input id="rate" class="block mt-1 w-full" type="text"
                            wire:model.defer="currency.rate" />
                    </div>
                </div>

                <div class="w-full flex justify-start px-3">
                    <x-button primary type="submit" wire:click="update" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
    @endif

    <livewire:currency.create />
</div>


@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', currencyId => {
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
                        window.livewire.emit('delete', currencyId)
                    }
                })
            })
        })
    </script>
@endpush
