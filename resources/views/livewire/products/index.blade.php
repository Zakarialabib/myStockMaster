<div>
    @section('title', __('Products'))
    <x-page-container title="{{ __('Products') }}" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Products')]]" :show-filters="true">
        <x-slot name="actions">
            @can('product_import')
                <x-button wire:click="importModal" variant="secondary" icon="fas fa-upload">
                    {{ __('Excel Import') }}
                </x-button>
            @endcan
            @can('product_export')
                <x-button wire:click="exportAll" variant="success" icon="fas fa-download">
                    {{ __('PDF Export') }}
                </x-button>
                <x-button wire:click="downloadAll" variant="success" icon="fas fa-download">
                    {{ __('Excel Export') }}
                </x-button>
            @endcan
            @can('product_create')
                <x-button wire:click="$dispatchTo('products.create', 'createModal')" variant="primary" icon="fas fa-plus">
                    {{ __('Create Product') }}
                </x-button>
            @endcan
        </x-slot>

        <x-slot name="filters">
            <x-datatable.product-filters 
                :per-page="$perPage"
                :pagination-options="$paginationOptions"
                :search="$search"
                :category-id="$category_id"
                :categories="$this->categories"
                :filter-availability="$filterAvailability"
                :filter-seasonality="$filterSeasonality"
                :selected-count="$this->selectedCount ?? count($selected ?? [])"
                :can-delete="auth()->user()->can('product_delete')"
                search-placeholder="{{ __('Search products...') }}"
            />
        </x-slot>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            <x-table>
                <x-slot name="thead">
                    <x-table.th
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-hashtag text-gray-400 dark:text-gray-500 w-3 h-3"></i>
                            <span>{{ __('ID') }}</span>
                        </div>
                    </x-table.th>
                    <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['name'] ?? null"
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-tag text-gray-400 dark:text-gray-500 w-3 h-3"></i>
                            <span>{{ __('Name') }}</span>
                            @if ($sorts['name'] ?? null)
                                <i
                                    class="fas fa-sort-{{ $sorts['name'] === 'asc' ? 'up' : 'down' }} text-blue-500 w-3 h-3"></i>
                            @else
                                <i class="fas fa-sort text-gray-400 w-3 h-3"></i>
                            @endif
                        </div>
                    </x-table.th>
                    <x-table.th
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-boxes text-gray-400 dark:text-gray-500 w-3 h-3"></i>
                            <span>{{ __('Quantity') }}</span>
                        </div>
                    </x-table.th>
                    <x-table.th
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-dollar-sign text-gray-400 dark:text-gray-500 w-3 h-3"></i>
                            <span>{{ __('Price') }}</span>
                        </div>
                    </x-table.th>
                    <x-table.th
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-coins text-gray-400 dark:text-gray-500 w-3 h-3"></i>
                            <span>{{ __('Cost') }}</span>
                        </div>
                    </x-table.th>
                    <x-table.th
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-percentage text-gray-400 dark:text-gray-500 w-3 h-3"></i>
                            <span>{{ __('Discounted Price') }}</span>
                        </div>
                    </x-table.th>
                    <x-table.th sortable wire:click="sortBy('category_id')" :direction="$sorts['category_id'] ?? null"
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-folder text-gray-400 dark:text-gray-500 w-3 h-3"></i>
                            <span>{{ __('Category') }}</span>
                            @if ($sorts['category_id'] ?? null)
                                <i
                                    class="fas fa-sort-{{ $sorts['category_id'] === 'asc' ? 'up' : 'down' }} text-blue-500 w-3 h-3"></i>
                            @else
                                <i class="fas fa-sort text-gray-400 w-3 h-3"></i>
                            @endif
                        </div>
                    </x-table.th>
                    <x-table.th
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-warehouse text-gray-400 dark:text-gray-500 w-3 h-3"></i>
                            <span>{{ __('Warehouse') }}</span>
                        </div>
                    </x-table.th>
                    <x-table.th
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-cogs text-gray-400 dark:text-gray-500 w-3 h-3"></i>
                            <span>{{ __('Actions') }}</span>
                        </div>
                    </x-table.th>
                </x-slot>
                <x-table.tbody>
                    @forelse($products as $product)
                        <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $product->id }}"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <x-table.td
                                class="px-6 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <input type="checkbox" value="{{ $product->id }}" wire:model.live="selected"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 transition-colors">
                                </div>
                            </x-table.td>
                            <x-table.td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <button type="button"
                                    wire:click="$dispatchTo('products.show','showModal',{{ $product->id }})"
                                    class="group text-left hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg p-2 -m-2 transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                                                <i class="fas fa-box text-blue-600 dark:text-blue-400 w-4 h-4"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-medium text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                                {{ $product->name }}
                                            </p>
                                            <div class="mt-1">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    <i class="fas fa-barcode w-3 h-3 mr-1"></i>
                                                    {{ $product->code }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </x-table.td>
                            <x-table.td
                                class="px-6 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <i class="fas fa-cubes w-3 h-3 mr-1"></i>
                                        {{ $product->total_quantity }}
                                    </span>
                                </div>
                            </x-table.td>
                            <x-table.td
                                class="px-6 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ format_currency($product->average_price) }}
                                </div>
                            </x-table.td>
                            <x-table.td
                                class="px-6 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ format_currency($product->average_cost) }}
                                </div>
                            </x-table.td>
                            <x-table.td
                                class="px-6 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700">
                                <div class="text-sm font-medium text-green-600 dark:text-green-400">
                                    {{ format_currency($product->getDiscountedPrice()) }}
                                </div>
                            </x-table.td>

                            <x-table.td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <button type="button" x-on:click="$wire.category_id = {{ $product->category->id }}"
                                    class="group inline-flex items-center px-3 py-2 text-sm font-medium text-orange-700 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:hover:bg-orange-900/50 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <i
                                        class="fas fa-folder w-3 h-3 mr-2 text-orange-600 dark:text-orange-500 group-hover:text-orange-700 dark:group-hover:text-orange-400 transition-colors"></i>
                                    <div class="text-left">
                                        <div class="font-medium">{{ $product->category->name }}</div>
                                        <div
                                            class="text-xs text-orange-600 dark:text-orange-500 group-hover:text-orange-700 dark:group-hover:text-orange-400">
                                            ({{ $product->ProductsByCategory($product->category->id) }})
                                            {{ __('products') }}
                                        </div>
                                    </div>
                                </button>
                            </x-table.td>
                            <x-table.td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($product->warehouses as $warehouse)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            <i class="fas fa-warehouse w-3 h-3 mr-1"></i>
                                            {{ $warehouse->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500 dark:text-gray-400 italic">
                                            {{ __('No warehouse assigned') }}
                                        </span>
                                    @endforelse
                                </div>
                            </x-table.td>
                            <x-table.td
                                class="px-6 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700">
                                <x-dropdown align="right" width="56">
                                    <x-slot name="trigger">
                                        <button type="button"
                                            class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 dark:focus:ring-offset-gray-800 focus:ring-blue-500 dark:focus:ring-blue-600 transition-colors">
                                            <i class="fas fa-ellipsis-v w-4 h-4 mr-2"></i>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        @can('product_show')
                                            <x-dropdown-link
                                                wire:click="$dispatchTo('products.show','showModal',{ id :'{{ $product->id }}'})"
                                                wire:loading.attr="disabled"
                                                class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <i class="fas fa-eye w-4 h-4 mr-3 text-blue-500"></i>
                                                {{ __('View') }}
                                            </x-dropdown-link>
                                        @endcan
                                        @if (settings()->telegram_channel)
                                            <x-dropdown-link wire:click="sendTelegram({{ $product->id }})"
                                                wire:loading.attr="disabled"
                                                class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <i class="fas fa-paper-plane w-4 h-4 mr-3 text-green-500"></i>
                                                {{ __('Send to telegram') }}
                                            </x-dropdown-link>
                                        @endif
                                        <x-dropdown-link wire:click="sendWhatsapp({{ $product->id }})"
                                            wire:loading.attr="disabled"
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <i class="fas fa-paper-plane w-4 h-4 mr-3 text-green-500"></i>
                                            {{ __('Send to Whatsapp') }}
                                        </x-dropdown-link>
                                        @can('product_update')
                                            <x-dropdown-link href="{{ route('product.edit', $product) }}"
                                                wire:loading.attr="disabled"
                                                class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <i class="fas fa-edit w-4 h-4 mr-3 text-yellow-500"></i>
                                                {{ __('Edit') }}
                                            </x-dropdown-link>
                                        @endcan
                                        @can('product_delete')
                                            <x-dropdown-link wire:click="deleteModal({{ $product->id }})"
                                                wire:loading.attr="disabled"
                                                class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <i class="fas fa-trash w-4 h-4 mr-3 text-red-500"></i>
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        @endcan
                                    </x-slot>
                                </x-dropdown>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="9"
                                class="px-6 py-12 text-center border-b border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col justify-center items-center space-y-3">
                                    <div
                                        class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                        <i class="fas fa-inbox w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <div class="text-center">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">
                                            {{ __('No products found') }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Try adjusting your search or filter criteria') }}</p>
                                    </div>
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>

            <div class="mt-6">
                <div
                    class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6 rounded-b-lg shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                            @if ($selected)
                                <div class="flex items-center space-x-2 mr-4">
                                    <div
                                        class="flex items-center justify-center w-5 h-5 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                        <i class="fas fa-check w-3 h-3 text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <span class="font-medium">{{ count($selected) }} {{ __('selected') }}</span>
                                </div>
                            @endif
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-info-circle w-4 h-4 text-gray-400"></i>
                                <span>{{ __('Showing') }} {{ $products->firstItem() ?? 0 }} {{ __('to') }}
                                    {{ $products->lastItem() ?? 0 }} {{ __('of') }} {{ $products->total() }}
                                    {{ __('results') }}</span>
                            </div>
                        </div>
                        <div class="flex-1 flex justify-center sm:justify-end">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <livewire:products.show :product="$product" />

            <livewire:products.create />

            <livewire:products.import />
        </div>
    </x-page-container>
</div>
