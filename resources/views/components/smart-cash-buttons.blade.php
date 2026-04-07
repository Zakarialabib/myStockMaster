@props([
    'totalAmount' => 0,
    'wireModel' => 'paid_amount',
])

<div
    x-data="{
        total: @js((float) $totalAmount),
        suggestions: [],
        init() {
            this.calculateSuggestions();
            $watch('total', () => this.calculateSuggestions());
        },
        calculateSuggestions() {
            const total = this.total;
            if (total <= 0) {
                this.suggestions = [];
                return;
            }

            const suggestions = [];
            const rounded = Math.ceil(total);

            suggestions.push({ label: '{{ __('Exact') }}', value: total, type: 'exact' });

            const roundToNearest = (num, nearest) => Math.ceil(num / nearest) * nearest;

            const denominations = [5, 10, 20, 50, 100];
            const usedDenominations = new Set();

            for (const denom of denominations) {
                if (denom >= total && !usedDenominations.has(denom)) {
                    suggestions.push({
                        label: `$${denom}`,
                        value: denom,
                        type: 'denomination'
                    });
                    usedDenominations.add(denom);
                    break;
                }
            }

            const rounded5 = roundToNearest(total, 5);
            if (rounded5 > total && !usedDenominations.has(5)) {
                suggestions.push({
                    label: `$${rounded5}`,
                    value: rounded5,
                    type: 'rounded'
                });
            }

            const rounded10 = roundToNearest(total, 10);
            if (rounded10 > total) {
                suggestions.push({
                    label: `$${rounded10}`,
                    value: rounded10,
                    type: 'rounded'
                });
            }

            const rounded20 = roundToNearest(total, 20);
            if (rounded20 > total && rounded20 !== rounded10) {
                suggestions.push({
                    label: `$${rounded20}`,
                    value: rounded20,
                    type: 'rounded'
                });
            }

            this.suggestions = suggestions.slice(0, 5);
        },
        setAmount(value) {
            $wire.set('{{ $wireModel }}', value);
        }
    }"
    class="grid grid-cols-4 gap-2 mb-4"
    role="group"
    aria-label="{{ __('Quick cash amount suggestions') }}"
>
    <template x-for="(suggestion, index) in suggestions" :key="index">
        <button
            type="button"
            @click="setAmount(suggestion.value)"
            class="py-2.5 px-2 font-semibold rounded-lg border text-sm transition-all duration-150 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
            :class="{
                'bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 border-green-300 dark:border-green-700': suggestion.type === 'exact',
                'bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-700': suggestion.type === 'denomination',
                'bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600': suggestion.type === 'rounded'
            }"
            x-text="suggestion.label"
            :aria-label="suggestion.type === 'exact' ? '{{ __('Pay exact amount') }}' : `Pay ${suggestion.label}`"
        ></button>
    </template>
</div>
