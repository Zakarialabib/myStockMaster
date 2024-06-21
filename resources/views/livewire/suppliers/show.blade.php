<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Supplier') }} {{ $supplier?->name }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap -mx-2 mb-3">
                <div class="md:w-1/2 sm:w-full px-3 mb-4 lg:mb-0">
                    <x-label for="name" :value="__('Name')" />
                    <p>{{ $supplier?->name }}</p>
                </div>

                <div class="md:w-1/2 sm:w-full px-3 mb-4 lg:mb-0">
                    <x-label for="phone" :value="__('Phone')" />
                    <p>{{ $supplier?->phone }}</p>
                </div>

                <div class="md:w-1/2 sm:w-full px-3 mb-4 lg:mb-0">
                    <x-label for="address" :value="__('Address')" />
                    <p>{{ $supplier?->address }}</p>
                </div>

                <div class="md:w-1/2 sm:w-full px-3 mb-4 lg:mb-0">
                    <x-label for="city" :value="__('City')" />
                    <p>{{ $supplier?->city }}</p>
                </div>

                <div class="md:w-1/2 sm:w-full px-3 mb-4 lg:mb-0">
                    <x-label for="tax_number" :value="__('Tax Number')" />
                    <p>{{ $supplier?->tax_number }}</p>
                </div>
            </div>
        </x-slot>
    </x-modal>

</div>
