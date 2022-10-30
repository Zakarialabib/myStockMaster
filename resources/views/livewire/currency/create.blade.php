<div>
    <x-modal wire:model="createCurrency">
        <x-slot name="title">
            {{ __('Create Currency') }}
        </x-slot>

        <x-slot name="content">
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="currency.name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model.defer="currency.name" />
                    </div>
                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="currency.code" :value="__('Code')" required />
                        <x-input id="code" class="block mt-1 w-full" type="text"
                            wire:model.defer="currency.code" />
                    </div>
                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="currency.symbol" :value="__('Symbol')" required />
                        <x-input id="symbol" class="block mt-1 w-full" type="text"
                            wire:model.defer="currency.symbol" />
                    </div>
                    <div class="w-full lg:w-1/2 px-3 mb-6 lg:mb-0">
                        <x-label for="currency.exchange_rate" :value="__('Exchange Rate')" required />
                        <x-input id="exchange_rate" class="block mt-1 w-full" type="text"
                            wire:model.defer="currency.exchange_rate" />
                    </div>
                </div>
                <div class="w-full flex justify-start px-3">
                    <x-button secondary wire:click="$set('createCurrency', false)" wire:loading.attr="disabled">
                        {{ __('Close') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
