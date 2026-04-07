<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Edit Role') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit="update" class="space-y-4">
                <div>
                    <x-label for="name" :value="__('Name')" />
                    <x-input type="text" id="name" wire:model="form.name" class="w-full" />
                    @error('form.name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div x-data="{
                    selectAll: false,
                    permissions: @entangle('form.permissions'),
                    toggleAll() {
                        if (this.selectAll) {
                            this.permissions = {{ $this->permission_groups->flatten()->pluck('id')->map(fn($id) => (string) $id)->toJson() }};
                        } else {
                            this.permissions = [];
                        }
                    }
                }">
                    <x-label for="permissions" :value="__('Permissions')" />
                    <div class="flex items-center justify-center w-full gap-4 mb-3">
                        <div>
                            <input type="checkbox" id="select-all" x-model="selectAll" x-on:change="toggleAll">
                            <label for="select-all" class="ml-2">{{ __('Select All') }}</label>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach ($this->permission_groups as $group => $permissions)
                            <div class="p-4 border rounded">
                                <h3 class="mb-2 text-lg font-semibold capitalize">{{ $group }}</h3>
                                <div class="py-2 grid grid-cols-3 gap-6">
                                    @foreach ($permissions as $permission)
                                        <div>
                                            <input type="checkbox" id="permission-{{ $permission->id }}"
                                                x-model="permissions" value="{{ $permission->id }}">
                                            <label for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('form.permissions')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
