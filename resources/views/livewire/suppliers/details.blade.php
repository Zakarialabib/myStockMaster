<div>
    <div class="w-full flex flex-wrap align-center mb-4 js-date-row">
        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 w-full">
            <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                <div>
                    <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                        {{ __('Purchases Total') }}
                    </p>
                    <p class="text-3xl sm:text-lg font-bold text-gray-700 dark:text-gray-300">
                        {{ format_currency($this->TotalPurchases) }}
                    </p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                <div>
                    <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                        {{ __('Total Payments') }}
                    </p>
                    <p class="text-3xl sm:text-lg font-bold text-gray-700 dark:text-gray-300">
                        {{ format_currency($this->TotalPayments) }}
                    </p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                <div>
                    <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                        {{ __('Total Purchase Returns') }}
                    </p>
                    <p class="text-3xl sm:text-lg font-bold text-gray-700 dark:text-gray-300">
                        {{ format_currency($this->TotalPurchaseReturns) }}
                    </p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-white dark:bg-dark-bg dark:text-gray-300 rounded-lg shadow-md">
                <div>
                    <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">
                        {{ __('Credit') }}
                    </p>
                    <p class="text-3xl sm:text-lg font-bold text-gray-700 dark:text-gray-300">
                        {{ format_currency($this->Debit) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto">
        <div class="flex flex-wrap px-4">
            <div class="w-1/2 sm:w-full px-2">
                // show purchaseInvoices

            </div>
            <div class="w-1/2 sm:w-full px-2">
                // show purchasePayments

            </div>
        </div>
    </div>
</div>
