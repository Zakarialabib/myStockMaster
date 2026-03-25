@props([
    'headers' => [],
    'showCheckbox' => false,
    'height' => null,
    'sticky' => false,
    'sortable' => true,
    'loading' => false,
    'emptyMessage' => 'No data available',
])

@php
    $containerClasses =
        'bg-white dark:bg-gray-900 shadow-soft rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden';
    $tableClasses = 'min-w-full divide-y divide-gray-200 dark:divide-gray-800';
    $heightStyle = $height ? "height: {$height}" : '';
    $stickyHeader = $sticky ? 'sticky top-0 z-10' : '';
@endphp

<div class="{{ $containerClasses }}" x-data="{
    sortBy: '',
    sortDirection: 'asc',
    selectedRows: [],
    selectAll: false,

    sort(field) {
        if (this.sortBy === field) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortBy = field;
            this.sortDirection = 'asc';
        }
        $wire.sortingBy(field, this.sortDirection);
    },

    toggleSelectAll() {
        this.selectAll = !this.selectAll;
        if (this.selectAll) {
            $wire.selectPage();
        } else {
            $wire.resetSelected();
        }
    },

    toggleRow(id) {
        if (this.selectedRows.includes(id)) {
            this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
        } else {
            this.selectedRows.push(id);
        }
        $wire.set('selected', this.selectedRows);
    }
}">

    <div
        class="overflow-x-auto overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-800"
        @if ($heightStyle) style="{{ $heightStyle }}" @endif>
        <table class="{{ $tableClasses }}" wire:loading.class="opacity-50">
            @if (!empty($headers))
                <thead class="bg-gray-50 dark:bg-gray-800 {{ $stickyHeader }}">
                    <tr>
                        @if ($showCheckbox)
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest w-12">
                                <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()"
                                    class="rounded border-gray-300 dark:border-gray-600 text-primary-600 shadow-sm focus:ring-primary-500 dark:bg-gray-700">
                            </th>
                        @endif
                        @foreach ($headers as $header)
                            @php
                                $isArray = is_array($header);
                                $label = $isArray ? $header['label'] ?? '' : $header;
                                $key = $isArray ? $header['key'] ?? '' : strtolower(str_replace(' ', '_', $header));
                                $isSortable = $sortable && $isArray && ($header['sortable'] ?? true);
                                $icon = $isArray ? $header['icon'] ?? null : null;
                            @endphp
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest
                                      {{ $isSortable ? 'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors' : '' }}"
                                @if ($isSortable) @click="sort('{{ $key }}')" @endif>
                                <div class="flex items-center space-x-2">
                                    @if ($icon)
                                        <i class="{{ $icon }} text-gray-400 dark:text-gray-500"></i>
                                    @endif
                                    <span>{{ $label }}</span>
                                    @if ($isSortable)
                                        <div class="flex flex-col">
                                            <i class="fas fa-chevron-up text-[10px] transition-colors"
                                                :class="sortBy === '{{ $key }}' && sortDirection === 'asc' ?
                                                    'text-primary-500' : 'text-gray-300'"></i>
                                            <i class="fas fa-chevron-down text-[10px] -mt-1 transition-colors"
                                                :class="sortBy === '{{ $key }}' && sortDirection === 'desc' ?
                                                    'text-primary-500' : 'text-gray-300'"></i>
                                        </div>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @elseif (isset($thead))
                <x-table.thead>
                    {{ $thead }}
                </x-table.thead>
            @endif

            @if (!empty($headers))
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                    @if ($slot->isEmpty())
                        <tr>
                            <td colspan="{{ count($headers) + ($showCheckbox ? 1 : 0) }}"
                                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-lg font-medium">{{ $emptyMessage }}</p>
                                    <p class="text-sm">{{ __('Try adjusting your search or filter criteria.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        {{ $slot }}
                    @endif
                </tbody>
            @else
                {{ $slot }}
            @endif
        </table>
    </div>
</div>

@push('scripts')
    <script>
        // Enhanced table performance utilities
        document.addEventListener('alpine:init', () => {
            Alpine.data('tableUtils', () => ({
                // Debounce function for search and filters
                debounce(func, wait) {
                    let timeout;
                    return function executedFunction(...args) {
                        const later = () => {
                            clearTimeout(timeout);
                            func(...args);
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                },

                // Intersection Observer for lazy loading rows
                observeRows() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('visible');
                            }
                        });
                    }, {
                        threshold: 0.1
                    });

                    document.querySelectorAll('[data-lazy-row]').forEach(row => {
                        observer.observe(row);
                    });
                }
            }));
        });
    </script>
@endpush
