<div>
    <div class="flex flex-wrap -mx-1">
        <div class="md:w-3/5 px-2">
            <div class="mb-4">
                <label>{{__('Product Category')}}</label>
                <select wire:model="category" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded">
                    <option value="">{{__('All Products')}}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="md:w-2/5 px-2">
            <div class="mb-4">
                <label>{{__('Product Count')}}</label>
                <select wire:model="showCount" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded">
                    <option value="9">9</option>
                    <option value="15">15</option>
                    <option value="21">21</option>
                    <option value="30">30</option>
                    <option value="">{{__('All Products')}}</option>
                </select>
            </div>
        </div>
    </div>
</div>
