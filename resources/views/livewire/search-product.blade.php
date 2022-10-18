<div class="relative">
    <div class="p-4">
        <div class="mb-2 md:mb-0">
            <input wire:keydown.escape="resetQuery" wire:model.debounce.500ms="query" type="text"
                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                placeholder="{{ __('Type product name or code....') }}">
        </div>
    </div>

    <div wire:loading class="card absolute mt-1 border-0 z-10 left-0 right-0">
        <div class="card-body shadow">
            <div class="d-flex justify-content-center">
                <x-loading />
            </div>
        </div>
    </div>

    @if (!empty($query))
        <div wire:click="resetQuery fixed w-full h-full  left-0 right-0 top-0 bottom-0 z-10">
        </div>
        @if ($search_results->isNotEmpty())
            <div class="card absolute mt-1" style="z-index: 2;left: 0;right: 0;border: 0;">
                <div class="flex-auto p-6 shadow">
                    <ul class="flex flex-col pl-0 mb-0">
                        @foreach ($search_results as $result)
                            <li
                                class="relative block py-3 px-6 -mb-px border border-r-0 border-l-0 border-grey-light no-underline w-fill">
                                <a wire:click="resetQuery" wire:click.prevent="selectProduct({{ $result }})"
                                    href="#">
                                    {{ $result->name }} | {{ $result->code }}
                                </a>
                            </li>
                        @endforeach
                        @if ($search_results->count() >= $how_many)
                            <li
                                class="relative block py-3 px-6 -mb-px border border-r-0 border-l-0 border-grey-light no-underline w-fill text-center">
                                <a wire:click.prevent="loadMore"
                                    class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded btn-sm"
                                    href="#">
                                    {{ __('Load More') }} <i class="bi bi-arrow-down-circle"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @else
            <div class="card absolute mt-1 border-0" style="z-index: 1;left: 0;right: 0;">
                <div class="flex-auto p-6 shadow">
                    {{ __('No Product Found....') }}
                </div>
            </div>
        @endif
    @endif
</div>
