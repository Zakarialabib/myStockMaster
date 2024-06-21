<div>
    <div class="grid gap-4 grid-cols-3 px-4">
        <x-button primary type="button" wire:click="generate">
            {{ __('Create Backup') }}
        </x-button>
        <x-button primary wire:click="backupToDrive">
            {{ __('Backup to Google Drive') }}</x-button>
        <a href="https://drive.google.com/drive/folders/{{ env('GOOGLE_DRIVE_FOLDER_ID') }}" target="_blank">
            {{ __('Open backup folder in Google Drive') }}
        </a>
        <x-button primary type="button" wire:click="cleanBackups">
            {{ __('Clean Backups') }}</x-button>
        <x-button primary type="button" wire:click="settingsModal">
            {{ __('Settings') }}
        </x-button>
    </div>

    <div x-data="{ activeTab: 'local' }">
        <ul class="flex border-b">
            <li class="-mb-px mr-1">
                <a class="inline-block border-l border-t border-r rounded-t py-2 px-4 text-blue-800 font-semibold hover:bg-blue-600 transition duration-300 ease-in-out"
                    href="#" :class="{ 'border-blue-500 bg-blue-600 text-white': activeTab === 'local' }"
                    x-on:click.prevent="activeTab = 'local'">
                    {{ __('Local') }}
                </a>
            </li>
            <li class="mr-1">
                <a class="inline-block py-2 px-4 text-blue-800 font-semibold hover:bg-blue-600 hover:text-white transition duration-300 ease-in-out"
                    href="#" :class="{ 'border-blue-500 bg-blue-600 text-white': activeTab === 'drive' }"
                    x-on:click.prevent="activeTab = 'drive'">
                    {{ __('Drive') }}
                </a>
            </li>
        </ul>
        <div class="py-4">
            <div x-show="activeTab === 'local'">
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
        </div>
        <div class="py-4">
            <div x-show="activeTab === 'drive'">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium">Google Drive Backups</h2>
                    <span class="text-gray-500 text-sm">You are connected to Google Drive backups</span>
                </div>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach ($this->contents as $content)
                            @if ($content['type'] === 'dir')
                                <li class="px-4 py-3">
                                    <div class="flex items-center">
                                        <span class="bg-gray-400 rounded-full h-2 w-2"></span>
                                        <span class="ml-3 font-medium">{{ $content['name'] }}</span>
                                    </div>
                                </li>
                            @elseif ($content['type'] === 'file')
                                <li class="px-4 py-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="bg-gray-400 rounded-full h-2 w-2"></span>
                                            <span class="ml-3 font-medium">{{ $content['path'] }}</span>
                                            <span class="ml-3 font-medium">
                                                {{ Helpers::formatBytes($content['fileSize']) }}
                                            </span>
                                            <span class="ml-3 font-medium">
                                                Last modified: {{ Helpers::formatDate($content['lastModified']) }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <x-modal wire:model.live="settingsModal">
        <x-slot name="title">
            {{ __('Backup settings') }}
        </x-slot>
        <x-slot name="content">
            {{-- error message --}}
            <form wire:submit="updateSettigns">
                <div class="w-full flex flex-wrap px-2">
                    <div class="w-1/2 px-2 my-4">
                        <label for="backup_status">{{ __('Backup status') }}</label>
                        <x-input type="text" type="checkbox" wire:model.live="backup_status" />
                        <x-input-error :messages="$errors->get('backup_status')" for="backup_status" class="mt-2" />
                    </div>

                    <div class="w-1/2 px-2 my-4">
                        <label for="backup_status">{{ __('Backup Schedule') }}</label>
                        <select wire:model.live="backup_schedule" name="backup_schedule">
                            @foreach (\App\Enums\BackupSchedule::cases() as $type)
                                <option value="{{ $type->value }}">
                                    {{ __($type->name) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('backup_schedule')" for="backup_schedule" class="mt-2" />
                    </div>
                    <div class="w-1/2 px-2 my-4">
                        <p>{{ __('Google Drive Configuration') }}</p>
                        <label for="clientId">{{ __('Client ID') }}</label>
                        <x-input id="clientId" type="text" wire:model.live="clientId" />

                        <label for="clientSecret">{{ __('Client Secret') }}</label>
                        <x-input id="clientSecret" type="text" wire:model.live="clientSecret" />

                        <label for="refreshToken">{{ __('Refresh Token') }}</label>
                        <x-input id="refreshToken" type="text" wire:model.live="refreshToken" />

                        <label for="folderId">{{ __('Folder ID') }}</label>
                        <x-input id="folderId" type="text" wire:model.live="folderId" />
                    </div>

                    <div class="w-full justify-center my-4 space-x-2">
                        <x-button primary type="submit">{{ __('Save') }}</x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>


@push('scripts')
    <script>
        document.addEventListener('livewire:init', function() {
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
                        window.Livewire.dispatch('delete', brandId)
                    }
                })
            })
        })
    </script>
@endpush
