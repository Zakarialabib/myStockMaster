<div>
    <x-table>
        <x-slot name="thead">
            <x-table.th>#</x-table.th>
            <x-table.th>{{ __('Language') }}</x-table.th>
            <x-table.th>{{ __('Status') }}</x-table.th>
            <x-table.th>{{ __('Default') }}</x-table.th>
            <x-table.th>{{ __('Actions') }}</x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse ($languages as $language)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $language['id'] }}">
                    <x-table.td>#</x-table.td>
                    <x-table.td>{{ $language['name'] }}</x-table.td>
                    <x-table.td>
                        @if ($language['status'] == true)
                            <x-badge type="primary">
                                {{ __('Active') }}
                            </x-badge>
                        @elseif($language['status'] == false)
                            <x-badge type="secondary">
                                {{ __('Inactive') }}
                            </x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        @if ($language['is_default'] == true)
                            <x-badge type="primary">
                                {{ __('Yes') }}
                            </x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        @if ($language['is_default'] == false)
                            <x-button type="button" secondary wire:click="onSetDefault( {{ $language['id'] }} )">
                                {{ __('Set as Default') }}</x-button>
                        @endif

                        <x-button success href="{{ route('translation.index', $language['code']) }}">
                            {{ __('Translate') }}
                        </x-button>

                        <x-button type="button" primary wire:click="sync({{ $language['id'] }})">
                            {{ __('Sync') }}
                        </x-button>

                        <x-button success type="button" wire:click="$emit('editLanguage', {{ $language['id'] }}) ">
                            <i class="fas fa-edit"></i>
                        </x-button>

                        <x-button danger type="button" wire:click="$emit('deleteModal', {{ $language['id'] }})">
                            <i class="fas fa-trash"></i>
                        </x-button>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td class="">{{ __('No record found') }}</x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <!-- Create Language-->
    @livewire('language.create')

    <!-- Update Language-->
    @livewire('language.edit', ['language' => $language])
    
    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                window.livewire.on('deleteModal', languageId => {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.livewire.emit('delete', languageId)
                        }
                    })
                })
            })
        </script>
    @endpush

</div>
