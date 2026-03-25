<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\WithAlert;

new class extends Component
{
    use WithAlert;
    use WithPagination;

    public $product;

    #[Url(as: 'q')]
    public $querySearch = '';

    public $category_id;

    public $warehouse_id;

    public $search_results;

    public int $showCount = 9;

    public bool $featured = false;

    public bool $hasMorePages = true;

    public function loadMore(): void
    {
        $this->showCount += 5;
    }

    public function selectProduct($id): void
    {
        if ($this->warehouse_id !== null) {
            $this->dispatch('productSelected', productId: $id, warehouseId: $this->warehouse_id);
        } else {
            $this->alert('error', __('Please select a warehouse!'));
        }
    }

    #[On('warehouseSelected')]
    public function updatedWarehouseId(int $warehouseId): void
    {
        $this->warehouse_id = $warehouseId;
        $this->resetPage();
    }

    #[Computed]
    public function categories()
    {
        return Category::pluck('name', 'id');
    }

    public function mount(int|string|null $warehouseId = null): void
    {
        if ($warehouseId !== null) {
            $this->warehouse_id = (int) $warehouseId;
        }
    }

    public function resetQuery(): void
    {
        $this->querySearch = '';
    }

    public function updatedQuerySearch(): void
    {
        // Check if input looks like a barcode (numeric, 8-14 chars)
        if (preg_match('/^\d{8,14}$/', $this->querySearch)) {
            $product = Product::where('code', $this->querySearch)->first();
            if ($product) {
                $this->selectProduct($product->id);
                $this->querySearch = '';
            }
        }
    }

    public function render()
    {
        $query = Product::with(['warehouses' => static function ($query): void {
            $query->withPivot('qty', 'price', 'cost');
        }, 'category'])
            ->when($this->querySearch, function ($query): void {
                $query->where(function ($query): void {
                    $query->where('name', 'like', '%'.$this->querySearch.'%')
                        ->orWhere('code', 'like', '%'.$this->querySearch.'%');
                });
            })
            ->when($this->category_id, function ($query): void {
                $query->where('category_id', $this->category_id);
            })
            ->when($this->warehouse_id, function ($query): void {
                $query->whereHas('warehouses', function ($q): void {
                    $q->where('warehouse_id', $this->warehouse_id);
                });
            })
            ->when($this->featured, static function ($query): void {
                $query->where('featured', true);
            });

        $products = $query->paginate($this->showCount);
        $this->hasMorePages = $products->hasMorePages();

        return view('components.search-product', [
            'products' => $products,
        ]);
    }
};
?>

<div>
    <div class="relative mt-2" x-data="{ showScan: false }">
        <div class="mb-3 px-2">
            <div class="mb-3 w-full relative text-gray-600 focus-within:text-gray-400">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <button type="button" class="p-1 focus:outline-hidden focus:shadow-outline text-gray-400 hover:text-blue-500 transition-colors" @click="showScan = true" title="{{ __('Scan Barcode') }}">
                        <i class="fas fa-camera text-lg"></i>
                    </button>
                </span>
                <input wire:keydown.escape="resetQuery" wire:model.live.debounce.500ms="querySearch" type="search" class="block w-full pl-12 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50"
                    minlength="4" placeholder="{{ __('Search products by code, reference or name...') }}"
                    id="product-search-input"
                    autofocus />
                @if($querySearch)
                <div class="absolute right-0 top-0 mt-2.5 mr-3 text-gray-400 hover:text-red-500 cursor-pointer transition-colors">
                    <button wire:click="resetQuery" type="button"><i class="fas fa-times-circle"></i></button>
                </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                <!-- Featured Toggle -->
                <div class="flex items-center">
                    <label for="toggle-featured" class="flex items-center cursor-pointer group">
                        <div class="relative">
                            <input id="toggle-featured" type="checkbox" class="sr-only" wire:model.live="featured">
                            <div class="block w-10 h-6 bg-gray-200 rounded-full transition-colors group-hover:bg-gray-300 peer-checked:bg-blue-500" :class="{'bg-blue-500': $wire.featured}"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform transform" :class="{'translate-x-4': $wire.featured}"></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700">{{ __('Featured Only') }}</span>
                    </label>
                </div>

                <!-- Filters -->
                <div class="flex items-center gap-3 flex-1 sm:flex-none justify-end">
                    <div class="relative min-w-[150px]">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-tags text-gray-400 text-xs"></i>
                        </div>
                        <select wire:model.live="category_id" id="category_id" class="block w-full pl-9 pr-8 py-2 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($this->categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative w-24">
                        <select wire:model.live="showCount" class="block w-full py-2 pl-3 pr-8 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="9">9 / {{ __('page') }}</option>
                            <option value="15">15 / {{ __('page') }}</option>
                            <option value="21">21 / {{ __('page') }}</option>
                            <option value="30">30 / {{ __('page') }}</option>
                            <option value="100">{{ __('All') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full px-2 mb-4 bg-white">
            <div class="flex flex-wrap w-full">
                <div
                    class="w-full grid gap-3 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 px-2 mt-5 overflow-y-auto">
                    @forelse($products as $product)
                        <div wire:click="selectProduct('{{ $product->id }}')" wire:key="product-{{ $product->id }}"
                            class="group select-none cursor-pointer transition-all duration-200 ease-in-out transform hover:-translate-y-1 hover:scale-105 overflow-hidden bg-white shadow-sm hover:shadow-xl w-full py-8 relative border border-gray-200 hover:border-green-500 rounded-xl"
                            style="{{ $product->image ? 'background-image: url(' . asset('images/products/') . $product->image . '); background-size: cover; background-position: center; background-blend-mode: overlay; background-color: rgba(255,255,255,0.9);' : '' }}">
                            
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-green-500 bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-200 flex items-center justify-center">
                                <span class="opacity-0 group-hover:opacity-100 bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                    <i class="fas fa-plus mr-1"></i> {{ __('Add') }}
                                </span>
                            </div>

                            @php($warehouse = $product->warehouses->where('id', $warehouse_id)->first())
                            <div
                                class="inline-block px-2 py-1 text-center font-bold text-xs align-baseline leading-none text-white bg-blue-500 mb-3 absolute top-2 right-2 rounded-lg shadow-sm">
                                {{ $product->code }}
                            </div>
                            <div class="flex flex-col py-4 px-3 text-sm gap-y-2 -my-auto items-center relative z-10">
                                <h6 class="text-md text-center font-bold text-gray-800 mb-1 line-clamp-2">
                                    {{ $product->name }}
                                </h6>
                                <p class="mb-0 text-center font-black text-lg text-green-600">
                                    {{ format_currency($warehouse ? $warehouse->pivot->price : ($product->price ?? 0)) }}
                                </p>
                            </div>
                            {{-- if stock is 0 not need to show --}}
                            @if ($warehouse && $warehouse->pivot->qty > 0)
                                <div
                                    class="w-full block p-1.5 text-center font-bold text-xs text-white bg-gray-800 absolute bottom-0 opacity-90">
                                    <i class="fas fa-box-open mr-1"></i> {{ __('Stock') }}: {{ $warehouse->pivot->qty }}
                                </div>
                            @else
                                <div
                                    class="w-full block p-1.5 text-center font-bold text-xs text-white bg-red-500 absolute bottom-0">
                                    <i class="fas fa-times-circle mr-1"></i> {{ __('Out of Stock') }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full w-full px-2 py-3 mb-4 border rounded-sm">
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
                
                @if($hasMorePages)
                    <div class="py-6 flex justify-center items-center w-full">
                        <button type="button" wire:click="loadMore" wire:loading.attr="disabled" class="px-6 py-2.5 bg-white border border-gray-300 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all flex items-center">
                            <span wire:loading.remove wire:target="loadMore">{{ __('Load More Products') }}</span>
                            <span wire:loading wire:target="loadMore" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('Loading...') }}
                            </span>
                        </button>
                    </div>
                @endif
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


        @script
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
                document.querySelector("#product-search-input").value = result.codeResult.code;
                document.querySelector("#product-search-input").dispatchEvent(new Event('input'));
                document.querySelector("#scanner-container").classList.add("hidden");
                Quagga.stop();
            });
        </script>
        @endscript
    </div>
</div>
