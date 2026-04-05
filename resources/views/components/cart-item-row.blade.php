@props(['item', 'cartInstance' => 'pos'])

<tr wire:key="cart-item-{{ $item->rowId }}"
    class="transition-all duration-200 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-800/50">
    <td class="px-3 py-3 align-top">
        <div class="flex items-start gap-2">
            <div class="hidden sm:block shrink-0 mt-1">
                @if ($item->attributes['image'] ?? null)
                    <img src="{{ asset('images/products/' . $item->attributes['image']) }}"
                        class="w-10 h-10 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                        alt="{{ $item->name }}" loading="lazy" />
                @else
                    <div
                        class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-400">
                        <i class="fas fa-box text-xs"></i>
                    </div>
                @endif
            </div>
            <div class="flex flex-col min-w-0">
                <div class="font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 leading-tight text-sm">
                    {{ $item->name }}
                </div>
                <div class="flex items-center gap-2 mt-1 flex-wrap">
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                        {{ $item->attributes['code'] ?? '' }}
                    </span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ ($item->attributes['stock'] ?? 0) > 0 ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800' }}">
                        {{ ($item->attributes['stock'] ?? 0) . ' ' . ($item->attributes['unit'] ?? '') }}
                    </span>
                </div>
                @include('livewire.includes.product-cart-modal')
            </div>
        </div>
    </td>

    <td class="px-3 py-3 align-top">
        <div x-data="{ editPrice: false }" class="relative">
            <div x-show="!editPrice" class="flex items-center group cursor-pointer" @click="editPrice = true">
                <span class="font-bold text-gray-700 dark:text-gray-300 whitespace-nowrap text-sm">
                    {{ format_currency($item->attributes['unit_price'] ?? ($item->price ?? 0)) }}
                </span>
                <button type="button"
                    class="ml-1.5 opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                    aria-label="{{ __('Edit price') }}">
                    <i class="fa fa-pen text-blue-500 text-[10px]"></i>
                </button>
            </div>
            <div x-show="editPrice" @click.away="editPrice = false" x-transition>
                <div wire:change="updatePrice('{{ $item->rowId }}', '{{ $item->id }}')" class="flex flex-col">
                    <input type="number" step="0.01" x-model="prices['{{ $item->id }}']"
                        wire:model="price.{{ $item->id }}"
                        class="w-24 text-sm px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="{{ format_currency($item->attributes['unit_price'] ?? ($item->price ?? 0)) }}"
                        aria-label="{{ __('Product price') }}" />
                </div>
            </div>
        </div>
    </td>

    <td class="px-3 py-3 align-top text-center">
        <div class="mt-0.5 inline-block">
            <div class="flex items-center justify-center gap-1">
                <button type="button"
                    @click="if (!quantities['{{ $item->id }}']) quantities['{{ $item->id }}'] = 1; quantities['{{ $item->id }}'] = parseInt(quantities['{{ $item->id }}']) - 1; if (quantities['{{ $item->id }}'] < 1) quantities['{{ $item->id }}'] = 1; $wire.updateQuantity('{{ $item->rowId }}', '{{ $item->id }}')"
                    class="w-7 h-7 flex items-center justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 active:bg-gray-100 dark:active:bg-gray-500 transition-colors touch-manipulation"
                    aria-label="{{ __('Decrease quantity') }}">
                    <i class="fas fa-minus text-xs"></i>
                </button>
                <input type="number" step="1" min="1" x-model="quantities['{{ $item->id }}']"
                    wire:model="quantity.{{ $item->id }}"
                    wire:change="updateQuantity('{{ $item->rowId }}', '{{ $item->id }}')"
                    class="w-14 text-center text-sm px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 font-semibold"
                    aria-label="{{ __('Product quantity') }}" />
                <button type="button"
                    @click="if (!quantities['{{ $item->id }}']) quantities['{{ $item->id }}'] = 1; quantities['{{ $item->id }}'] = parseInt(quantities['{{ $item->id }}']) + 1; $wire.updateQuantity('{{ $item->rowId }}', '{{ $item->id }}')"
                    class="w-7 h-7 flex items-center justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 active:bg-gray-100 dark:active:bg-gray-500 transition-colors touch-manipulation"
                    aria-label="{{ __('Increase quantity') }}">
                    <i class="fas fa-plus text-xs"></i>
                </button>
            </div>
        </div>
    </td>

    <td class="px-3 py-3 align-top text-right">
        <div class="font-bold text-green-600 dark:text-green-400 mt-1 whitespace-nowrap text-sm">
            {{ format_currency($item->attributes['sub_total'] ?? $item->price * $item->quantity) }}
        </div>
    </td>

    <td class="px-3 py-3 align-top text-center">
        <button wire:click="removeItem('{{ $item->rowId }}')" wire:loading.attr="disabled" type="button"
            class="text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 p-2 rounded-lg transition-colors"
            title="{{ __('Remove') }}" aria-label="{{ __('Remove item from cart') }}">
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
