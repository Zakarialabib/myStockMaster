<div>
    <div class="w-full px-2 ">
        <livewire:pos.filter :categories="$categories" />
        <div class="flex flex-row relative h-screen">
            <div wire:loading.flex class="w-full px-2 absolute justify-center items-center"
                style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
                <x-loading />
            </div>
            <div class="w-full flex flex-wrap px-2 mt-5 overflow-y-auto bg-white">
                @forelse($products as $product)
                    <div class="w-full lg:w-1/4 md:w-1/2 mx-2">
                        <div wire:click.prevent="selectProduct({{ $product }})"
                            class="rounded-md shadow-xl border border-gray-200">
                            <div class="relative">
                                <div class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded text-white bg-blue-400 mb-3 absolute"
                                    style="right:10px;top: 10px;">{{ __('Stock') }}: {{ $product->quantity }}
                                </div>
                                <div class="inline-block p-1 text-center">
                                    <div class="mb-2">
                                        <h6 class="text-md text-center font-semibold mb-3 md:mb-0">{{ $product->name }}
                                        </h6>
                                    </div>
                                    <p class="mb-0 text-center font-bold">{{ format_currency($product->price) }}</p>
                                </div>
                                <span
                                    class="block p-1 text-center font-semibold text-xs align-baseline leading-none text-white bg-green-400">
                                    {{ $product->code }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full px-2">
                        <div
                            class="relative px-3 py-3 mb-4 border rounded text-yellow-800 border-yellow-800 bg-yellow-400 md:mb-0">
                            <span class="inline-block align-middle mr-8">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11a1 1 0 11-2 0 1 1 0 012 0zm-1-3a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span class="inline-block align-middle mr-8">
                                {{ __('No product found') }}
                            </span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <div @class(['mt-3' => $products->hasPages()])>
            {{ $products->links() }}
        </div>
    </div>
</div>
