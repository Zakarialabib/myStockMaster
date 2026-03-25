<div>
    @section('title', __('Printers'))

    <x-page-container>
        <x-slot name="breadcrumbs">
            <x-breadcrumb :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('Printers'), 'url' => route('printers.index')],
            ]" />
        </x-slot>

        <x-slot name="actions">
            <x-button primary type="button" wire:click="$dispatch('createModal')">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Create Printer') }}
            </x-button>
        </x-slot>

        <x-slot name="filters">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <x-label for="perPage" :value="__('Per Page')" class="text-sm font-medium text-gray-700" />
                    <select wire:model.live="perPage" id="perPage"
                        class="w-20 block p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($this->selectedCount)
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">
                            {{ $this->selectedCount }} {{ __('selected') }}
                        </span>
                        <x-button danger wire:click="deleteSelected" size="sm">
                            <i class="fas fa-trash mr-1"></i>
                            {{ __('Delete Selected') }}
                        </x-button>
                    </div>
                @endif

                <div class="flex-1 max-w-md">
                    <x-input wire:model.live.debounce.500ms="search" placeholder="{{ __('Search printers...') }}"
                        class="w-full" />
                </div>
            </div>
        </x-slot>
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <x-table>
                <x-slot name="thead">
                    <x-table.th class="w-12 text-center">
                        <x-input type="checkbox" wire:model.live="selectPage" class="rounded" />
                    </x-table.th>
                    <x-table.th sortable multi-column wire:click="sortingBy('name')" :direction="$sorts['name'] ?? null" class="text-left">
                        {{ __('Name') }}
                    </x-table.th>
                    <x-table.th sortable multi-column wire:click="sortingBy('connection_type')" :direction="$sorts['connection_type'] ?? null"
                        class="text-left">
                        {{ __('Connection Type') }}
                    </x-table.th>
                    <x-table.th sortable multi-column wire:click="sortingBy('capability_profile')" :direction="$sorts['capability_profile'] ?? null"
                        class="text-left">
                        {{ __('Capability Profile') }}
                    </x-table.th>
                    <x-table.th sortable multi-column wire:click="sortingBy('char_per_line')" :direction="$sorts['char_per_line'] ?? null"
                        class="text-center">
                        {{ __('Char/Line') }}
                    </x-table.th>
                    <x-table.th class="text-center">
                        {{ __('Actions') }}
                    </x-table.th>
                </x-slot>
                <x-table.tbody>
                    @forelse ($printers as $printer)
                        <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $printer->id }}"
                            class="hover:bg-gray-50 transition-colors">
                            <x-table.td class="text-center">
                                <x-input type="checkbox" value="{{ $printer->id }}" wire:model.live="selected"
                                    class="rounded" />
                            </x-table.td>
                            <x-table.td class="font-medium text-gray-900">
                                <div class="flex items-center">
                                    <i class="fas fa-print text-gray-400 mr-3"></i>
                                    <div>
                                        <div class="font-semibold">{{ $printer->name }}</div>
                                        @if ($printer->ip_address)
                                            <div class="text-sm text-gray-500">
                                                {{ $printer->ip_address }}{{ $printer->port ? ':' . $printer->port : '' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <x-badge
                                    type="{{ $printer->connection_type === 'network' ? 'primary' : 'secondary' }}">
                                    <i
                                        class="fas fa-{{ $printer->connection_type === 'network' ? 'wifi' : 'usb' }} mr-1"></i>
                                    {{ ucfirst($printer->connection_type) }}
                                </x-badge>
                            </x-table.td>
                            <x-table.td class="text-gray-600">
                                {{ $printer->capability_profile }}
                            </x-table.td>
                            <x-table.td class="text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $printer->char_per_line }}
                                </span>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <x-button alert wire:click="showModal({{ $printer->id }})"
                                        wire:loading.attr="disabled" size="sm">
                                        <i class="fas fa-eye"></i>
                                    </x-button>

                                    <x-button primary wire:click="editModal({{ $printer->id }})"
                                        wire:loading.attr="disabled" size="sm">
                                        <i class="fas fa-edit"></i>
                                    </x-button>

                                    <x-button danger wire:click="delete({{ $printer->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this printer?') }}"
                                        wire:loading.attr="disabled" size="sm">
                                        <i class="fas fa-trash"></i>
                                    </x-button>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="6" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i class="fas fa-print text-4xl text-gray-300"></i>
                                    <p class="text-gray-500 text-lg font-medium">{{ __('No printers found') }}</p>
                                    <p class="text-gray-400 text-sm">
                                        {{ __('Get started by adding your first printer') }}</p>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        </div>

        <div class="mt-6">
            {{ $printers->links() }}
        </div>
    </x-page-container>

    <x-modal wire:model.live="showModal">
        <x-slot name="title">
            {{ __('Show printer') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col">
                <div class="flex flex-col">
                    <x-label for="printer.name" :value="__('Name')" />
                    <x-input id="name" class="block mt-1 w-full" required type="text" disabled
                        wire:model="printer.name" />
                </div>
                <div class="flex flex-col">
                    <x-label for="printer.connection_type" :value="__('Connection type')" />
                    <x-input id="connection_type" class="block mt-1 w-full" type="text" disabled
                        wire:model="printer.connection_type" />
                </div>
                <div class="flex flex-col">
                    <x-label for="printer.capability_profile" :value="__('Capability profile')" />
                    <x-input id="capability_profile" class="block mt-1 w-full" type="text" disabled
                        wire:model="printer.capability_profile" />
                </div>
                <div class="flex flex-col">
                    <x-label for="printer.char_per_line" :value="__('Char per line')" />
                    <x-input id="char_per_line" class="block mt-1 w-full" type="text" disabled
                        wire:model="printer.char_per_line" />
                </div>
                <div class="flex flex-col">
                    <x-label for="printer.ip_address" :value="__('Ip address')" />
                    <x-input id="ip_address" class="block mt-1 w-full" type="text" disabled
                        wire:model="printer.ip_address" />
                </div>
                <div class="flex flex-col">
                    <x-label for="printer.port" :value="__('Port')" />
                    <x-input id="port" class="block mt-1 w-full" type="text" disabled
                        wire:model="printer.port" />
                </div>
                <div class="flex flex-col">
                    <x-label for="printer.path" :value="__('Path')" />
                    <x-input id="path" class="block mt-1 w-full" type="text" disabled
                        wire:model="printer.path" />
                </div>
            </div>
        </x-slot>
    </x-modal>

    <x-modal wire:model="openModal">
        <x-slot name="title">
            {{ __('Edit printer') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit="update">
                <div class="flex flex-wrap mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" required type="text"
                            wire:model="printer.name" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.connection_type" :value="__('Connection type')" />
                        <x-input id="connection_type" class="block mt-1 w-full" type="text"
                            wire:model="printer.connection_type" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.capability_profile" :value="__('Capability profile')" />
                        <x-input id="capability_profile" class="block mt-1 w-full" type="text"
                            wire:model="printer.capability_profile" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.char_per_line" :value="__('char per line')" />
                        <x-input id="char_per_line" class="block mt-1 w-full" type="text"
                            wire:model="printer.char_per_line" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.ip_address" :value="__('Ip address')" />
                        <x-input id="ip_address" class="block mt-1 w-full" type="text"
                            wire:model="printer.ip_address" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.port" :value="__('Port')" />
                        <x-input id="port" class="block mt-1 w-full" type="text"
                            wire:model="printer.port" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="printer.path" :value="__('Path')" />
                        <x-input id="path" class="block mt-1 w-full" type="text"
                            wire:model="printer.path" />
                    </div>
                </div>

                <div class="w-full px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>

    <livewire:printer.create />
</div>
