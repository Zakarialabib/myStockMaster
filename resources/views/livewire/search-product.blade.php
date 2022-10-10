<div class="relative">
    <div class="p-4">
        <div class="mb-4 mb-0">
            <input wire:keydown.escape="resetQuery" wire:model.debounce.500ms="query" type="text"
                class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                placeholder="{{ __('Type product name or code....') }}">
        </div>
    </div>

    <div wire:loading class="card position-absolute mt-1 border-0" style="z-index: 1;left: 0;right: 0;">
        <div class="card-body shadow">
            <div class="d-flex justify-content-center">
                <x-loading />
            </div>
        </div>
    </div>

    @if (!empty($query))
        <div wire:click="resetQuery" class="position-fixed w-100 h-100"
            style="left: 0; top: 0; right: 0; bottom: 0;z-index: 1;"></div>
        @if ($search_results->isNotEmpty())
            <div class="card position-absolute mt-1" style="z-index: 2;left: 0;right: 0;border: 0;">
                <div class="card-body shadow">
                    <ul class="list-group list-group-flush">
                        @foreach ($search_results as $result)
                            <li class="list-group-item list-group-item-action">
                                <a wire:click="resetQuery" wire:click.prevent="selectProduct({{ $result }})"
                                    href="#">
                                    {{ $result->name }} | {{ $result->code }}
                                </a>
                            </li>
                        @endforeach
                        @if ($search_results->count() >= $how_many)
                            <li class="list-group-item list-group-item-action text-center">
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
            <div class="card position-absolute mt-1 border-0" style="z-index: 1;left: 0;right: 0;">
                <div class="card-body shadow">
                    <div class="alert alert-warning mb-0">
                        {{ __('No Product Found....') }}
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
