<div>
    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="store">
                <div class="flex flex-wrap justify-center">
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <label for="title">{{ __('Title') }}</label>
                        <input type="text" class="form-control" id="title" wire:model="role.title">
                        @error('role.title')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-select-list
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required id="permissions" name="permissions" wire:model="permissions" :options="$this->permissions"
                            multiple />
                    </div>
                    <div class="w-full px-3">
                        <x-button primary type="submit" class="w-full text-center" 
                        wire:loading.attr="disabled">
                            {{ __('Save') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
