<div>
    <x-card>
        <div class="py-3 px-6 mb-0 bg-gray-200 border-b-1 border-gray-light text-gray-500">
            <h5 class="mb-0">{{ __('General Settings') }}</h5>
        </div>
        <div class="p-4">
            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-1">
                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="company_name" :value="__('Company Name')" required />
                        <input type="text" wire:model.defer="settings.company_name" id="company_name"
                            name="company_name"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required>
                    </div>
                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="company_email" :value="__('Company Email')" required />
                        <input type="text" wire:model.defer="settings.company_email" id="company_email"
                            name="company_email" required
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    </div>
                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="company_phone" :value="__('Company Phone')" required />
                        <input type="text" wire:model.defer="settings.company_phone" id="company_phone"
                            name="company_phone" required
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    </div>

                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="company_address" :value="__('Company Address')" required />
                        <input type="text" wire:model.defer="settings.company_address" id="company_address"
                            name="company_address"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    </div>

                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="company_tax" :value="__('Company Tax')" />
                        <input type="text" wire:model.defer="settings.company_tax" id="company_tax"
                            name="company_tax"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    </div>

                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="default_currency_id" :value="__('Default currency')" required />
                        <x-select-list
                            class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                            id="default_currency_id" name="default_currency_id"
                            wire:model.defer="settings.default_currency_id" :options="$this->listsForFields['currencies']" required />
                    </div>
                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="default_currency_position" :value="__('Default currency position')" required />
                        <select name="default_currency_position" id="default_currency_position"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required>
                            <option {{ $settings->default_currency_position == 'prefix' ? 'selected' : '' }}
                                value="prefix">{{ __('Prefix') }}</option>
                            <option {{ $settings->default_currency_position == 'suffix' ? 'selected' : '' }}
                                value="suffix">{{ __('Suffix') }}</option>
                        </select>
                    </div>

                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                        <x-label for="footer_text" :value="__('Footer Text')" required />
                        <input wire:model.defer="settings.footer_text" disabled type="text" id="footer_text"
                            name="footer_text"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                    </div>

                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="default_client_id" :value="__('Default customer')" />
                        <x-select-list wire:model.defer="settings.default_client_id" id="default_client_id"
                            name="default_client_id" :options="$this->listsForFields['customers']" r />
                    </div>

                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="default_warehouse_id" :value="__('Default Warehouse')" />
                        <x-select-list wire:model.defer="settings.default_warehouse_id" id="default_warehouse_id"
                            name="default_warehouse_id" :options="$this->listsForFields['warehouses']" />
                    </div>

                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="invoice_footer" :value="__('Invoice Footer')" />
                        <input wire:model.defer="settings.invoice_footer" type="text" id="invoice_footer"
                            name="invoice_footer"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                    </div>

                    <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                        <x-label for="invoice_prefix" :value="__('Invoice Prefix')" />
                        <input wire:model.defer="settings.invoice_prefix" type="text" id="invoice_prefix"
                            name="invoice_prefix"
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                    </div>
                    <div class="w-full flex justify-center md:w-1/3 px-4 mb-4 md:mb-0">
                        <div class="px-2">
                            <x-label for="notification_email" :value="__('Activate Notification Email')" required />
                            <input type="checkbox" name="notification_email" id="notification_email"
                                {{ $settings->notification_email ? 'checked' : '' }}>
                        </div>
                        <div class="px-2">
                            <x-label for="is_rtl" :value="__('Activate RTL')" required />
                            <input type="checkbox" name="is_rtl" id="is_rtl"
                                {{ $settings->is_rtl ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="w-full flex justify-center p-4">
                        
                        <div class="px-2">
                            <x-label for="is_invoice_footer" :value="__('Activate Invoice Footer')" required />
                            <input type="checkbox" name="is_invoice_footer" id="is_invoice_footer"
                                {{ $settings->is_invoice_footer ? 'checked' : '' }}>
                        </div>
                    
                        <div class="px-2">
                            <x-label for="show_email" :value="__('Show Email')" required />
                            <input type="checkbox" name="show_email" id="show_email"
                                {{ $settings->show_email ? 'checked' : '' }}>
                        </div>
                        <div class="px-2">
                            <x-label for="show_address" :value="__('Show Address')" required />
                            <input type="checkbox" name="show_address" id="show_address"
                                {{ $settings->show_address ? 'checked' : '' }}>
                        </div>
                        <div class="px-2">
                            <x-label for="show_order_tax" :value="__('Show Order Tax')" required />
                            <input type="checkbox" name="show_order_tax" id="show_order_tax"
                                {{ $settings->show_order_tax ? 'checked' : '' }}>
                        </div>
                        <div class="px-2">
                            <x-label for="show_discount" :value="__('Show Discount')" required />
                            <input type="checkbox" name="show_discount" id="show_discount"
                                {{ $settings->show_discount ? 'checked' : '' }}>
                        </div>
                        <div class="px-2">
                            <x-label for="show_shipping" :value="__('Show Shipping')" required />
                            <input type="checkbox" name="show_shipping" id="show_shipping"
                                {{ $settings->show_shipping ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <div class="mb-4 md:mb-0">
                    <x-button type="submit" primary>
                        {{ __('Save Changes') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-card>
</div>
