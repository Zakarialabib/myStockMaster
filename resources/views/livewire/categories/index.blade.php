<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 border border-gray-300 rounded-md shadow-sm py-2 px-4 bg-white text-sm leading-5 font-medium text-gray-700 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if($this->selectedCount)
            <x-button danger wire:click="deleteSelected" class="ml-3">
                <i class="fas fa-trash"></i>
            </x-button>
            @endif
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <div class="flex items-center mr-3 pl-4">
                <input wire:model="search" type="text"
                    class="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white dark:bg-dark-eval-2 rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full pr-10"
                    placeholder="{{__('Search...')}}" />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th class="pr-0 w-8">
                <input wire:model="selectPage" type="checkbox" />
            </x-table.th>
            <x-table.th>
                {{ __('Code') }}
            </x-table.th>
            <x-table.th>
                {{ __('Name') }}
            </x-table.th>
            <x-table.th>
                {{ __('Products count') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
            </tr>
        </x-slot>
        <x-table.tbody>
            @forelse($categories as $category)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $category->id }}">
                    <x-table.td>
                        <input type="checkbox" value="{{ $category->id }}" wire:model="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $category->code }}
                    </x-table.td>
                    <x-table.td>
                        {{ $category->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $category->products_count }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button info wire:click="$emit('showModal', {{ $category->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="$emit('editModal', {{ $category->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $category->id }})"
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
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Category') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="update">
                <div class="space-y-4 px-4">
                    <div class="mt-4 w-full">
                        <x-label for="code" :value="__('Code')" />
                        <x-input id="code" class="block mt-1 w-full" type="text" name="code" disabled
                            wire:model.defer="category.code" />
                        <x-input-error :messages="$errors->get('category.code')" for="category.code" class="mt-2" />
                    </div>

                    <div class="mt-4 p w-full">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                            wire:model.defer="category.name" />
                        <x-input-error :messages="$errors->get('category.name')" for="category.name" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-end">
                        <x-button primary wire:click="update" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                        <x-button primary type="button" wire:click="$set('editModal', false)">
                            {{ __('Cancel') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Edit Modal -->

    <!-- Show Modal -->
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Category') }}
        </x-slot>

        <x-slot name="content">
            <div>
                <div class="mb-4">
                    <label for="code">{{ __('Category Code') }} <span class="text-red-500">*</span></label>
                    <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                        type="text" name="code" wire:model.defer="category.code" disabled />
                </div>
                <div class="mb-4">
                    <label for="name">{{ __('Category Name') }} <span class="text-red-500">*</span></label>
                    <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                        type="text" name="name" wire:model.defer="category.name" disabled />
                </div>

                <div class="w-full flex justify-end">
                    <x-button primary type="button" wire:loading.attr="disabled"  wire:click="$set('showModal', false)">
                        {{ __('Close') }}
                    </x-button>
                </div>
            </div>
        </x-slot>
    </x-modal>
    <!-- End Show Modal -->

    {{-- Import modal --}}

    <x-modal wire:model="import">
        <x-slot name="title">
            {{ __('Import Categories') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="importExcel">
                <div class="space-y-4">
                    <div class="mt-4">
                        <x-label for="import" :value="__('Import')" />
                        <x-input id="import" class="block mt-1 w-full" type="file" name="import"
                            wire:model.defer="import" />
                        <x-input-error :messages="$errors->get('import')" for="import" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-end">
                        <x-button primary wire:click="importExcel" type="button" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                        <x-button primary type="button" wire:loading.attr="disabled" wire:click="$set('import', false)">
                            {{ __('Cancel') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

    {{-- End Import modal --}}

    <livewire:categories.create />
</div>

@push('page_scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', categoryId => {
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
                        window.livewire.emit('delete', categoryId)
                    }
                })
            })
        })
    </script>
@endpush
