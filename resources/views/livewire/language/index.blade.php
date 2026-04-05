<div>
    @section('title', __('Languages'))

    <x-page-container :title="__('Languages List')"
        :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Languages List'), 'url' => route('languages.index')]]"
        :show-filters="true">
        <x-slot name="actions">
            <x-button primary type="button" wire:click="dispatchTo('language.create', 'createModal')">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Create Language') }}
            </x-button>
        </x-slot>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <x-table>
                <x-slot name="thead">
                    <x-table.th class="w-16 text-center">#</x-table.th>
                    <x-table.th class="text-left">{{ __('Language') }}</x-table.th>
                    <x-table.th class="text-center">{{ __('Status') }}</x-table.th>
                    <x-table.th class="text-center">{{ __('Default') }}</x-table.th>
                    <x-table.th class="text-center">{{ __('Actions') }}</x-table.th>
                </x-slot>
                <x-table.tbody>
                    @forelse ($this->languages as $index => $language)
                    <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $language->id }}" class="hover:bg-gray-50 transition-colors">
                        <x-table.td class="text-center text-gray-500 font-medium">
                            {{ $index + 1 }}
                        </x-table.td>
                        <x-table.td class="font-medium text-gray-900">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">{{ $language->flag ?? '🌐' }}</span>
                                <div>
                                    <div class="font-semibold">{{ $language->name }}</div>
                                    <div class="text-sm text-gray-500">{{ strtoupper($language->code) }}</div>
                                </div>
                            </div>
                        </x-table.td>
                        <x-table.td class="text-center">
                            @if ($language->status == true)
                            <x-badge type="success">
                                <i class="fas fa-check mr-1"></i>
                                {{ __('Active') }}
                            </x-badge>
                            @else
                            <x-badge type="secondary">
                                <i class="fas fa-times mr-1"></i>
                                {{ __('Inactive') }}
                            </x-badge>
                            @endif
                        </x-table.td>
                        <x-table.td class="text-center">
                            @if ($language->is_default == true)
                            <x-badge type="primary">
                                <i class="fas fa-star mr-1"></i>
                                {{ __('Default') }}
                            </x-badge>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </x-table.td>
                        <x-table.td class="text-center">
                            <div class="flex items-center justify-center space-x-2">
                                @if ($language->is_default == false)
                                <x-button type="button" secondary size="sm" wire:click="onSetDefault( {{ $language->id }} )">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ __('Set Default') }}
                                </x-button>
                                @endif

                                <x-button success href="{{ route('languages.translation', $language->code) }}" size="sm">
                                    <i class="fas fa-language mr-1"></i>
                                    {{ __('Translate') }}
                                </x-button>

                                <x-button type="button" primary size="sm" wire:click="sync({{ $language->id }})">
                                    <i class="fas fa-sync mr-1"></i>
                                    {{ __('Sync') }}
                                </x-button>

                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger" class="inline-flex">
                                        <x-button primary type="button" size="sm" class="text-white flex items-center">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </x-button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link
                                            wire:click="dispatchTo('language.edit','editModal', {id: '{{ $language->id }}'})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-edit mr-2"></i>
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link
                                            wire:click="dispatch('deleteModal', {{ $language->id }})"
                                            wire:loading.attr="disabled"
                                            class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash mr-2"></i>
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                    @empty
                    <x-table.tr>
                        <x-table.td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <i class="fas fa-language text-4xl text-gray-300"></i>
                                <p class="text-gray-500 text-lg font-medium">{{ __('No languages found') }}</p>
                                <p class="text-gray-400 text-sm">{{ __('Get started by adding your first language') }}</p>
                            </div>
                        </x-table.td>
                    </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>
        </div>
    </x-page-container>

    <!-- Create Language-->
    @livewire('language.create')

    <!-- Update Language-->
    @livewire('language.edit', ['language' => $language])
</div>