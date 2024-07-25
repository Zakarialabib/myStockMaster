<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Customer') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap">
                <div class="w-full sm:w-1/2 px-3 mb-6">
                    <x-label for="name" :value="__('Name')" />
                    <p>{{ $customer?->name }}</p>
                </div>

                <div class="w-full sm:w-1/2 px-3 mb-6">
                    <x-label for="phone" :value="__('Phone')" />
                    <p>{{ $customer?->phone }}</p>
                </div>

                <div class="w-full sm:w-1/2 px-3 mb-6">
                    <x-label for="email" :value="__('Email')" />
                    <p>{{ $customer?->email }}</p>
                </div>

                <div class="w-full sm:w-1/2 px-3 mb-6">
                    <x-label for="address" :value="__('Address')" />
                    <p>{{ $customer?->address }}</p>
                </div>

                <div class="w-full sm:w-1/2 px-3 mb-6">
                    <x-label for="city" :value="__('City')" />
                    <p>{{ $customer?->city }}</p>

                </div>
                <div class="w-full sm:w-1/2 px-3 mb-6">
                    <x-label for="tax_number" :value="__('Tax Number')" />
                    <p>{{ $customer?->tax_number }}</p>
                </div>
            </div>
        </x-slot>
    </x-modal>
</div>
