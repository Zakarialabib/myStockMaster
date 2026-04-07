<div x-data="{
    open: false,
    highlightedIdx: $wire.entangle('highlightedIndex').live,
    init() {
        this.$watch('open', value => {
            if (value) {
                this.$nextTick(() => this.$refs.searchInput?.focus());
            }
        });
    },
    toggleDropdown() {
        this.open = !this.open;
    },
    closeDropdown() {
        this.open = false;
    },
    clearSelection() {
        $wire.clearSelection();
        this.open = true;
        this.$nextTick(() => this.$refs.searchInput?.focus());
    },
    handleKeydown(event) {
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            $wire.highlightNext();
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            $wire.highlightPrev();
        } else if (event.key === 'Enter') {
            event.preventDefault();
            $wire.selectHighlighted();
            this.open = false;
        } else if (event.key === 'Escape') {
            this.closeDropdown();
        }
    }
}" class="relative w-full" @click.outside="closeDropdown()">
    <div class="flex items-center gap-2">
        <button type="button" @click="toggleDropdown()"
            class="relative w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2.5 text-left transition focus:outline-none focus:ring-2 focus:ring-blue-500/40"
            :class="{ 'ring-2 ring-blue-500/40 border-blue-500': open }" aria-haspopup="listbox" :aria-expanded="open"
            aria-label="{{ __('Select customer') }}">
            <div class="flex items-center gap-3 pr-8">
                <i class="fas fa-user-circle text-gray-400"></i>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Customer') }}</p>
                    <p x-show="!$wire.selectedCustomerId" class="truncate text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Search and select customer...') }}
                    </p>
                    <p x-show="$wire.selectedCustomerId"
                        class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $this->selectedCustomerName }}
                    </p>
                </div>
            </div>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 transition-transform"
                :class="{ 'rotate-180': open }"></i>
        </button>

        <button type="button" x-show="$wire.selectedCustomerId" @click.stop="clearSelection()"
            class="rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-red-600 transition hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40"
            aria-label="{{ __('Clear customer selection') }}">
            <i class="fas fa-times"></i>
        </button>

        <button type="button" wire:click="$dispatch('createModal').to('customers.create')"
            class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-2.5 text-blue-600 transition hover:bg-blue-100 dark:border-blue-700 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50"
            title="{{ __('Add Customer') }}" aria-label="{{ __('Add new customer') }}">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-2 w-full overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
        role="listbox" @keydown="handleKeydown($event)">
        <div class="border-b border-gray-100 p-2 dark:border-gray-700">
            <x-input.text x-ref="searchInput" icon="fas fa-search" wire:model.live.debounce.250ms="search"
                autocomplete="off" placeholder="{{ __('Search by name or phone...') }}"
                aria-label="{{ __('Search customers') }}" />
        </div>

        <ul class="max-h-64 overflow-y-auto py-1">
            @forelse($this->customers as $index => $customer)
                <li wire:key="customer-{{ $customer->id }}">
                    <button type="button" wire:click="selectCustomer('{{ $customer->id }}')"
                        class="flex w-full items-center justify-between px-4 py-2.5 text-left transition hover:bg-blue-50 dark:hover:bg-blue-900/30"
                        @class([
                            'bg-blue-100 dark:bg-blue-900/40' => $highlightedIndex === $index,
                        ]) @click="closeDropdown()" role="option"
                        aria-selected="{{ (string) $selectedCustomerId === (string) $customer->id ? 'true' : 'false' }}">
                        <span class="min-w-0">
                            <span
                                class="block truncate font-medium text-gray-900 dark:text-gray-100">{{ $customer->name }}</span>
                            @if ($customer->phone)
                                <span
                                    class="block truncate text-xs text-gray-500 dark:text-gray-400">{{ $customer->phone }}</span>
                            @endif
                        </span>
                        @if ((string) $selectedCustomerId === (string) $customer->id)
                            <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                        @endif
                    </button>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-search mb-2 text-2xl opacity-50"></i>
                    <p class="text-sm">{{ __('No customers found') }}</p>
                    <p class="text-xs mt-1">{{ __('Try typing at least 2 characters') }}</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>
