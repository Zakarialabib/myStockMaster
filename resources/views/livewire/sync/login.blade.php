<div>
    <x-modal wire:model="loginModal">
        <x-slot name="title">
            {{ __('Login credentials') }}
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="authenticate">
                <div class="w-full flex flex-wrap justify-center align-center py-10 px-4">
                    <div class="w-full px-2 mt-4">
                        <x-label for="type" :value="__('Type')" />
                        <select wire:model.lazy="type" id="type" name="type"
                            class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500">
                            <option value="">{{ 'Select way to sync' }}</option>
                            @foreach (\App\Enums\IntegrationType::cases() as $type)
                                <option value="{{ $type->value }}">
                                    {{ __($type->name) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" for="type" class="mt-2" />
                    </div>

                    <div class="w-full px-2 mt-4">
                        <x-label for="store_url" :value="__('Store URL')" />
                        <x-input id="store_url" class="block mt-1 w-full" type="text" name="store_url"
                            wire:model.lazy="store_url" required />
                        <x-input-error :messages="$errors->get('store_url')" for="store_url" class="mt-2" />
                    </div>

                    <div class="w-full px-2 mt-4">
                        <label for="email">{{ __('Email') }}</label>
                        <x-input type="email" id="email" required wire:model.lazy="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="w-full px-2 mt-4">
                        <label for="password">{{ __('Password') }}</label>
                        <x-input type="password" id="password" required wire:model.lazy="password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <x-button primary type="submit" class="my-2">
                        {{ __('Login') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
