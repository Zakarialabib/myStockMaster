<div>
    <x-modal wire:model="importModal">
        <x-slot name="title">
            {{ __('Import Excel') }}
        </x-slot>

        <x-slot name="content">
            <div x-data="{
                openTab: 1,
                activeClasses: 'border rounded-t text-purple-500',
                inactiveClasses: 'text-purple-600 hover:text-purple-800'
            }" class="p-4">
                <ul class="flex mb-0 list-none flex-wrap pt-3 flex-row border-b">
                    <li @click="openTab = 1" :class="{ '-mb-px': openTab === 1 }"
                        class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                        <button :class="openTab === 1 ? activeClasses : inactiveClasses"
                            class="inline-block py-2 px-4 text-green-800 hover:text-green-400 font-semibold"
                            type="button">
                            {{ __('Import Products') }}
                        </button>
                    </li>
                    <li @click="openTab = 2" :class="{ '-mb-px': openTab === 2 }"
                        class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                        <button :class="openTab === 2 ? activeClasses : inactiveClasses"
                            class="inline-block py-2 px-4 text-green-800 hover:text-green-400 font-semibold"
                            type="button">
                            {{ __('Update products with Code') }}</button>
                    </li>
                </ul>
                <div class="w-full">
                    <div x-show="openTab === 1">

                        <div class="w-full py-2">
                            <x-button secondary wire:click="downloadSample"
                                class="text-center m-2 w-full py-2 px-4 text-green-800 hover:text-green-400 font-semibold"
                                type="button">
                                {{ __('Download Sample') }}</x-button>
                            <x-table-responsive>
                                <x-table.tr>
                                    <x-table.th>{{ __('Name') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge danger>
                                            {{ __('Required') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.th>{{ __('Description') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge danger>
                                            {{ __('Required') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.th>{{ __('Price') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge danger>
                                            {{ __('Required') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.th>{{ __('Old price') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge alert>
                                            {{ __('Optional') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.th>{{ __('Category name') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge danger>
                                            {{ __('Required') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.th>{{ __('Subcategory name') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge alert>
                                            {{ __('Optional') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>

                                <x-table.tr>
                                    <x-table.th>{{ __('Brand') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge alert>
                                            {{ __('Optional') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.th>{{ __('Image') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge danger>
                                            {{ __('Required') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>

                            </x-table-responsive>
                            <form wire:submit="import">
                                <div class="flex flex-wrap gap-4">
                                    <div class="w-1/2 my-4">
                                        <x-input id="file" class="block mt-1 w-full" type="file" name="file"
                                            wire:model="file" />
                                        <x-input-error :messages="$errors->get('file')" for="file" class="mt-2" />
                                    </div>
                                    <div class="w-1/2 my-2">
                                        <x-button primary type="submit" class="block" wire:loading.attr="disabled">
                                            {{ __('Import') }}
                                        </x-button>
                                        <span wire:loading.delay wire:target="import">
                                            {{ __('Loading...') }}
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div x-show="openTab === 2">
                        <div class="w-full py-2">
                            <x-button secondary wire:click="downloadSample"
                                class="text-center m-2 w-full py-2 px-4 text-green-800 hover:text-green-400 font-semibold"
                                type="button">
                                {{ __('Download Sample') }}</x-button>
                            <x-table-responsive>
                                <x-table.tr>
                                    <x-table.th>{{ __('Code') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge danger>
                                            {{ __('Required') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.th>{{ __('Price') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge danger>
                                            {{ __('Required') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>
                                <x-table.tr>
                                    <x-table.th>{{ __('Old Price') }}</x-table.th>
                                    <x-table.td>
                                        <x-badge warning>
                                            {{ __('Not required') }}
                                        </x-badge>
                                    </x-table.td>
                                </x-table.tr>
                            </x-table-responsive>
                            <form wire:submit="importUpdates">
                                <div class="w-full px-3 my-4">
                                    <x-input id="file" class="block mt-1 w-full" type="file" name="file"
                                        wire:model="file" />
                                    <x-input-error :messages="$errors->get('file')" for="file" class="mt-2" />
                                </div>

                                <div class="w-full px-3">
                                    <x-button primary type="submit" class="block" wire:loading.attr="disabled">
                                        {{ __('Import') }}
                                    </x-button>
                                    <span wire:loading.delay wire:target="importUpdates">
                                        {{ __('Loading...') }}
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-modal>
</div>


