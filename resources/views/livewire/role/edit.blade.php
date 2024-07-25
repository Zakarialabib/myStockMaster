<div>
    <x-modal wire:model="openModal">
        <x-slot name="title">
            {{ __('Edit Role') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit="update">
                <div class="w-full px-3">
                    <x-label for="name" :value="__('Name')" />
                    <x-input type="text" id="name" wire:model="role.name" />
                    @error('role.name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full px-3">
                    <x-label for="permissions" :value="__('Permissions')" />
                    <div class="flex items-center justify-center w-full gap-4 mb-3">
                        <div>
                            <input type="checkbox" id="select-all" wire:click="selectAllPermissions"
                                {{ $this->isAllSelected ? 'checked' : '' }}>
                            <label for="select-all" class="ml-2">{{ __('Select All') }}</label>
                        </div>

                        <div>
                            <input type="checkbox" id="deselectAll" wire:click="deselectAllPermissions"
                                {{ $this->isNoneSelected ? 'checked' : '' }}>
                            <label for="deselectAll" class="ml-2">{{ __('Deselect All') }}</label>
                        </div>
                    </div>
                    <div class="py-2 grid grid-cols-3 gap-6">
                        @foreach ($this->permissions as $permission)
                            <div>
                                <input type="checkbox" id="permission-{{ $permission->id }}"
                                    wire:model.live="selectedPermissions" value="{{ $permission->id }}"
                                    {{ in_array($permission->id, $selectedPermissions) ? 'checked' : '' }}>
                                <label for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedPermissions')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
