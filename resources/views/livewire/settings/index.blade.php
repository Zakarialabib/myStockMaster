<div x-data="{ tab: 'company' }">
    <div class="flex">
        <div class="w-1/4">
            <div class="flex flex-col space-y-2">
                <button @click="tab = 'company'" :class="{ 'bg-indigo-500 text-white': tab === 'company' }"
                    class="px-4 py-2 w-full text-left hover:bg-indigo-500 hover:text-white transition-colors">
                    {{ __('Company Info') }}
                </button>
                <button @click="tab = 'system'" :class="{ 'bg-indigo-500 text-white': tab === 'system' }"
                    class="px-4 py-2 w-full text-left hover:bg-indigo-500 hover:text-white transition-colors">
                    {{ __('System Configuration') }}
                </button>
                <button @click="tab = 'invoice'" :class="{ 'bg-indigo-500 text-white': tab === 'invoice' }"
                    class="px-4 py-2 w-full text-left hover:bg-indigo-500 hover:text-white transition-colors">
                    {{ __('Invoice Configuration') }}
                </button>
                <button @click="tab = 'mail'" :class="{ 'bg-indigo-500 text-white': tab === 'mail' }"
                    class="px-4 py-2 w-full text-left hover:bg-indigo-500 hover:text-white transition-colors">
                    {{ __('Mail Configuration') }}
                </button>
                <button @click="tab = 'mail'" :class="{ 'bg-indigo-500 text-white': tab === 'messaging' }"
                    class="px-4 py-2 w-full text-left hover:bg-indigo-500 hover:text-white transition-colors">
                    {{ __('Messaging') }}
                </button>
            </div>
        </div>
        <div class="w-3/4 px-4">
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="update">
                <div x-show="tab === 'company'">
                    <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light text-white">
                        <h2>{{ __('Company Info') }}</h2>
                    </div>
                    <div class="flex flex-wrap -mx-2 mb-3">
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="company_name" :value="__('Company Name')" required />
                            <x-input type="text" wire:model.defer="settings.company_name" id="company_name"
                                name="company_name" required />
                            <x-input-error :messages="$errors->get('settings.company_name')" class="mt-2" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="company_email" :value="__('Company Email')" required />
                            <x-input type="email" wire:model.defer="settings.company_email" id="company_email"
                                name="company_email" required />
                            <x-input-error :messages="$errors->get('settings.company_email')" class="mt-2" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="company_phone" :value="__('Company Phone')" required />
                            <x-input type="text" wire:model.defer="settings.company_phone" id="company_phone"
                                name="company_phone" required />
                            <x-input-error :messages="$errors->get('settings.company_phone')" class="mt-2" />
                        </div>

                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="company_address" :value="__('Company Address')" required />
                            <x-input type="text" wire:model.defer="settings.company_address" id="company_address"
                                name="company_address" />
                            <x-input-error :messages="$errors->get('settings.company_address')" class="mt-2" />
                        </div>

                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="company_tax" :value="__('Company Tax')" />
                            <x-input type="text" wire:model.defer="settings.company_tax" id="company_tax"
                                name="company_tax" />
                            <x-input-error :messages="$errors->get('settings.company_tax')" class="mt-2" />
                        </div>

                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="telegram_channel" :value="__('Telegram Channel')" />
                            <x-input type="text" wire:model.defer="settings.telegram_channel" id="telegram_channel"
                                name="telegram_channel" />
                            <x-input-error :messages="$errors->get('settings.telegram_channel')" class="mt-2" />
                        </div>

                        <div class="w-full px-2">
                            <x-label for="company_logo" :value="__('Company Logo')" />
                            <x-fileupload wire:model="company_logo" :file="$company_logo"
                                accept="image/jpg,image/jpeg,image/png" />
                            <x-input-error :messages="$errors->get('company_logo')" for="company_logo" class="mt-2" />
                        </div>
                    </div>
                    <div class="mb-4 w-full">
                        <x-button type="submit" wire:click="update" primary class="w-full text-center">
                            {{ __('Save Changes') }}
                        </x-button>
                    </div>
                </div>
                <div x-show="tab === 'system'">
                    <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light text-white">
                        <h2>{{ __('System Configuration') }}</h2>
                    </div>
                    <div class="flex flex-wrap -mx-2 mb-3">
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="default_currency_id" :value="__('Default currency')" required />
                            <x-select-list
                                class="block bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                id="default_currency_id" name="default_currency_id"
                                wire:model.defer="settings.default_currency_id" :options="$this->listsForFields['currencies']" required />
                        </div>

                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="default_currency_position" :value="__('Default currency position')" required />
                            <select name="default_currency_position" id="default_currency_position"
                                wire:model.defer="settings.default_currency_position"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                required>
                                <option {{ $settings->default_currency_position == 'prefix' ? 'selected' : '' }}
                                    value="prefix">
                                    {{ __('Left') }}</option>
                                <option {{ $settings->default_currency_position == 'suffix' ? 'selected' : '' }}
                                    value="suffix">
                                    {{ __('Right') }}</option>
                            </select>
                        </div>

                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="default_date_format" :value="__('Default date format')" required />
                            <select name="default_date_format" wire:model.defer="settings.default_date_format"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                required>
                                <option value="d-m-Y">DD-MM-YYYY</option>
                                <option value="d/m/Y">DD/MM/YYYY</option>
                                <option value="d.m.Y">DD.MM.YYYY</option>
                                <option value="m-d-Y">MM-DD-YYYY</option>
                                <option value="m/d/Y">MM/DD/YYYY</option>
                                <option value="m.d.Y">MM.DD.YYYY</option>
                                <option value="Y-m-d">YYYY-MM-DD</option>
                                <option value="Y/m/d">YYYY/MM/DD</option>
                                <option value="Y.m.d">YYYY.MM.DD</option>
                            </select>
                        </div>

                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="default_client_id" :value="__('Default customer')" />
                            <x-select-list wire:model.defer="settings.default_client_id" id="default_client_id"
                                name="default_client_id" :options="$this->listsForFields['customers']" r />
                        </div>

                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="default_warehouse_id" :value="__('Default Warehouse')" />
                            <x-select-list wire:model.defer="settings.default_warehouse_id" id="default_warehouse_id"
                                name="default_warehouse_id" :options="$this->listsForFields['warehouses']" />
                        </div>
                        <div class="w-full flex justify-center p-4 space-x-4">
                            <div>
                                <x-label for="show_email" :value="__('Show Email')" required />
                                <input type="checkbox" name="show_email" id="show_email"
                                    {{ $settings->show_email ? 'checked' : '' }}>
                            </div>
                            <div>
                                <x-label for="show_address" :value="__('Show Address')" required />
                                <input type="checkbox" name="show_address" id="show_address"
                                    {{ $settings->show_address ? 'checked' : '' }}>
                            </div>
                            <div>
                                <x-label for="show_order_tax" :value="__('Show Order Tax')" required />
                                <input type="checkbox" name="show_order_tax" id="show_order_tax"
                                    {{ $settings->show_order_tax ? 'checked' : '' }}>
                            </div>
                            <div>
                                <x-label for="show_discount" :value="__('Show Discount')" required />
                                <input type="checkbox" name="show_discount" id="show_discount"
                                    {{ $settings->show_discount ? 'checked' : '' }}>
                            </div>
                            <div>
                                <x-label for="show_shipping" :value="__('Show Shipping')" required />
                                <input type="checkbox" name="show_shipping" id="show_shipping"
                                    {{ $settings->show_shipping ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 w-full">
                        <x-button type="submit" wire:click="update" primary class="w-full text-center">
                            {{ __('Save Changes') }}
                        </x-button>
                    </div>
                </div>
                <div x-show="tab === 'invoice'">
                    <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light text-white">
                        <h2>{{ __('Invoice Configuration') }}</h2>
                    </div>
                    <div class="flex flex-wrap -mx-2 mb-3">
                        <div class="w-full md:w-1/2 px-3 mb-4">
                            <x-label for="invoice_header" :value="__('Invoice Header')" />
                            <x-fileupload wire:model="invoice_header" :file="$invoice_header"
                                accept="image/jpg,image/jpeg,image/png" />
                        </div>

                        <div class="w-full md:w-1/2 px-3 mb-4">
                            <x-label for="invoice_footer" :value="__('Invoice Footer')" />
                            <x-fileupload wire:model="invoice_footer" :file="$invoice_footer"
                                accept="image/jpg,image/jpeg,image/png" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="invoice_footer_text" :value="__('Invoice footer text')" />
                            <x-input type="text" wire:model.defer="settings.invoice_footer_text"
                                id="invoice_footer_text" name="invoice_footer_text" />
                            <x-input-error :messages="$errors->get('settings.invoice_footer_text')" class="mt-2" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="sale_prefix" :value="__('Sale Prefix')" />
                            <input wire:model.defer="settings.sale_prefix" type="text" id="sale_prefix"
                                name="sale_prefix"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="saleReturn_prefix" :value="__('Sale Prefix')" />
                            <input wire:model.defer="settings.saleReturn_prefix" type="text" id="saleReturn_prefix"
                                name="saleReturn_prefix"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="salePayment_prefix" :value="__('Sale Payment Prefix')" />
                            <input wire:model.defer="settings.salePayment_prefix" type="text"
                                id="salePayment_prefix" name="salePayment_prefix"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="purchase_prefix" :value="__('Purchase Prefix')" />
                            <input wire:model.defer="settings.purchase_prefix" type="text" id="purchase_prefix"
                                name="purchase_prefix"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="purchaseReturn_prefix" :value="__('Purchase Prefix')" />
                            <input wire:model.defer="settings.purchaseReturn_prefix" type="text" id="purchaseReturn_prefix"
                                name="purchaseReturn_prefix"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="purchasePayment_prefix" :value="__('Purchase Payment Prefix')" />
                            <input wire:model.defer="settings.purchasePayment_prefix" type="text"
                                id="purchasePayment_prefix" name="purchasePayment_prefix"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-4">
                            <x-label for="quotation_prefix" :value="__('Quotation Prefix')" />
                            <input wire:model.defer="settings.quotation_prefix" type="text" id="quotation_prefix"
                                name="quotation_prefix"
                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                        </div>
                    </div>
                    <div class="mb-4 w-full">
                        <x-button type="submit" wire:click="update" primary class="w-full text-center">
                            {{ __('Save Changes') }}
                        </x-button>
                    </div>
                </div>
            </form>
            <div x-show="tab === 'mail'">
                <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light text-white">
                    <h2>{{ __('Mail Configuration') }}</h2>
                </div>
                <div class="w-full px-2">
                    <livewire:settings.smtp />
                </div>
            </div>
            <div x-show="tab === 'messaging'">
                <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light text-white">
                    <h2>{{ __('Messaging') }}</h2>
                </div>
                <div class="w-full px-2">
                    <livewire:settings.messaging />
                </div>
            </div>
        </div>
    </div>
</div>