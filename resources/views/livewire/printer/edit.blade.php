<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit printer') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit="update">
                <div class="flex flex-wrap mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model="printer.name" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.connection_type" :value="__('Connection type')" />
                        <x-select id="connection_type" class="block mt-1 w-full" wire:model="printer.connection_type">
                            @foreach ($connection_types as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.capability_profile" :value="__('Capability profile')" />
                        <x-select id="capability_profile" class="block mt-1 w-full"
                            wire:model="printer.capability_profile">
                            @foreach ($capability_profiles as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.char_per_line" :value="__('Char per line')" />
                        <x-input id="char_per_line" class="block mt-1 w-full" type="number"
                            wire:model="printer.char_per_line" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.ip_address" :value="__('IP address')" />
                        <x-input id="ip_address" class="block mt-1 w-full" type="text"
                            wire:model="printer.ip_address" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.port" :value="__('Port')" />
                        <x-input id="port" class="block mt-1 w-full" type="number" wire:model="printer.port" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.path" :value="__('Path')" />
                        <x-input id="path" class="block mt-1 w-full" type="text" wire:model="printer.path" />
                    </div>
                </div>

                <div class="w-full px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
