@props([
    'options' => [],
    'placeholder' => __('Select an option'),
    'id' => '',
    'name' => '',
    'required' => false,
])

<div
    x-data="{
        open: false,
        search: '',
        value: @entangle($attributes->wire('model')),
        options: {{ Js::from(collect($options)->map(function($label, $value) { return ['value' => (string)$value, 'label' => $label]; })->values()) }},
        get filteredOptions() {
            if (this.search === '') {
                return this.options;
            }
            return this.options.filter(option => option.label.toLowerCase().includes(this.search.toLowerCase()));
        },
        get selectedLabel() {
            const selected = this.options.find(option => option.value == this.value);
            return selected ? selected.label : '{{ $placeholder }}';
        },
        selectOption(val) {
            this.value = val;
            this.open = false;
            this.search = '';
        }
    }"
    class="relative w-full"
    @click.outside="open = false"
>
    <!-- Hidden select for native form submission if needed, and to hold the required attribute -->
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        x-model="value"
        class="hidden"
        {{ $required ? 'required' : '' }}
    >
        <option value="">{{ $placeholder }}</option>
        <template x-for="option in options" :key="option.value">
            <option :value="option.value" x-text="option.label"></option>
        </template>
    </select>

    <div
        @click="open = !open"
        class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md mt-1 cursor-pointer bg-white border px-3 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
        :class="{'border-indigo-500 ring-1 ring-indigo-500': open}"
    >
        <span x-text="selectedLabel" :class="{'text-gray-500': !value}"></span>
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
        </svg>
    </div>

    <div
        x-show="open"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
        style="display: none;"
    >
        <div class="px-2 pb-2 sticky top-0 bg-white">
            <input
                type="text"
                x-model="search"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="{{ __('Search...') }}"
                @click.stop
            >
        </div>

        <ul class="max-h-48 overflow-y-auto">
            <template x-for="option in filteredOptions" :key="option.value">
                <li
                    @click="selectOption(option.value)"
                    class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white"
                >
                    <span class="block truncate" :class="{'font-semibold': value == option.value, 'font-normal': value != option.value}" x-text="option.label"></span>
                    <span
                        x-show="value == option.value"
                        class="text-indigo-600 absolute inset-y-0 right-0 flex items-center pr-4 hover:text-white"
                        style="display: none;"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </li>
            </template>
            <li x-show="filteredOptions.length === 0" class="text-gray-500 cursor-default select-none relative py-2 pl-3 pr-9" style="display: none;">
                {{ __('No results found') }}
            </li>
        </ul>
    </div>
</div>
