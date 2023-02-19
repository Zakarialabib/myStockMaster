<div>
    <div class="px-4 mx-auto">
        <form id="payment-form" action="{{ route('purchase-payments.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="w-full px-4">
                    @include('utils.alerts')
                    <div class="mb-4">
                        <button class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">Create Payment <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="w-full px-4">
                    <div class="card">
                        <div class="p-4">
                            <div class="flex flex-wrap -mx-2 mb-3">
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                    <div class="mb-4">
                                        <label for="reference">{{__('Reference')}} <span class="text-red-500">*</span></label>
                                        <input type="text" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" name="reference" required readonly value="INV/{{ $purchase->reference }}">
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                                    <div class="mb-4">
                                        <label for="date">{{__('Date')}} <span class="text-red-500">*</span></label>
                                        <input type="date" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" name="date" required value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            
                                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="due_amount">{{__('Due Amount')}} <span class="text-red-500">*</span></label>
                                        <input type="text" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" name="due_amount" required value="{{ format_currency($purchase->due_amount) }}" readonly>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="amount">{{__('Amount')}} <span class="text-red-500">*</span></label>
                                        <div class="input-group">
                                            <input id="amount" type="text" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" name="amount" required value="{{ old('amount') }}">
                                            <div class="input-group-append">
                                                <button id="getTotalAmount" class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded" type="button">
                                                    <i class="bi bi-check-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                                    <div class="from-group">
                                        <div class="mb-4">
                                            <label for="payment_method">{{__('Payment Method')}} <span class="text-red-500">*</span></label>
                                            <select class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" name="payment_method" id="payment_method" required>
                                                <option value="Cash">{{__('Cash')}}</option>
                                                <option value="Bank Transfer">{{__('Bank Transfer')}}</option>
                                                <option value="Cheque">{{__('Cheque')}}</option>
                                                <option value="Other">{{__('Other')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="note">{{__('Note')}}</label>
                                <textarea class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" rows="4" name="note">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
