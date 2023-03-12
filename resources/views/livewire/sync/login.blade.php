<div>
    <x-modal wire:model="loginModal">
        <x-slot name="title">
            {{ __('Login credentials') }}
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="authentificate">
                <div class="w-full flex flex-wrap justify-center py-10 px-4">
                    <div class="w-1/2 px-2">
                        <label for="email">{{ __('Email') }}</label>
                        <x-input type="email" id="email" required wire:model.lazy="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="w-1/2 px-2">
                        <label for="password">{{ __('Password') }}</label>
                        <x-input type="password" id="password" required wire:model.lazy="password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <x-button primary type="submit">
                        {{ __('Login') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
