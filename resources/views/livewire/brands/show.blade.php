<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Brand') }} - {{ $brand?->name }}
        </x-slot>

        <x-slot name="content">
            <div class="px-4 mx-auto mb-4">
                <div class="w-full mb-3">
                    <div class="flex justify-center px-3">
                        <img src="{{ asset('images/brands/' . $brand?->image) }}" alt="{{ $brand?->name }}"
                            class="w-32 h-32 rounded-full">
                    </div>
                </div>
                <div class="flex flex-row">
                    <div class="w-full px-4">
                        <x-table-responsive>
                            <x-table.tr>
                                <x-table.th>
                                    {{ __('Name') }}
                                </x-table.th>
                                <x-table.td>
                                    {{ $brand?->name }}
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>
                                    {{ __('Origin') }}
                                </x-table.th>
                                <x-table.td>
                                    {{ $brand?->origin }}
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>
                                    {{ __('Description') }}
                                </x-table.th>
                                <x-table.td>
                                    {!! $brand?->description !!}
                                </x-table.td>
                            </x-table.tr>
                        </x-table-responsive>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-modal>
</div>
