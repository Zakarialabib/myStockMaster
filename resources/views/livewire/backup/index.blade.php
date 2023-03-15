<div>
    <div class="grid gap-4 grid-cols-3 px-4">
        <x-button primary type="button" wire:click="generate" class="w-full">
            {{ __('Create Backup') }}
        </x-button>
        <x-button primary type="button" wire:click="cleanBackups">{{ __('clean Backups') }}</x-button>
        <x-button primary type="button" wire:click="settingsModal" class="w-full">
            {{ __('Settings') }}
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
                                        {{ __('Download') }}
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
    <x-modal wire:model="settingsModal">
        <x-slot name="title">
            {{ __('Backup settings') }}
        </x-slot>
        <x-slot name="content">
            {{-- error message --}}
            <form wire:submit.prevent="updateSettigns">
                <div class="w-full flex flex-wrap px-2">
                    <div class="w-full px-2 my-4">
                        <label for="backup_status">{{ __('Backup status') }}</label>
                        <x-input type="text" type="checkbox" wire:model="backup_status" />
                        {{-- error handle --}}
                    </div>

                    <div class="w-full px-2 ">
                        <label for="backup_status">{{ __('Backup status') }}</label>
                        <select wire:model="backup_schedule" name="backup_schedule">
                             @foreach(\App\Enums\BackupSchedule::values() as $key=>$value)
                                 <option value="{{ $key }}">{{ $value }}</option>
                             @endforeach
                        </select>
                        {{-- error handle --}}
                    </div>
                    <div class="w-full justify-center my-4 space-x-2">
                        <x-button primary type="submit">{{ __('save') }}</x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
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
