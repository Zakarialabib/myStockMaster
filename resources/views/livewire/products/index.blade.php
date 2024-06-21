<div>
    @section('title', __('Produtcs'))
    <x-theme.breadcrumb :title="__('Products List')" :parent="route('products.index')" :parentName="__('Products List')">
        <x-dropdown align="right" width="48" class="w-auto mr-2">
            <x-slot name="trigger" class="inline-flex">
                <x-button secondary type="button" class="text-white flex items-center">
                    <i class="fas fa-angle-double-down w-4 h-4"></i>
                </x-button>
            </x-slot>
            <x-slot name="content">
                @can('product_import')
                    <x-dropdown-link wire:click="importModal" wire:loading.attr="disabled">
                        {{ __('Excel Import') }}
                    </x-dropdown-link>
                @endcan
                @can('product_export')
                    <x-dropdown-link wire:click="exportAll" wire:loading.attr="disabled">
                        {{ __('PDF Export') }}
                    </x-dropdown-link>
                    <x-dropdown-link wire:click="downloadAll" wire:loading.attr="disabled">
                        {{ __('Excel Export') }}
                    </x-dropdown-link>
                @endcan
            </x-slot>
        </x-dropdown>
        @can('product_create')
            <x-button primary type="button" wire:click="dispatchTo('products.create', 'createModal')">
                {{ __('Create products') }}
            </x-button>
        @endcan
    </x-theme.breadcrumb>

    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model.live="perPage"
                class="w-20 block p-3 leading-5 bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($selected)
                @can('product_delete')
                    <x-button danger type="button" wire:click="deleteSelectedModal" class="ml-3">
                        <i class="fas fa-trash"></i>
                    </x-button>
                @endcan
            @endif
            @if ($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            <select wire:model.live="category_id" name="category_id" id="category_id"
                class="w-36 block py-2 px-3 ml-2 leading-5 bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                <option value=""> {{ __('Select Category') }} </option>
                @foreach ($this->categories as $index => $category)
                    <option value="{{ $index }}">{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <x-input wire:model.live.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>
                #
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">
                {{ __('Name') }}
            </x-table.th>
            <x-table.th>
                {{ __('Quantity') }}
            </x-table.th>
            <x-table.th>
                {{ __('Price') }}
            </x-table.th>
            <x-table.th>
                {{ __('Cost') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('category_id')" :direction="$sorts['category_id'] ?? null">
                {{ __('Category') }}
            </x-table.th>
            <x-table.th>
                {{ __('Warehouse') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>
        <x-table.tbody>
            @forelse($products as $product)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $product->id }}">
                    <x-table.td>
                        <input type="checkbox" value="{{ $product->id }}" wire:model.live="selected">
                    </x-table.td>
                    <x-table.td>
                        <button type="button" wire:click="dispatchTo('products.show','showModal',{{ $product->id }})"
                            class="whitespace-nowrap hover:text-blue-400 active:text-blue-400">
                            {{ $product->name }} <br>
                            <x-badge type="success">
                                {{ $product->code }}
                            </x-badge>
                        </button>
                    </x-table.td>
                    <x-table.td>{{ $product->total_quantity }}</x-table.td>
                    <x-table.td>{{ format_currency($product->average_price) }}</x-table.td>
                    <x-table.td>{{ format_currency($product->average_cost) }}</x-table.td>
                    <x-table.td>
                        <x-button type="button" warning x-on:click="$wire.category_id = {{ $product->category->id }}">
                            {{ $product->category->name }}
                            <small>
                                ({{ $product->ProductsByCategory($product->category->id) }})
                            </small>
                        </x-button>
                    </x-table.td>
                    <x-table.td>
                        <div class="flex flex-wrap">
                            @forelse ($product->warehouses as $warehouse)
                                <x-badge type="info"><small>{{ $warehouse->name }}</small></x-badge>
                            @empty
                                {{ __('No warehouse assigned') }}
                            @endforelse
                        </div>
                    </x-table.td>
                    <x-table.td>
                        <x-dropdown align="right" width="56">
                            <x-slot name="trigger" class="inline-flex">
                                <x-button primary type="button" class="text-white flex items-center">
                                    <i class="fas fa-angle-double-down"></i>
                                </x-button>
                            </x-slot>

                            <x-slot name="content">
                                @can('product_show')
                                    <x-dropdown-link
                                        wire:click="dispatchTo('products.show','showModal',{ id :'{{ $product->id }}'})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-eye"></i>
                                        {{ __('View') }}
                                    </x-dropdown-link>
                                @endcan
                                @if (settings()->telegram_channel)
                                    <x-dropdown-link wire:click="sendTelegram({{ $product->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-paper-plane"></i>
                                        {{ __('Send to telegram') }}
                                    </x-dropdown-link>
                                @endif
                                <x-dropdown-link wire:click="sendWhatsapp({{ $product->id }})"
                                    wire:loading.attr="disabled">
                                    <i class="fas fa-paper-plane"></i>
                                    {{ __('Send to Whatsapp') }}
                                </x-dropdown-link>
                                @can('product_update')
                                    <x-dropdown-link href="{{ route('product.edit', $product) }}"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('product_delete')
                                    <x-dropdown-link wire:click="deleteModal({{ $product->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-trash"></i>
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-dropdown>

                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="8" class="text-center">
                        {{ __('No products found') }}
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="p-4">
        <div class="pt-3">
            @if ($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $products->links() }}
        </div>
    </div>

    <!-- Show Modal -->
    @livewire('products.show', ['product' => $this->product], key('show-modal-' . $this->product?->id))
    <!-- End Show Modal -->

    <livewire:products.create />

    <livewire:products.import />
</div>
