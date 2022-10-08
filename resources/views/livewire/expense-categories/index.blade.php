<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            <button
                class="text-blue-500 dark:text-gray-300 bg-transparent dark:bg-dark-eval-2 border border-blue-500 dark:border-gray-300 hover:text-blue-700  active:bg-blue-600 font-bold uppercase text-xs p-3 rounded outline-none focus:outline-none ease-linear transition-all duration-150"
                type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled"
                {{ $this->selectedCount ? '' : 'disabled' }}>
                {{ __('Delete Selected') }}
            </button>
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <div class="w-full">
                <input wire:model="search"
                    class="w-full p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300"
                    placeholder="Search" />
            </div>
        </div>
    </div>

    <div wire:loading.delay>
         <div class="d-flex justify-content-center">
        <x-loading />
    </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th class="pr-0 w-8">
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
                <x-table.tr>
                    <x-table.td>
                        <input type="checkbox" value="{{ $expenseCategory->id }}" wire:model="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $expenseCategory->category_name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expenseCategory->category_description }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-center">
                            <x-primary-button wire:click="showModal({{ $expenseCategory->id }})" wire:loading.attr="disabled">
                                {{ __('Show') }}
                            </x-primary-button>
                            <x-primary-button wire:click="editModal({{ $expenseCategory->id }})" wire:loading.attr="disabled">
                                {{ __('Edit') }}
                            </x-primary-button>
                            <x-primary-button wire:click="confirm('delete', {{ $expenseCategory->id }})" wire:loading.attr="disabled">
                                {{ __('Delete') }}
                            </x-primary-button>
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
                    <x-label for="category_name" :value="__('Name')" />
                    <x-input id="category_name" type="text" class="block mt-1 w-full"
                        wire:model="expenseCategory.category_name" disabled />
                </div>
                <div class="w-full">
                    <x-label for="category_description" :value="__('Description')" />
                    <x-input id="category_description" type="text" class="block mt-1 w-full"
                        wire:model="expenseCategory.category_description" disabled />
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-primary-button>
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:click="createModal">
        <x-slot name="title">
            {{ __('Create Expense Category') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap justify-center">
                <div class="w-full">
                    <x-label for="category_name" :value="__('Name')" />
                    <x-input id="category_name" type="text" class="block mt-1 w-full"
                        wire:model="expenseCategory.category_name" />
                    <x-input-error :messages="$errors->first('expenseCategory.category_name')" />
                </div>
                <div class="w-full">
                    <x-label for="category_description" :value="__('Description')" />
                    <x-input id="category_description" type="text" class="block mt-1 w-full"
                        wire:model="expenseCategory.category_description" />
                    <x-input-error :messages="$errors->first('expenseCategory.category_description')" />
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button wire:click="$toggle('createModal')" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-primary-button>
                <x-primary-button wire:click="create" wire:loading.attr="disabled">
                    {{ __('Create') }}
                </x-primary-button>
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
                    <x-label for="category_name" :value="__('Name')" />
                    <x-input id="category_name" type="text" class="block mt-1 w-full"
                        wire:model="expenseCategory.category_name" />
                        <x-input-error :messages="$errors->first('expenseCategory.category_name')" />
                </div>
                <div class="w-full">
                    <x-label for="category_description" :value="__('Description')" />
                    <x-input id="category_description" type="text" class="block mt-1 w-full"
                        wire:model="expenseCategory.category_description" />
                        <x-input-error :messages="$errors->first('expenseCategory.category_description')" />
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button wire:click="$toggle('editModal')" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-primary-button>
                <x-primary-button wire:click="update" wire:loading.attr="disabled">
                    {{ __('Update') }}
                </x-primary-button>
            </div>
        </x-slot>
    </x-modal>
</div>



@push('page_scripts')
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
