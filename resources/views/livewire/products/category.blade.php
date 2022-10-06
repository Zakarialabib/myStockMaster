<div>
    <div class="flex flex-wrap justify-center">
        <div class="relative flex w-full flex-wrap items-stretch space-x-2 mb-3 ">
            <select wire:model="perPage"
                class="w-20 border border-gray-300 rounded-md shadow-sm py-2 px-4 bg-white text-sm leading-5 font-medium text-gray-700 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            <button wire:click="deleteSelected"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Delete Selected') }}
            </button>

            <div>
                <input wire:model="search" type="text"
                    class="px-3 py-3 placeholder-gray-400 text-gray-700 relative bg-white dark:bg-dark-eval-2 rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full pr-10"
                    placeholder="Search..." />
                <span
                    class="z-10 h-full leading-snug font-normal absolute text-center text-gray-400 bg-transparent rounded text-base items-center justify-center w-8 right-0 pr-3 py-3">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>

        <div wire:loading.delay>
            <x-loading />
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
                    <x-table.tr>
                        <x-table.td>
                            <input type="checkbox" value="{{ $category->id }}" wire:model="selected">
                        </x-table.td>
                        <x-table.td>
                            {{ $category->category_code }}
                        </x-table.td>
                        <x-table.td>
                            {{ $category->category_name }}
                        </x-table.td>
                        <x-table.td>
                            {{ $category->products_count }}
                        </x-table.td>
                        <x-table.td>
                            <button wire:click="$emit('showModal', {{ $category->id }})"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button wire:click="$emit('editModal', {{ $category->id }})"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="$emit('deleteModal', {{ $category->id }})"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-trash"></i>
                            </button>
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


        <!-- Create Modal -->
        <x-modal wire:model="createModal">
            <x-slot name="title">
                {{ __('Create Category') }}
            </x-slot>

            <x-slot name="content">
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form wire:submit.prevent="create">
                    <div>
                        <x-input id="category_code" type="text" name="category_code"
                            wire:model.defer="category.category_code" hidden />

                        <div class="mt-4">
                            <x-label for="category_name" :value="__('Name')" />
                            <x-input id="category_name" class="block mt-1 w-full" type="text" name="category_name"
                                wire:model.defer="category.category_name" />
                            <x-input-error :messages="$errors->get('category.category_name')" for="category.category_name" class="mt-2" />
                        </div>

                        <div class="w-full flex justify-end">
                            <x-primary-button wire:click="create" wire:loading.attr="disabled">
                                {{ __('Create') }}
                            </x-primary-button>
                            <x-primary-button type="button" wire:click="$set('createModal', false)">
                                {{ __('Cancel') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
        <!-- End Create Modal -->

        <!-- Edit Modal -->
        <x-modal wire:model="editModal">
            <x-slot name="title">
                {{ __('Edit Category') }}
            </x-slot>

            <x-slot name="content">
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                <form wire:submit.prevent="update">
                    <div class="space-y-4">
                        <div class="mt-4">
                            <x-label for="category_code" :value="__('Code')" />
                            <x-input id="category_code" class="block mt-1 w-full" type="text" name="category_code"
                                wire:model.defer="category.category_code" />
                            <x-input-error :messages="$errors->get('category.category_code')" for="category.category_code" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-label for="category_name" :value="__('Name')" />
                            <x-input id="category_name" class="block mt-1 w-full" type="text" name="category_name"
                                wire:model.defer="category.category_name" />
                            <x-input-error :messages="$errors->get('category.category_name')" for="category.category_name" class="mt-2" />
                        </div>

                        <div class="w-full flex justify-end">
                            <x-primary-button wire:click="update" wire:loading.attr="disabled">
                                {{ __('Update') }}
                            </x-primary-button>
                            <x-primary-button type="button" wire:click="$set('editModal', false)">
                                {{ __('Cancel') }}
                            </x-primary-button>
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
                        <label for="category_code">{{ __('Category Code') }} <span
                                class="text-red-500">*</span></label>
                        <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                            type="text" name="category_code" value="{{ $category->category_code }}" disabled>
                    </div>
                    <div class="mb-4">
                        <label for="category_name">{{ __('Category Name') }} <span
                                class="text-red-500">*</span></label>
                        <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                            type="text" name="category_name" value="{{ $category->category_name }}" disabled>
                    </div>

                    <div class="w-full flex justify-end">
                        <x-primary-button type="button" wire:click="$set('showModal', false)">
                            {{ __('Close') }}
                        </x-primary-button>
                    </div>
                </div>
            </x-slot>
        </x-modal>
        <!-- End Show Modal -->
    </div>
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
