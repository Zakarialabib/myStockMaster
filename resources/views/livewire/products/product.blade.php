<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="">
                <input type="text" wire:model.debounce.300ms="search"
                    class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th class="pr-0 w-8">
                <input wire:model="selected" type="checkbox" />
            </x-table.th>
            <x-table.th>
                {{ __('Image') }}
            </x-table.th>
            <x-table.th>
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
            <x-table.th>
                {{ __('Category') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>

        </x-slot>
        <x-table.tbody>
            @forelse($products as $product)
                <x-table.tr wire:loading.class.delay="opacity-50" wire:key="row-{{ $product->id }}">
                    <x-table.td>
                        <input type="checkbox" value="{{ $product->id }}" wire:model="selected">
                    </x-table.td>
                    <x-table.td>
                        @if ($product->image)
                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-10 h-10 rounded-full">
                        @else
                            {{ __('No image') }}
                        @endif
                    </x-table.td>
                    <x-table.td>
                        <div class=" whitespace-nowrap">
                            {{ $product->name }} <br>
                            <x-badge success>
                                {{ $product->code }}
                            </x-badge>
                        </div>
                    </x-table.td>
                    <x-table.td>
                        {{ $product->quantity }}
                    </x-table.td>
                    <x-table.td>
                        {{ $product->price }}
                    </x-table.td>
                    <x-table.td>
                        {{ $product->cost }}
                    </x-table.td>
                    <x-table.td>
                        {{ $product->category->name }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-dropdown align="right" class="w-auto">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-button primary type="button" class="text-white flex items-center">
                                        {{ __('Actions') }}
                                    </x-button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link wire:click="showModal({{ $product->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-eye"></i>
                                        {{ __('View') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="editModal({{ $product->id }})" class="mr-2"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link wire:click="$emit('deleteModal', {{ $product->id }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-trash"></i>
                                        {{ __('Delete') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="10" class="text-center">
                        {{ __('No results found') }}
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

    @if ($product)
        <!-- Show Modal -->
        <x-modal wire:model="showModal">
            <x-slot name="title">
                {{ __('Show Product') }}
            </x-slot>

            <x-slot name="content">
                <div class="px-4 mx-auto mb-4">
                    <div class="row mb-3">
                        <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                            {!! \Milon\Barcode\Facades\DNS1DFacade::getBarCodeSVG($product->code, $product->barcode_symbology, 2, 110) !!}
                        </div>
                        <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-32 h-32 rounded-full">
                        </div>
                    </div>
                    <div class="row">
                        <div class="w-full px-4">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <tr>
                                        <th>{{ __('Product Code') }}</th>
                                        <td>{{ $product->code }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Barcode Symbology') }}</th>
                                        <td>{{ $product->barcode_symbology }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Category') }}</th>
                                        <td>{{ $product->category->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Cost') }}</th>
                                        <td>{{ format_currency($product->cost) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Price') }}</th>
                                        <td>{{ format_currency($product->price) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Quantity') }}</th>
                                        <td>{{ $product->quantity . ' ' . $product->unit }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Stock Worth') }}</th>
                                        <td>
                                            {{ __('COST') }}::
                                            {{ format_currency($product->cost * $product->quantity) }}
                                            /
                                            {{ __('PRICE') }}::
                                            {{ format_currency($product->price * $product->quantity) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Alert Quantity') }}</th>
                                        <td>{{ $product->stock_alert }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Tax (%)') }}</th>
                                        <td>{{ $product->order_tax ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Tax Type') }}</th>
                                        <td>
                                            @if ($product->tax_type == 1)
                                                {{ __('Exclusive') }}
                                            @elseif($product->tax_type == 2)
                                                {{ __('Inclusive') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Description') }}</th>
                                        <td>{{ $product->note ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full flex justify-start space-x-2">
                    <x-button primary wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                        {{ __('Close') }}
                    </x-button>
                </div>
            </x-slot>
        </x-modal>
        <!-- End Show Modal -->
    @endif

    <!-- Edit Modal -->
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Product') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                <div>
                    <div class="flex flex-wrap -mx-1">
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="name" :value="__('Product Name')" required autofocus />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                wire:model="product.name" required autofocus />
                            <x-input-error :messages="$errors->get('product.name')" for="product.name" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="code" :value="__('Product Code')" required />
                            <x-input id="code" class="block mt-1 w-full" type="text" name="code"
                                wire:model="product.code" disabled required />
                            <x-input-error :messages="$errors->get('product.code')" for="product.code" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-1">
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="category_id" :value="__('Category')" required />
                            <x-select-list
                                class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                id="category_id" name="category_id" wire:model="product.category_id"
                                :options="$this->listsForFields['categories']" />
                        </div>

                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="cost" :value="__('Cost')" required />
                            <x-input id="cost" class="block mt-1 w-full" type="number" name="cost"
                                wire:model="product.cost" required />
                            <x-input-error :messages="$errors->get('product.cost')" for="product.cost" class="mt-2" />

                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="price" :value="__('Price')" required />
                            <x-input id="price" class="block mt-1 w-full" type="number" name="price"
                                wire:model="product.price" required />
                            <x-input-error :messages="$errors->get('product.price')" for="product.price" class="mt-2" />

                        </div>

                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="quantity" :value="__('Quantity')" required />
                            <input type="number" wire:model="product.quantity" name="quantity"
                                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                required min="1">
                            <x-input-error :messages="$errors->get('product.quantity')" for="product.quantity" class="mt-2" />
                        </div>
                        <div class="lg:w-1/2 sm:w-1/2 px-2">
                            <x-label for="stock_alert" :value="__('Stock Alert')" required />
                            <x-input id="stock_alert" class="block mt-1 w-full" type="number" name="stock_alert"
                                wire:model="product.stock_alert" required />
                            <x-input-error :messages="$errors->get('product.stock_alert')" for="product.stock_alert" class="mt-2" />
                        </div>
                    </div>

                    <x-accordion title="{{ 'More Details' }}">
                        <div class="flex flex-wrap -mx-1 space-y-2">
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="brand_id" :value="__('Brand')" />
                                <x-select-list
                                    class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                    id="brand_id" name="brand_id" wire:model="product.brand_id"
                                    :options="$this->listsForFields['brands']" />
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="order_tax" :value="__('Tax')" />
                                <x-input id="order_tax" class="block mt-1 w-full" type="number" name="order_tax"
                                    wire:model="product.order_tax" />
                                <x-input-error :messages="$errors->get('product.order_tax')" for="product.order_tax" class="mt-2" />

                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="tax_type" :value="__('Tax type')" />
                                <select wire:model="product.tax_type" name="tax_type"
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded">
                                    <option value="inclusive">{{ __('Inclusive') }}</option>
                                    <option value="exclusive">{{ __('Exclusive') }}</option>
                                </select>
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="unit" :value="__('Unit')" />
                                <x-input id="unit" class="block mt-1 w-full" type="text" name="unit"
                                    wire:model="product.unit" required />
                            </div>
                            <div class="lg:w-1/3 sm:w-1/2 px-2">
                                <x-label for="barcode_symbology" :value="__('Barcode Symbology')" />
                                <select wire:model="product.barcode_symbology" name="barcode_symbology"
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                    required>
                                    <option selected value="C128">Code 128</option>
                                    <option value="C39">Code 39</option>
                                    <option value="UPCA">UPC-A</option>
                                    <option value="UPCE">UPC-E</option>
                                    <option value="EAN13">EAN-13</option>
                                    <option value="EAN8">EAN-8</option>
                                </select>
                            </div>
                            <div class="w-full mb-4">
                                <x-label for="note" :value="__('Description')" />
                                <textarea rows="4" wire:model="product.note" name="note"
                                    class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded">
                                            </textarea>
                            </div>
                        </div>
                    </x-accordion>


                    <div class="w-full px-4 my-4">
                        <x-label for="image" :value="__('Product Image')" />
                        <x-fileupload wire:model="image" :file="$image" accept="image/jpg,image/jpeg,image/png" />
                        <x-input-error :messages="$errors->get('image')" for="image" class="mt-2" />
                    </div>

                    <div class="flex justify-start space-x-2">
                        <x-button primary wire:click="update" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                        <x-button primary wire:click="$toggle('editModal')" wire:loading.attr="disabled">
                            {{ __('Close') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Edit Modal -->

    <livewire:products.create />

    {{-- Import modal --}}

    <x-modal wire:model="importModal">
        <x-slot name="title">
            {{ __('Import Excel') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="import">
                <div class="space-y-4">
                    <div class="mt-4">
                        <x-label for="import" :value="__('Import')" />
                        <x-input id="import" class="block mt-1 w-full" type="file" name="import"
                            wire:model.defer="import" />
                        <x-input-error :messages="$errors->get('import')" for="import" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-end">
                        <x-button primary wire:click="import" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                        <x-button primary type="button" wire:click="$set('importModal', false)"
                            wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

    {{-- End Import modal --}}

</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', productId => {
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
                        window.livewire.emit('delete', productId)
                    }
                })
            })
        })
    </script>
@endpush
