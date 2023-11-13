`<div>
    <div class="flex py-4">
        <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
            <div class="bg-white shadow-md rounded-lg px-2 py-4">
                <h2 class="text-lg font-medium mb-4">{{ __('Messaging Configuration') }}</h2>
                <div class="mb-4">
                    <label for="type">{{ __('Message Type') }}:</label>
                    <select id="type" wire:model.lazy="type" class="w-full">
                        <option value="">{{ __('Select Message Type') }}</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="telegram">Telegram</option>
                    </select>
                </div>
                @if ($type == 'telegram')
                    <div class="mb-4" x-data="{ showTooltip: false }">
                        <label for="bot-token" class="block font-medium items-center mb-1">{{ __('Bot Token') }}
                            <button class="mr-1 text-gray-500 hover:text-gray-700" @mouseover="showTooltip = true"
                                @mouseout="showTooltip = false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9 18l6-6-6-6" />
                                </svg>
                            </button>
                        </label>

                        <div x-show="showTooltip"
                            class="absolute z-50 w-64 p-4 bg-white border rounded-lg shadow-lg mt-2">
                            <p>{{ __('Here are the steps to follow') }}:</p>
                            <ul class="list-disc pl-4 mt-2">
                                <li>{{ __('Open Telegram app and search for "BotFather" (username: @BotFather).') }}
                                </li>
                                <li>{{ __('Send the message "/newbot" to BotFather.') }}</li>
                                <li>{{ __('Follow the instructions provided by BotFather to create a new bot and get the bot token.') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="chat-id" class="block font-medium mb-1">{{ __('Chat ID') }}:</label>
                        <input type="text" wire:model.lazy="chatId" id="chat-id"
                            class="w-full p-2 border rounded-lg">
                    </div>
                @elseif($type == 'whatsapp')
                    <div class="mb-4">
                        <label for="chat-id" class="block font-medium mb-1">{{ __('Phone') }}:</label>
                        <input type="text" wire:model.lazy="chatId" id="chat-id"
                            class="w-full p-2 border rounded-lg">
                    </div>
                    <div class="px-4 pb-2">
                        <button class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                            wire:click="openClientModal">
                            {{ __('Customer Phone') }}
                        </button>
                    </div>
                @endif
                <!-- Button to open the message templates modal -->
                <div class="px-4 pb-2">
                    <button class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                        wire:click="openTemplate">
                        {{ __('Use Message Templates') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="w-full sm:w-1/2 md:w-2/3 lg:w-3/4 p-4 h-auto">
            <div class="bg-white shadow-md rounded-lg px-2 py-4">
                <h2 class="text-lg font-medium mb-4">{{ __('Message Content') }}</h2>
                @if ($type == 'whatsapp' || $type == 'telegram')
                    <div class="mb-4">
                        <x-input.textarea id="message" wire:model.lazy="message" />
                    </div>
                    <div class="px-4">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full"
                            wire:click="sendMessage">
                            {{ __('Send Message') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>


        <!-- Message templates modal -->
        <x-modal wire:model="openTemplate">
            <x-slot name="title">
                {{ __('Message Templates') }}
            </x-slot>
            <x-slot name="content">
                <div class="py-3">
                    <ul class="border rounded-md divide-y">
                        <li class="flex justify-between items-center py-2 px-3">
                            <span class="font-bold">Product Information</span>
                            <button wire:click="fillMessage('productMessage')"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                                Use
                            </button>
                        </li>
                        <li class="flex justify-between items-center py-2 px-3">
                            <span class="font-bold">Client Message</span>
                            <button wire:click="fillMessage('clientMessage')"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                                Use
                            </button>
                        </li>
                        <li class="flex justify-between items-center py-2 px-3">
                            <span class="font-bold">Empty Message</span>
                            <button wire:click="fillMessage('')"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                                Use
                            </button>
                        </li>
                    </ul>
                </div>
            </x-slot>
        </x-modal>


        <!-- Product selection modal -->
        <x-modal wire:model="openProductModal">
            <x-slot name="title">
                {{ __('Select a Product') }}
            </x-slot>
            <x-slot name="content">
                <div class="py-3">
                    <ul class="border rounded-md divide-y">
                        @foreach ($this->products as $product)
                            <li class="flex justify-between items-center py-2 px-3">
                                <span class="font-bold">{{ $product->name }}</span>
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                                    wire:click="insertProduct({{ $product->id }})">Use</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </x-slot>
        </x-modal>
        
        <x-modal wire:model="openClientModal">
            <x-slot name="title">
                {{ __('Select a Client') }}
            </x-slot>
            <x-slot name="content">
                <div class="py-3">
                    <ul class="border rounded-md divide-y">
                        @foreach ($this->customers as $customer)
                            <li class="flex justify-between items-center py-2 px-3">
                                <span class="font-bold">{{ $customer->name }}</span>
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                                    wire:click="selectCustomer({{ $customer->id }})">{{__('Select')}}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </x-slot>
        </x-modal>

        {{-- selectCustomer --}}
    </div>
</div>


@push('scripts')
    <script>
        Livewire.on('openUrl', url => {
            window.open(url, '_blank');
        });
    </script>
@endpush
