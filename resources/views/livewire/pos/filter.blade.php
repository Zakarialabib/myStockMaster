<div>
    <div class="flex flex-wrap -mx-1">
        <div class="col-md-7">
            <div class="mb-4">
                <label>Product Category</label>
                <select wire:model="category" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded">
                    <option value="">All Products</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="mb-4">
                <label>Product Count</label>
                <select wire:model="showCount" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded">
                    <option value="9">9 Products</option>
                    <option value="15">15 Products</option>
                    <option value="21">21 Products</option>
                    <option value="30">30 Products</option>
                    <option value="">All Products</option>
                </select>
            </div>
        </div>
    </div>
</div>
