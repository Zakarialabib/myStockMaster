<div>
     <iv class="relative mb-4">
        <div class="w-full rounded-lg">
            <x-input wire:model.lazy="searchQuery" autofocus
            placeholder="{{ __('Search with names and codes, or reference') }}" />
        </div>
        @if (!empty($searchQuery))
            <div class="absolute top-0 left-0 w-full mt-12 bg-white rounded-md shadow-xl overflow-y-auto max-h-52 z-50">
                <ul>
                    @if ($this->product->count())
                       <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-700 mb-2">{{ __('Product') }}</h3>
                            @foreach ($this->product as $item)
                               <div class="mx-4">
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Name') }} <br>
                                       {{ $item->name }}
                                    </p>
                                    <p class="font-semibold text-gray-700">
                                       {{ __('Price') }} <br>
                                        {{ format_currency($item->price) }}
                                    </p>
                                   <p class="font-semibold text-gray-700">
                                        {{ __('Price') }} <br>
                                        {{ format_currency($item->cost) }}
                                   </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Quantity') }} <br>
                                       {{ $item->quantity }}
                                    </p>
                                </div>
                           @endforeach
                        </li>
                    @endif
                   @if ($this->customer->count())
                        <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-700 mb-2">{{ __('Customer') }}</h3>
                           @foreach ($this->customer as $item)
                                <div class="mx-4">
                                    <p class="font-semibold text-gray-700">
                                       {{ __('Name') }} <br>
                                        {{ $item->name }}
                                    </p>
                                   <p class="font-semibold text-gray-700">
                                        {{ __('Phone') }} <br>
                                        {{ $item->phone }}
                                   </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Purchases amount') }} <br>
                                   {{ format_currency($item->totalPurchases) }}
                                </p>
                                   <p class="font-semibold text-gray-700">
                                        {{ __('Total Paid amount') }} <br>
                                    {{ format_currency($item->totalPayments) }}
                                </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Total Due amount') }} <br>
                                    {{ format_currency($item->debit) }}
                                </p>
                               </div>
                            @endforeach
                        </li>
                   @endif
                    @if ($this->supplier->count())
                        <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                           <h3 class="font-semibold text-gray-700 mb-2">{{ __('Supplier') }}</h3>
                            @foreach ($this->supplier as $item)
                                <div class="mx-4">
                                   <p class="font-semibold text-gray-700">
                                        {{ __('Name') }} <br>
                                        {{ $item->name }}
                                   </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Phone') }} <br>
                                       {{ $item->phone }}
                                    </p>
                                    {{-- Adding a button link to details --}}
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Purchases amount') }} <br>
                                   {{ format_currency($item->totalPurchases) }}
                                </p>
                                   <p class="font-semibold text-gray-700">
                                        {{ __('Total Paid amount') }} <br>
                                    {{ format_currency($item->totalPayments) }}
                                </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Total Due amount') }} <br>
                                    {{ format_currency($item->debit) }}
                                </p>
                               </div>
                            @endforeach
                        </li>
                    @endif
                    @if ($this->sale->count())
                        <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                           <h3 class="font-semibold text-gray-700 mb-2">{{ __('Sale') }}</h3>
                            @foreach ($this->sale as $item)
                                <div class="mx-4">
                                   <p class="font-semibold text-gray-700">{{ __('Date') }} :{{ $item->date }}
                                    </p>
                                    <p class="font-semibold text-gray-700">{{ __('Customer name') }}
                                        :{ $item->customer->name }}</p>
                                    <p class="font-semibold text-gray-700">{{ __('Reference') }}
                                        :{{ $item->reference }}</p>
                                   <p class="font-semibold text-gray-700">{{ __('Total amount') }}
                                        :{{ format_currency($item->total_amount) }}</p>
                                    <p class="font-semibold text-gray-700">{{ __('Paid amount') }}
                                        :{ format_currency($item->paid_amount) }}</p>
                                    <p class="font-semibold text-gray-700">{{ __('Due amount') }}
                                        :{{ format_currency($item->due_amount) }}</p>
                               </div>
                            @endforeach
                        </li>
                    @endif
                    @if ($this->purchase->count())
                        <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                           <h3 class="font-semibold text-gray-700 mb-2">{{ __('Purchase') }}</h3>
                            @foreach ($this->purchase as $item)
                                <div class="mx-4">
                                   <p class="font-semibold text-gray-700">{{ __('Date') }} <br>
                                        {{ $item->date }}
                                    </p>
                                   <p class="font-semibold text-gray-700">{{ __('Supplier name') }}
                                        <br>
                                        {{ $item->supplier->name }}
                                   </p>
                                    <p class="font-semibold text-gray-700">{{ __('Reference') }}
                                        <br>
                                       {{ $item->reference }}
                                    </p>
                                    <p class="font-semibold text-gray-700">{{ __('Total amount') }}
                                       <br>
                                        {{ format_currency($item->total_amount) }}
                                    </p>
                                   <p class="font-semibold text-gray-700">{{ __('Paid amount') }}
                                        <br>
                                        {{ format_currency($item->paid_amount) }}
                                   </p>
                                    <p class="font-semibold text-gray-700">{{ __('Due amount') }}
                                        <br>
                                        {{ format_currency($item->due_amount) }}
                                    </p>
                                </div>
                           @endforeach
                        </li>
                    @endif
                    </li>
                </ul>
            </div>
        @endif
    </div>
</div>
