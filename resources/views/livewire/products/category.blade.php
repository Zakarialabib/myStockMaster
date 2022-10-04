<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-md-0 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 my-md-0">
            <div class="">
                <input type="text" wire:model.debounce.300ms="search"
                    class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                    placeholder="{{ __('Search') }}" />
            </div>
        </div>
    </div>
    <div wire:loading.delay>
        Loading...
    </div>

    <x-table>
        <x-slot name="thead">
            <x-table.th>#</x-table.th>
            <x-table.th>
                {{ __('Code') }}
            </x-table.th>
            <x-table.th>
                {{ __('Name') }}
            </x-table.th>
            <x-table.th>
                {{ __('Products count') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
            </tr>
        </x-slot>
        <x-table.tbody>
            @forelse($categories as $category)
                <x-table.tr>
                    <x-table.td>
                        <input type="checkbox" value="{{ $category->id }}" wire:model="selected">
                    </x-table.td>
                    <x-table.td>
                        {{ $category->category_code }}
                    </x-table.td>
                    <x-table.td>
                        {{ $category->category_name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $category->products_count }}
                    </x-table.td>
                    <x-table.td>
                        {{-- <button type="button" class="uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-2 px-2 rounded"
                            wire:click="$emit('showModal', 'products.product-show', {{ $category->id }})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-2 px-2 rounded"
                            wire:click="$emit('showModal', 'products.product-edit', {{ $category->id }})">
                            <i class="bi bi-pencil"></i> --}}
                        <a href="{{ route('product-categories.edit', $category->id) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button id="delete" class="btn btn-danger btn-sm"
                            onclick="
    event.preventDefault();
    if (confirm('Are you sure? It will delete the data permanently!')) {
        document.getElementById('destroy{{ $category->id }}').submit();
    }
    ">
                            <i class="bi bi-trash"></i>
                            <form id="destroy{{ $category->id }}" class="d-none"
                                action="{{ route('product-categories.destroy', $category->id) }}" method="POST">
                                @csrf
                                @method('delete')
                            </form>
                        </button>
                        </button>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="10" class="text-center">
                        {{ __('No entries found.') }}
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
            {{ $categories->links() }}
        </div>
    </div>
</div>

<script>
    Livewire.on('confirm', e => {
        if (!confirm("{{ __('Are you sure') }}")) {
            return
        }
        @this[e.callback](...e.argv)
    });
</script>
