<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show User') }} - {{ $user->name }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap -mx-2 mb-3">
                <div class="md:w-1/2 sm:w-full px-3">
                    <x-label for="name" :value="__('Name')" />
                    <p class="block mt-1 w-full">
                        {{ $user->name }}
                    </p>
                </div>

                <div class="md:w-1/2 sm:w-full px-3">
                    <x-label for="phone" :value="__('Phone')" />
                    <p class="block mt-1 w-full">
                        {{ $user->phone }}
                    </p>
                </div>

                <div class="md:w-1/2 sm:w-full px-3">
                    <x-label for="email" :value="__('Email')" />
                    <p class="block mt-1 w-full">
                        {{ $user->email }}
                    </p>
                </div>
            </div>
        </x-slot>
    </x-modal>
</div>
