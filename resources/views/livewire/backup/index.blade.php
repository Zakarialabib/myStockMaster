<div>
    <div class="w-full px-4 block">
        <x-button primary type="button" wire:click="generate" class="w-full">
            {{ __('Create Backup') }}
        </x-button>
    </div>
    <div>
        <x-table>
            <x-slot name="thead">
                <x-table.th>
                    {{ __('Id') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Name') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Date') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Size') }}
                </x-table.th>
                <x-table.th>
                    {{ __('Actions') }}
                </x-table.th>
                </tr>
            </x-slot>
            <x-table.tbody>
                @forelse ($backups as $id=>$backup)
                    @php
                        $infoPath = pathinfo($backup);
                        $extension = $infoPath['extension'] ?? '';
                    @endphp
                    @if ($extension == 'zip')
                        <x-table.tr>
                            <x-table.td>
                                {{ $id }}
                            </x-table.td>
                            <x-table.td>
                                {{ basename($backup) }}
                            </x-table.td>
                            <x-table.td>
                                {{ Storage::size($backup) / 1000 }} KB
                            </x-table.td>
                            <x-table.td>
                                {{ \Carbon\Carbon::createFromTimestamp(Storage::lastModified($backup))->format("d M Y \\a\\t h:i a") }}
                            </x-table.td>
                            <x-table.td>
                                <div class="flex justify-start space-x-2">
                                    <x-button primary wire:click="downloadBackup('{{ $backup }}')"
                                        title="Download">
                                        {{__('Download')}}
                                    </x-button>
                                    <x-button danger wire:click="delete('{{ $backup }}')" type="button"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-trash"></i>
                                    </x-button>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endif
                @empty
                    <x-table.tr>
                        <x-table.td colspan="10" class="text-center">
                            {{ __('No entries found.') }}
                        </x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', brandId => {
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
                        window.livewire.emit('delete', brandId)
                    }
                })
            })
        })
    </script>
@endpush
