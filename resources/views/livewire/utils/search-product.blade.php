<div>
    <div class="relative mt-2" x-data="{ showScan: false }">
        <div class="mb-3 px-2">
            <div class="mb-2 w-full relative text-gray-600 focus-within:text-gray-400">
                <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                    <a href="#" class="p-1 focus:outline-none focus:shadow-outline" @click="showScan = true">
                        <i class="fas fa-camera"></i>
                    </a>
                </span>
                <x-input wire:keydown.escape="resetQuery" wire:model.live="querySearch" type="search" class="pl-10"
                    minlength="4" placeholder="{{ __('Search for products with code, reference or name') }}"
                    autofocus />
                <div class="absolute right-0 top-0 mt-2 mr-4 text-purple-lighter">
                    <button wire:click="resetQuery" type="button">X</button>
                </div>
            </div>
            <div class="flex flex-wrap -mx-2 mb-3">
                <div class="lg:w-1/3 md:w-1/3 sm:w-1/2 px-2 flex items-center">
                    <div class="flex items-center space-x-2">
                        <span>{{ __('All') }}</span>
                        <label for="toggle" class="flex items-center cursor-pointer">
                            <div
                                class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input id="toggle" type="checkbox" class="sr-only" wire:model.live="featured"
                                    checked>
                                <div class="toggle-label block w-10 h-6 rounded-full bg-gray-300 shadow-inner"></div>
                                <div
                                    class="toggle-checkbox absolute w-6 h-6 bg-white rounded-full shadow inset-y-0 left-0">
                                </div>
                            </div>
                        </label>
                        <span>{{ __('Featured') }}</span>
                    </div>
                </div>
                <div class="lg:w-1/3 md:w-1/3 sm:w-1/2 px-2">
                    <x-label for="category" :value="__('Category')" />
                    <x-select-list :options="$this->categories" wire:model.live="category_id" name="category_id"
                        id="category_id" />
                </div>

                <div class="lg:w-1/3 md:w-1/3 sm:w-1/2 px-2">
                    <x-label for="showCount" :value="__('Product per page')" />
                    <select wire:model.live="showCount"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                        <option value="9">9</option>
                        <option value="15">15</option>
                        <option value="21">21</option>
                        <option value="30">30</option>
                        <option value="">{{ __('All') }}</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="w-full px-2 mb-4 bg-white">
            <div class="flex flex-wrap w-full">
                <div
                    class="w-full grid gap-3 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 px-2 mt-5 overflow-y-auto">
                    @forelse($products as $product)
                        <div wire:click="selectProduct('{{ $product->id }}')"
                            class="select-none cursor-pointer transition-shadow overflow-hidden bg-white shadow hover:shadow-lg w-full py-8 relative border border-green-400"
                            style="{{ asset('images/products/') . $product->image ? 'background-image: url(' . asset('images/products/') . $product->image . '); background-size: cover; background-position: center;multiply-blend-mode: darken;' : '' }}">
                            @php($warehouse = $product->warehouses->where('id', $warehouse_id)->first())
                            <div
                                class="inline-block p-1 text-center font-semibold text-xs align-baseline leading-none text-white bg-blue-400 mb-3 absolute top-0 right-0">
                                {{ $product->code }}
                            </div>
                            <div class="flex flex-col py-4 px-3 text-sm gap-y-2 -my-auto items-center">
                                <h6 class="text-md text-center font-semibold mb-3 md:mb-0">
                                    {{ $product->name }}
                                </h6>
                                <p class="mb-0 text-center font-bold">
                                    {{ format_currency($warehouse ? $warehouse->pivot->price : 0) }}
                                </p>
                            </div>
                            {{-- if stock is 0 not need to show --}}
                            @if ($warehouse && $warehouse->pivot->qty > 0)
                                <div
                                    class="w-full block p-1 text-center font-bold text-md text-white bg-green-500 absolute bottom-0">
                                    {{ __('Stock') }}: {{ $warehouse ? $warehouse->pivot->qty : 0 }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full w-full px-2 py-3 mb-4 border rounded">
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
                    @endforelse
                </div>
                <div class="my-3 mx-auto">
                    @if ($products->count() >= $showCount)
                        <x-button wire:click.prevent="loadMore" primary type="button">
                            {{ __('Load More') }} <i class="fa fa-arrow-down-circle"></i>
                        </x-button>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="showScan" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="fixed top-0 left-0 h-screen w-screen flex items-center justify-center z-50"
            @click.away="showScan = false; Quagga.stop();">
            <div class="bg-white rounded-lg p-4 overflow-hidden shadow-xl transform transition-all">
                <div class="px-4 py-3">
                    <h2 class="text-lg leading-6 font-medium text-gray-900">{{ __('Scan barcode') }}</h2>
                    <div class="mt-2">
                        <x-button type="button" primary @click="initQuaggaJS()">{{ __('Start Scanning') }}
                        </x-button>
                    </div>
                    <div class="px-4 py-3 text-right">
                        <x-button @click="showScan = false; Quagga.stop();" danger>
                            {{ __('Close') }}
                        </x-button>
                    </div>
                    <div style="height:200px">
                        <div class="hidden" id="scanner-container"></div>
                    </div>
                </div>
            </div>
        </div>

        @assets
            <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"
                integrity="sha512-bCsBoYoW6zE0aja5xcIyoCDPfT27+cGr7AOCqelttLVRGay6EKGQbR6wm6SUcUGOMGXJpj+jrIpMS6i80+kZPw=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @endassets


        <script>
            function initQuaggaJS() {
                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.querySelector('#scanner-container')
                    },
                    decoder: {
                        readers: ["code_128_reader"]
                    }
                }, function(err) {
                    if (err) {
                        console.log(err);
                        return
                    }
                    console.log("Initialization finished. Ready to start");
                    Quagga.start();
                });
                document.querySelector("#scanner-container").classList.remove("hidden");
            }

            Quagga.onDetected(function(result) {
                document.querySelector("#productSearch").value = result.codeResult.code;
                document.querySelector("#scanner-container").classList.add("hidden");
                Quagga.stop();
                showScan = false;
            });
        </script>

    </div>
</div>
