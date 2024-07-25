<div>
    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create Currency') }}
        </x-slot>

        <x-slot name="content">

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="currency.name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model.lazy="currency.name" />
                        <x-input-error :messages="$errors->get('currency.name')" for="name" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="currency.code" :value="__('Code')" required />
                        <x-input id="code" class="block mt-1 w-full" type="text"
                            wire:model.lazy="currency.code" />
                        <x-input-error :messages="$errors->get('currency.code')" for="code" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="currency.symbol" :value="__('Symbol')" required />
                        <x-input id="symbol" class="block mt-1 w-full" type="text"
                            wire:model.lazy="currency.symbol" />
                        <x-input-error :messages="$errors->get('currency.symbol')" for="name" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="currency.exchange_rate" :value="__('Exchange Rate')" required />
                        <x-input id="exchange_rate" class="block mt-1 w-full" type="text"
                            wire:model.lazy="currency.exchange_rate" />
                        <x-input-error :messages="$errors->get('currency.exchange_rate')" for="name" class="mt-2" />
                    </div>
                </div>
                <div class="w-full px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
