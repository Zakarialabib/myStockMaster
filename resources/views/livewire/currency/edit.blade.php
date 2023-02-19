<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Currency') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="update">
                <div class="flex flex-col">
                    <div class="flex flex-col">
                        <x-label for="currency.name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model.lazy="currency.name" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="currency.code" :value="__('Code')" />
                        <x-input id="code" class="block mt-1 w-full" type="text"
                            wire:model.lazy="currency.code" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="currency.symbol" :value="__('Symbol')" />
                        <x-input id="symbol" class="block mt-1 w-full" type="text"
                            wire:model.lazy="currency.symbol" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="currency.exchange_rate" :value="__('Rate')" />
                        <x-input id="exchange_rate" class="block mt-1 w-full" type="text"
                            wire:model.lazy="currency.exchange_rate" />
                    </div>
                </div>

                <div class="w-full px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:click="update"
                        wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
