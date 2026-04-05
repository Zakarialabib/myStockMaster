<div
    x-data="{
        open: $wire.entangle('isOpen'),
        search: $wire.entangle('search'),
        selectedId: $wire.entangle('selectedCustomerId'),
        highlightedIdx: $wire.entangle('highlightedIndex'),
        selectedName: @js($this->selectedCustomerName),
        init() {
            this.$watch('open', value => {
                if (value) {
                    this.$nextTick(() => {
                        this.$refs.searchInput?.focus();
                    });
                }
            });
        },
        selectOption(id, name) {
            this.selectedId = id;
            this.selectedName = name;
            this.open = false;
            this.search = '';
            this.highlightedIdx = -1;
            $wire.selectCustomer(id);
        },
        clearSelection() {
            this.selectedId = null;
            this.selectedName = null;
            $wire.clearSelection();
        },
        handleKeydown(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                $wire.highlightNext();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                $wire.highlightPrev();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                $wire.selectHighlighted();
            } else if (e.key === 'Escape') {
                this.open = false;
            }
        }
    }"
    class="relative w-full"
    @click.outside="open = false"
    @keydown="handleKeydown($event)"
>
    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ __('Customer') }}
    </label>

    <div class="flex gap-2">
        <div class="relative flex-1">
            <div
                x-show="!selectedId"
                @click="open = true"
                class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{'border-blue-500 ring-2 ring-blue-200': open}"
            >
                <span x-show="!open || !search" class="text-gray-500 dark:text-gray-400">{{ __('Search customer...') }}</span>
                <input
                    x-ref="searchInput"
                    x-model="search"
                    x-show="open"
                    type="text"
                    class="w-full bg-transparent border-none focus:outline-none text-gray-900 dark:text-gray-100"
                    placeholder="{{ __('Type to search...') }}"
                    autocomplete="off"
                    role="combobox"
                    aria-expanded="false"
                    aria-haspopup="listbox"
                    aria-label="{{ __('Search customers') }}"
                />
            </div>

            <div
                x-show="selectedId"
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-gray-900 dark:text-gray-100"
            >
                <span x-text="selectedName" class="font-medium"></span>
            </div>

            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user-circle text-gray-400"></i>
            </div>

            <button
                x-show="selectedId"
                @click.stop="clearSelection()"
                type="button"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors"
                aria-label="{{ __('Clear customer selection') }}"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>

        <button
            type="button"
            wire:click="$dispatch('createModal').to('customers.create')"
            class="px-3 py-2.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-lg border border-blue-200 dark:border-blue-700 transition-colors"
            title="{{ __('Add Customer') }}"
            aria-label="{{ __('Add new customer') }}"
        >
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg max-h-60 rounded-lg border border-gray-200 dark:border-gray-700 overflow-auto"
        role="listbox"
    >
        <div class="p-2 border-b border-gray-100 dark:border-gray-700">
            <input
                x-model="search"
                @input="$wire.set('search', search)"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                placeholder="{{ __('Search by name or phone...') }}"
                autocomplete="off"
            />
        </div>

        <ul class="py-1">
            @forelse($this->customers as $index => $customer)
                <li
                    wire:key="customer-{{ $customer->id }}"
                    @click="selectOption({{ $customer->id }}, '{{ addslashes($customer->name) }}')"
                    class="px-4 py-2.5 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/30 text-gray-900 dark:text-gray-100 transition-colors"
                    :class="{ 'bg-blue-100 dark:bg-blue-900/40': highlightedIdx === {{ $index }} }"
                    role="option"
                    :aria-selected="selectedId === {{ $customer->id }}"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-medium">{{ $customer->name }}</span>
                            @if($customer->phone)
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">{{ $customer->phone }}</span>
                            @endif
                        </div>
                        <svg x-show="selectedId === {{ $customer->id }}" class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-search mb-2 text-2xl opacity-50"></i>
                    <p class="text-sm">{{ __('No customers found') }}</p>
                    <p class="text-xs mt-1">{{ __('Type at least 2 characters to search') }}</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>
