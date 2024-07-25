<div>
    <x-modal wire:model="createPrinter">
        <x-slot name="title">
            {{ __('Create Printer') }}
        </x-slot>

        <x-slot name="content">
            
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model.defer="printer.name" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.connection_type" :value="__('Connection type')" />
                        <x-input id="connection_type" class="block mt-1 w-full" type="text"
                            wire:model.defer="printer.connection_type" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.capability_profile" :value="__('Capability profile')" />
                        <x-input id="capability_profile" class="block mt-1 w-full" type="text"
                            wire:model.defer="printer.capability_profile" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.char_per_line" :value="__('char per line')" />
                        <x-input id="char_per_line" class="block mt-1 w-full" type="text"
                            wire:model.defer="printer.char_per_line" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.ip_address" :value="__('Ip address')" />
                        <x-input id="ip_address" class="block mt-1 w-full" type="text"
                            wire:model.defer="printer.ip_address" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.port" :value="__('Port')" />
                        <x-input id="port" class="block mt-1 w-full" type="text" wire:model.defer="printer.port" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="printer.path" :value="__('Path')" />
                        <x-input id="path" class="block mt-1 w-full" type="text" wire:model.defer="printer.path" />
                    </div>
                </div>
                <div class="w-full px-3">
                    <x-button secondary class="w-full text-center" 
                            wire:click="create" wire:loading.attr="disabled">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
