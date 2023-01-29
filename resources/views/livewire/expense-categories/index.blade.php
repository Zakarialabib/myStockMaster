<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($selected)
                <x-button danger type="button" wire:click="$toggle('showDeleteModal')" wire:loading.attr="disabled">
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
                <x-input wire:model.debounce.300ms="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input wire:model="selectPage" type="checkbox" />
            </x-table.th>
            <x-table.th>
                {{ __('Name') }}
            </x-table.th>
            <x-table.th>
                {{ __('Description') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse($expenseCategories as $expenseCategory)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $expenseCategory->id }}">
                    <x-table.td>
                        <input type="checkbox" value="{{ $expenseCategory->id }}" wire:model="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $expenseCategory->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expenseCategory->description }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button info wire:click="showModal({{ $expenseCategory->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="editModal({{ $expenseCategory->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $expenseCategory->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="4">
                        <div class="flex justify-center">
                            {{ __('No Expense Categories found.') }}
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
            {{ $expenseCategories->links() }}
        </div>
    </div>

    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Expense Category') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap justify-center">
                <div class="w-full">
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" type="text" class="block mt-1 w-full" wire:model="expenseCategory.name"
                        disabled />
                </div>
                <div class="w-full">
                    <x-label for="description" :value="__('Description')" />
                    <x-input id="description" type="text" class="block mt-1 w-full"
                        wire:model="expenseCategory.description" disabled />
                </div>
            </div>
        </x-slot>
    </x-modal>


    <x-modal wire:click="editModal">
        <x-slot name="title">
            {{ __('Edit Expense Category') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap justify-center">
                <div class="w-full">
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" type="text" class="block mt-1 w-full"
                        wire:model="expenseCategory.name" />
                    <x-input-error :messages="$errors->first('expenseCategory.name')" />
                </div>
                <div class="w-full">
                    <x-label for="description" :value="__('Description')" />
                    <x-input id="description" type="text" class="block mt-1 w-full"
                        wire:model="expenseCategory.description" />
                    <x-input-error :messages="$errors->first('expenseCategory.description')" />
                </div>
            </div>
            <div class="flex justify-center">
                <x-button primary type="submit" wire:loading.attr="disabled">
                    {{ __('Update') }}
                </x-button>
            </div>
        </x-slot>
    </x-modal>


    <livewire:expense-categories.create />

</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', expenseCategoryId => {
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
                        window.livewire.emit('delete', expenseCategoryId)
                    }
                })
            })
        })
    </script>
@endpush
