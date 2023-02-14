<div>
    <div class="max-w-md mx-auto my-10 bg-white p-6 rounded-md shadow-md">
        <h1 class="text-lg font-semibold mb-4">{{ __('Sync Your Inventory with Ecommerce') }}</h1>

        <div class="mb-4">
            <label for="url" class="block font-medium mb-2">{{ __('Website URL') }}</label>
            <input type="text" wire:model.defer="custom_store_url" id="url" name="url"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>
        <h3 class="pu-4 text-center">
            {{ $product_count }}
        </h3>
        <div class="mb-4">
            <button wire:click="createToken" type="button"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Create Token') }}
            </button>

            @if ($token)
                <div class="mt-4">
                    <p class="font-medium">{{ __('API Token') }}</p>

                    <div class="flex items-center">
                        <p class="mr-2">{{ $token }}</p>
                    </div>
                </div>
            @endif

            @if ($authenticated)
                <div class="mt-4">
                    <p class="font-medium">{{ __('Products Synced') }}</p>
                    <p class="text-green-500">
                        {{ __('Your inventory has been successfully synced with your ecommerce platform.') }}</p>
                </div>
            @endif

            <button wire:click="authenticate"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                {{ __('Authenticate') }}
            </button>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 text-green-800 py-2 px-4 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 text-red-800 py-2 px-4 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <button wire:click="deleteToken" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Delete Token') }}
        </button>
    </div>
</div>
