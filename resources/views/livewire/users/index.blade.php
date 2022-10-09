<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="
                {{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            <x-button danger wire:click="deleteSelected" class="ml-3">
                <i class="fas fa-trash"></i>
            </x-button>

        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <input type="text" wire:model.debounce.300ms="search" class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                placeholder="{{ __('Search') }}" />
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
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null">
                {{ __('Date') }}
                @include('components.table.sort', ['field' => 'created_at'])
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
                @include('components.table.sort', ['field' => 'name'])
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('email')" :direction="$sorts['email'] ?? null">
                {{ __('Email') }}
                @include('components.table.sort', ['field' => 'email'])
            </x-table.th>
            <x-table.th>
                {{ __('Phone') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
                @include('components.table.sort', ['field' => 'status'])
            </x-table.th>
            <x-table.th>
                {{ __('Roles') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse($users as $user)
                <x-table.tr>
                    <x-table.td>
                        <input type="checkbox" value="{{ $user->id }}" wire:model="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $user->created_at->format('d / m / Y') }}
                    </x-table.td>
                    <x-table.td>
                        {{ $user->name }}
                    </x-table.td>
                    <x-table.td>
                        <a class="link-light-blue" href="mailto:{{ $user->email }}">
                            {{ $user->email }}
                        </a>
                    </x-table.td>
                    <x-table.td>
                        {{ $user->phone }}
                    </x-table.td>
                    <x-table.td>
                        <livewire:toggle-button :model="$user" field="status" key="{{ $user->id }}" />
                    </x-table.td>
                   
                    <x-table.td>
                        @foreach ($user->roles as $key => $entry)
                            <span class="badge badge-relationship">{{ $entry->title }}</span>
                        @endforeach
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button secondary wire:click="showModal({{ $user->id }})"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="editModal({{ $user->id }})" wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $user->id }})"
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
            {{ $users->links() }}
        </div>
    </div>

    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show User') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap">
                <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" class="block mt-1 w-full" disabled type="text"
                        wire:model.defer="user.name" />
                </div>

                <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                    <x-label for="phone" :value="__('Phone')" />
                    <x-input id="phone" class="block mt-1 w-full" disabled type="text"
                        wire:model.defer="user.phone" />
                </div>

                <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                    <x-label for="email" :value="__('Email')" />
                    <x-input id="email" class="block mt-1 w-full" disabled type="email"
                        wire:model.defer="user.email" />
                </div>

                <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                    <x-label for="address" :value="__('Address')" />
                    <x-input id="address" class="block mt-1 w-full" disabled type="text"
                        wire:model.defer="user.address" />
                </div>

                <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                    <x-label for="city" :value="__('City')" />
                    <x-input id="city" class="block mt-1 w-full" type="text" disabled
                        wire:model.defer="user.city" />
                </div>

                <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                    <x-label for="tax_number" :value="__('Tax Number')" />
                    <x-input id="tax_number" class="block mt-1 w-full" type="text"
                        wire:model.defer="user.tax_number" disabled />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button secondary wire:click="$set('showModal', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-button>
                </div>
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit User') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" type="text"
                            wire:model.defer="user.name" required />
                        <x-input-error :messages="$errors->get('user.name')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.defer="user.phone" />
                        <x-input-error :messages="$errors->get('user.phone')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="email" :value="__('Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="email"
                            wire:model.defer="user.email" />
                        <x-input-error :messages="$errors->get('user.email')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="address" :value="__('Address')" />
                        <x-input id="address" class="block mt-1 w-full" type="text"
                            wire:model.defer="user.address" />
                        <x-input-error :messages="$errors->get('user.address')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text"
                            wire:model.defer="user.city" />
                        <x-input-error :messages="$errors->get('user.city')" class="mt-2" />
                    </div>

                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="tax_number" :value="__('Tax Number')" />
                        <x-input id="tax_number" class="block mt-1 w-full" type="text"
                            wire:model.defer="user.tax_number" />
                        <x-input-error :messages="$errors->get('user.tax_number')" for="" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button primary wire:click="update" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                        <x-button sencondary wire:click="$set('editModal', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

    <livewire:users.create />

</div>

@push('page_scripts')

<script>
    document.addEventListener('livewire:load', function() {
        window.livewire.on('deleteModal', UserId => {
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
                    window.livewire.emit('delete', UserId)
                }
            })
        })
    })
</script>
@endpush
