<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="
                {{ $value }}">{{ $value }}</option>
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
            <x-table.th sortable wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('email')" :direction="$sorts['email'] ?? null">
                {{ __('Email') }}
            </x-table.th>
            <x-table.th>
                {{ __('Phone') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('status')" :direction="$sorts['status'] ?? null">
                {{ __('Status') }}
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
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $user->id }}">
                    <x-table.td>
                        <input type="checkbox" value="{{ $user->id }}" wire:model="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $user->created_at->format('d / m / Y') }}
                    </x-table.td>
                    <x-table.td>
                        <button wire:click="showModal({{ $user->id }})" type="button">
                            {{ $user->name }}
                        </button>
                    </x-table.td>
                    <x-table.td>
                        <a class="text-blue-500" href="mailto:{{ $user->email }}">
                            {{ $user->email }}
                        </a>
                    </x-table.td>
                    <x-table.td>
                        <a class="text-blue-500" href="tel:{{ $user->phone }}">
                            {{ $user->phone }}
                        </a>
                    </x-table.td>
                    <x-table.td>
                        <livewire:toggle-button :model="$user" field="status" key="{{ $user->id }}" />
                    </x-table.td>
                    <x-table.td>
                        @foreach ($user->roles as $role)
                            <x-badge primary>{{ $role->name }}</x-badge>
                        @endforeach
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button secondary wire:click="showModal({{ $user->id }})" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="editModal({{ $user->id }})" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $user->id }})" type="button"
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

    @if (null !== $showModal)
        <x-modal wire:model="showModal">
            <x-slot name="title">
                {{ __('Show User') }} - {{ $user->name }}
            </x-slot>

            <x-slot name="content">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="name" :value="__('Name')" />
                        <p class="block mt-1 w-full">
                            {{ $user->name }}
                        </p>
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="phone" :value="__('Phone')" />
                        <p class="block mt-1 w-full">
                            {{ $user->phone }}
                        </p>
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="email" :value="__('Email')" />
                        <p class="block mt-1 w-full">
                            {{ $user->email }}
                        </p>
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="address" :value="__('Address')" />
                        <p class="block mt-1 w-full">
                            {{ $user->address }}
                        </p>
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="city" :value="__('City')" />
                        <p class="block mt-1 w-full">
                            {{ $user->city }}
                        </p>
                    </div>

                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="tax_number" :value="__('Tax Number')" />
                        <p class="block mt-1 w-full">
                            {{ $user->tax_number }}
                        </p>
                    </div>
                </div>
            </x-slot>
        </x-modal>
    @endif

    @if (null !== $editModal)
        <x-modal wire:model="editModal">
            <x-slot name="title">
                {{ __('Edit User') }}
            </x-slot>

            <x-slot name="content">
                <form wire:submit.prevent="update">
                    <div class="flex flex-wrap -mx-2 mb-3">
                        <div class="md:w-1/2 sm:w-full px-3">
                            <x-label for="name" :value="__('Name')" required />
                            <x-input id="name" class="block mt-1 w-full" type="text"
                                wire:model.defer="user.name" required />
                            <x-input-error :messages="$errors->get('user.name')" class="mt-2" />
                        </div>

                        <div class="md:w-1/2 sm:w-full px-3">
                            <x-label for="phone" :value="__('Phone')" required />
                            <x-input id="phone" class="block mt-1 w-full" required type="text"
                                wire:model.defer="user.phone" />
                            <x-input-error :messages="$errors->get('user.phone')" class="mt-2" />
                        </div>

                        <div class="md:w-1/2 sm:w-full px-3">
                            <label for="role">{{ __('Role') }} <span class="text-red-500">*</span></label>
                            <select wire:model.defer="user.role"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                name="role" id="role" required>
                                <option value="" selected disabled>{{ __('Select Role') }}</option>
                              
                            </select>
                        </div>

                        <div class="md:w-1/2 sm:w-full px-3">
                            <x-label for="password" :value="__('Password')" />
                            <x-input id="password" class="block mt-1 w-full" type="password"
                                wire:model.defer="user.password" />
                            <x-input-error :messages="$errors->get('user.password')" class="mt-2" />
                        </div>

                        <div class="md:w-1/2 sm:w-full px-3">
                            <x-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                wire:model.defer="user.password_confirmation" />
                            <x-input-error :messages="$errors->get('user.password_confirmation')" class="mt-2" />
                        </div>

                        <x-accordion>
                            <x-slot name="title">
                                {{ __('Details') }}
                            </x-slot>

                            <x-slot name="content">
                                <div class="md:w-1/2 sm:w-full px-3">
                                    <x-label for="email" :value="__('Email')" />
                                    <x-input id="email" class="block mt-1 w-full" type="email"
                                        wire:model.defer="user.email" />
                                    <x-input-error :messages="$errors->get('user.email')" class="mt-2" />
                                </div>

                                <div class="md:w-1/2 sm:w-full px-3">
                                    <x-label for="address" :value="__('Address')" />
                                    <x-input id="address" class="block mt-1 w-full" type="text"
                                        wire:model.defer="user.address" />
                                    <x-input-error :messages="$errors->get('user.address')" class="mt-2" />
                                </div>

                                <div class="md:w-1/2 sm:w-full px-3">
                                    <x-label for="city" :value="__('City')" />
                                    <x-input id="city" class="block mt-1 w-full" type="text"
                                        wire:model.defer="user.city" />
                                    <x-input-error :messages="$errors->get('user.city')" class="mt-2" />
                                </div>

                                <div class="md:w-1/2 sm:w-full px-3">
                                    <x-label for="tax_number" :value="__('Tax Number')" />
                                    <x-input id="tax_number" class="block mt-1 w-full" type="text"
                                        wire:model.defer="user.tax_number" />
                                    <x-input-error :messages="$errors->get('user.tax_number')" for="" class="mt-2" />
                                </div>
                            </x-slot>
                        </x-accordion>

                        <div class="w-full px-3">
                            <x-button primary type="submit" class="w-full text-center"
                                wire:loading.attr="disabled">
                                {{ __('Update') }}
                            </x-button>
                        </div>
                    </div>
                </form>
            </x-slot>
        </x-modal>
    @endif

    <livewire:users.create />

</div>

@push('scripts')
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
