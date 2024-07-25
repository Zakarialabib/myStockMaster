<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Currency') }} {{ $currency?->name }}
        </x-slot>

        <x-slot name="content">
            <div class="grid md:grid-cols-2 grid-cols-1 gap-4 px-3">
                <div>
                    <x-label for="name" :value="__('Name')" />
                    {{ $currency?->name }}
                </div>
                <div>
                    <x-label for="code" :value="__('Code')" />
                    {{ $currency?->code }}
                </div>
                <div>
                    <x-label for="symbol" :value="__('Symbol')" />
                    {{ $currency?->symbol }}
                </div>
                <div>
                    <x-label for="rate" :value="__('Rate')" />
                    {{ $currency?->rate }}
                </div>
            </div>
        </x-slot>
    </x-modal>
</div>
