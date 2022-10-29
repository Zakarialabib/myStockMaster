<div>
    <div class="flex flex-wrap -mx-1">
        <div class="md:w-1/3 px-2">
            <x-label for="category" :value="__('Product Category')" />
            <select wire:model="category"
                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                <option value="">{{ __('All Products') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:w-1/3 px-2">
            <x-label for="warehouse" :value="__('Warehouse')" />
            <x-select-list
                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                required id="warehouses" name="warehouses" wire:model="warehouse_id" :options="$this->listsForFields['warehouses']" />
        </div>
        <div class="md:w-1/3 px-2">
            <x-label for="showCount" :value="__('Product per page')" />
            <select wire:model="showCount"
                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                <option value="9">9</option>
                <option value="15">15</option>
                <option value="21">21</option>
                <option value="30">30</option>
                <option value="">{{ __('All Products') }}</option>
            </select>
        </div>
    </div>
</div>
