<div>
    <x-page-container title="{{ __('Settings') }}" :breadcrumbs="[['label' => __('Dashboard'), 'url' => route('dashboard')], ['label' => __('Settings')]]">
        <form wire:submit="update" x-data="{ isDirty: false }" @change="isDirty = true" @settings-saved.window="isDirty = false" class="space-y-6">

            <!-- Sticky Save Button -->
            <div x-show="isDirty" x-transition.opacity.duration.300ms
                class="sticky top-0 z-50 bg-indigo-50 dark:bg-gray-800 p-4 rounded-lg shadow-md border border-indigo-200 dark:border-indigo-800 flex justify-between items-center mb-6">
                <div class="flex items-center text-indigo-700 dark:text-indigo-300">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span class="text-sm font-medium">{{ __('You have unsaved changes.') }}</span>
                </div>
                <x-button type="submit" primary>
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Save Changes') }}
                </x-button>
            </div>

            <div x-data="{ 
                tab: 'general', 
                subtab: 'company',
                infoTitle: '{{ __('Settings') }}', 
                infoDesc: '{{ __('Hover or click on any setting field to see detailed information about what it does and where it is used.') }}',
                infoPrivacy: ''
            }" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left Sidebar (Nested) -->
                    <div class="lg:col-span-3">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <!-- General Section -->
                            <div class="border-b border-gray-200 dark:border-gray-700">
                                <button type="button" @click="tab = 'general'; subtab = 'company'" class="w-full px-4 py-3 text-left text-sm font-bold bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white flex items-center justify-between">
                                    <span class="flex items-center space-x-2"><i class="fas fa-cog w-4 h-4"></i> <span>{{ __('General') }}</span></span>
                                    <i class="fas fa-chevron-down text-xs" x-show="tab === 'general'"></i>
                                </button>
                                <div x-show="tab === 'general'" class="flex flex-col py-1">
                                    <button type="button" @click="subtab = 'company'" :class="{ 'text-indigo-600 font-medium': subtab === 'company', 'text-gray-600 hover:text-indigo-500': subtab !== 'company' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('Company Info') }}</button>
                                    <button type="button" @click="subtab = 'system'" :class="{ 'text-indigo-600 font-medium': subtab === 'system', 'text-gray-600 hover:text-indigo-500': subtab !== 'system' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('System Config') }}</button>
                                    <button type="button" @click="subtab = 'siteConfig'" :class="{ 'text-indigo-600 font-medium': subtab === 'siteConfig', 'text-gray-600 hover:text-indigo-500': subtab !== 'siteConfig' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('Site Config') }}</button>
                                    <button type="button" @click="subtab = 'analyticsConfig'" :class="{ 'text-indigo-600 font-medium': subtab === 'analyticsConfig', 'text-gray-600 hover:text-indigo-500': subtab !== 'analyticsConfig' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('Analytics Config') }}</button>
                                    <button type="button" @click="subtab = 'appearance'" :class="{ 'text-indigo-600 font-medium': subtab === 'appearance', 'text-gray-600 hover:text-indigo-500': subtab !== 'appearance' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('Appearance') }}</button>
                                </div>
                            </div>
                            <!-- Invoicing Section -->
                            <div class="border-b border-gray-200 dark:border-gray-700">
                                <button type="button" @click="tab = 'invoicing'; subtab = 'invoice'" class="w-full px-4 py-3 text-left text-sm font-bold bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white flex items-center justify-between">
                                    <span class="flex items-center space-x-2"><i class="fas fa-file-invoice w-4 h-4"></i> <span>{{ __('Invoicing') }}</span></span>
                                    <i class="fas fa-chevron-down text-xs" x-show="tab === 'invoicing'"></i>
                                </button>
                                <div x-show="tab === 'invoicing'" class="flex flex-col py-1">
                                    <button type="button" @click="subtab = 'invoice'" :class="{ 'text-indigo-600 font-medium': subtab === 'invoice', 'text-gray-600 hover:text-indigo-500': subtab !== 'invoice' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('Templates & Prefixes') }}</button>
                                </div>
                            </div>
                            <!-- Notifications Section -->
                            <div>
                                <button type="button" @click="tab = 'notifications'; subtab = 'channels'" class="w-full px-4 py-3 text-left text-sm font-bold bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white flex items-center justify-between">
                                    <span class="flex items-center space-x-2"><i class="fas fa-bell w-4 h-4"></i> <span>{{ __('Notifications') }}</span></span>
                                    <i class="fas fa-chevron-down text-xs" x-show="tab === 'notifications'"></i>
                                </button>
                                <div x-show="tab === 'notifications'" class="flex flex-col py-1">
                                    <button type="button" @click="subtab = 'channels'" :class="{ 'text-indigo-600 font-medium': subtab === 'channels', 'text-gray-600 hover:text-indigo-500': subtab !== 'channels' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('Delivery Channels') }}</button>
                                    <button type="button" @click="subtab = 'triggers'" :class="{ 'text-indigo-600 font-medium': subtab === 'triggers', 'text-gray-600 hover:text-indigo-500': subtab !== 'triggers' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('Event Triggers') }}</button>
                                    <button type="button" @click="subtab = 'logs'" :class="{ 'text-indigo-600 font-medium': subtab === 'logs', 'text-gray-600 hover:text-indigo-500': subtab !== 'logs' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('Notification Logs') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Center Form Content -->
                    <div class="lg:col-span-6">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <x-validation-errors class="mb-4" :errors="$errors" />
                            <div x-show="subtab === 'company'" class="p-6">
                                <div class="mb-6">
                                    <h2
                                        class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                                        <i class="fas fa-building text-indigo-600"></i>
                                        <span>{{ __('Company Info') }}</span>
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ __('Configure your company information and branding') }}
                                    </p>
                                </div>
                                <div class="grid grid-cols-3 gap-4 mb-3">
                                    <div class="w-full">
                                        <x-label for="company_name" :value="__('Company Name')" required />
                                        <x-input type="text" wire:model="form.company_name" id="company_name" @focus="infoTitle = 'Company Name'; infoDesc = 'Your official business name. This is displayed on the login screen, dashboard header, and is printed on all generated invoices, quotations, and reports.'; infoPrivacy = 'Public'"
                                            name="company_name" required />
                                        <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                                    </div>
                                    <div class="w-full">
                                        <x-label for="company_email" :value="__('Company Email')" required />
                                        <x-input type="email" wire:model="form.company_email" id="company_email" @focus="infoTitle = 'Company Email'; infoDesc = 'The primary contact email for your business. Used as the reply-to address for system emails.'; infoPrivacy = 'Public'"
                                            name="company_email" required />
                                        <x-input-error :messages="$errors->get('company_email')" class="mt-2" />
                                    </div>
                                    <div class="w-full">
                                        <x-label for="company_phone" :value="__('Company Phone')" required />
                                        <x-input type="text" wire:model="form.company_phone" id="company_phone"
                                            name="company_phone" required />
                                        <x-input-error :messages="$errors->get('company_phone')" class="mt-2" />
                                    </div>

                                    <div class="w-full">
                                        <x-label for="company_address" :value="__('Company Address')" required />
                                        <x-input type="text" wire:model="form.company_address" id="company_address"
                                            name="company_address" />
                                        <x-input-error :messages="$errors->get('company_address')" class="mt-2" />
                                    </div>

                                    <div class="w-full">
                                        <x-label for="company_tax" :value="__('Company Tax')" />
                                        <x-input type="text" wire:model="form.company_tax" id="company_tax"
                                            name="company_tax" />
                                        <x-input-error :messages="$errors->get('company_tax')" class="mt-2" />
                                    </div>

                                    <div class="w-full">
                                        <x-label for="telegram_channel" :value="__('Telegram Channel')" />
                                        <x-input type="text" wire:model="form.telegram_channel" id="telegram_channel"
                                            name="telegram_channel" />
                                        <x-input-error :messages="$errors->get('telegram_channel')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="w-full px-2">
                                        <x-label for="site_logo" :value="__('Logo')" />
                                        <x-media-upload title="{{ __('Logo') }}" name="site_logo" :file="$form->site_logo"
                                            path="images/" single types="PNG / JPEG / WEBP" fileTypes="image/*" />

                                        <x-input-error :messages="$errors->get('site_logo')" for="site_logo" class="mt-2" />
                                    </div>
                                    <div class="w-full px-2">
                                        <x-label for="site_favicon" :value="__('Favicon')" />

                                        <x-media-upload title="{{ __('Favicon') }}" name="site_favicon"
                                            :file="$form->site_favicon" path="images/" single types="PNG / JPEG / WEBP"
                                            fileTypes="image/*" />

                                        <x-input-error :messages="$errors->get('site_favicon')" for="site_favicon" class="mt-2" />
                                    </div>
                                </div>
                                <div class="mb-4 w-full">
                                    <x-button type="submit" wire:click="update" primary class="w-full text-center">
                                        {{ __('Save Changes') }}
                                    </x-button>
                                </div>
                            </div>
                            <div x-show="subtab === 'system'" class="p-6">
                                <div class="mb-6">
                                    <h2
                                        class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                                        <i class="fas fa-cogs text-indigo-600"></i>
                                        <span>{{ __('System Configuration') }}</span>
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ __('Configure system defaults and preferences') }}
                                    </p>
                                </div>
                                <div class="grid grid-cols-3 gap-4 mb-3">
                                    <div>
                                        <x-label for="default_currency_id" :value="__('Default currency')" required />
                                        <x-select-list
                                            class="block bg-white text-gray-700 rounded-sm border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                            id="default_currency_id" name="default_currency_id"
                                            wire:model="form.default_currency_id" @focus="infoTitle = 'Default Currency'; infoDesc = 'The base currency used for all transactions, reports, and dashboard totals.'; infoPrivacy = 'Internal'" :options="$this->currencies" required />
                                    </div>

                                    <div>
                                        <x-label for="default_currency_position" :value="__('Default currency position')" required />
                                        <x-select name="default_currency_position" id="default_currency_position"
                                            wire:model="form.default_currency_position"
                                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                            required>
                                            <option
                                                {{ settings('default_currency_position') == 'prefix' ? 'selected' : '' }}
                                                value="prefix">
                                                {{ __('Left') }}
                                            </option>
                                            <option
                                                {{ settings('default_currency_position') == 'suffix' ? 'selected' : '' }}
                                                value="suffix">
                                                {{ __('Right') }}
                                            </option>
                                        </x-select>
                                    </div>

                                    <div>
                                        <x-label for="default_date_format" :value="__('Default date format')" required />
                                        <x-select name="default_date_format" wire:model="form.default_date_format"
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
                                        </x-select>
                                    </div>

                                    <div>
                                        <x-label for="default_client_id" :value="__('Default customer')" />
                                        <x-select-list wire:model="form.default_client_id" id="default_client_id"
                                            name="default_client_id" :options="$this->customers" />
                                    </div>

                                    <div>
                                        <x-label for="default_warehouse_id" :value="__('Default Warehouse')" />
                                        <x-select-list wire:model.live="form.default_warehouse_id"
                                            id="default_warehouse_id" name="default_warehouse_id"
                                            :options="$this->warehouses" />
                                    </div>

                                    <div>
                                        <div class="flex items-center space-x-2 mt-6">
                                            <x-input.checkbox id="is_rtl" wire:model="form.is_rtl" @focus="infoTitle = 'Enable RTL'; infoDesc = 'Toggle Right-to-Left layout for Arabic, Hebrew, or Urdu languages.'; infoPrivacy = 'Internal'" />
                                            <x-label for="is_rtl" :value="__('Enable RTL')" />
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ __('Toggle Right-to-Left layout for Arabic, Hebrew, or Urdu languages.') }}</p>
                                    </div>

                                    <div>
                                        <div class="flex items-center space-x-2 mt-6">
                                            <x-checkbox id="is_ecommerce_active" wire:model="form.is_ecommerce_active" />
                                            <x-label for="is_ecommerce_active" :value="__('Enable E-commerce')" />
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ __('Toggle e-commerce features for your application.') }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-900 mb-4">
                                        {{ __('POS Configuration') }}
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-label for="receipt_printer_type" :value="__('Receipt Printer Type')" />
                                            <x-select name="receipt_printer_type" id="receipt_printer_type"
                                                wire:model.live="form.receipt_printer_type"
                                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                                <option value="browser">{{ __('Browser Based Printing') }}</option>
                                                <option value="printer">{{ __('Configured Receipt Printer') }}</option>
                                            </x-select>
                                        </div>
                                        <div>
                                            <x-label for="printer_id" :value="__('Printer ID')" />
                                            <x-input type="text" wire:model.live="form.printer_id" id="printer_id"
                                                name="printer_id" class="mt-1 block w-full" />
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-900 mb-4">
                                        {{ __('Invoice Control Settings') }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mb-4">{{ __('Toggle specific fields to show or hide them on your generated invoices.') }}</p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach ($invoice_control as $index => $control)
                                        <div class="flex items-center space-x-2">
                                            <x-input.checkbox
                                                id=" invoice_control_{{ $index }}"
                                                wire:model.live="invoice_control.{{ $index }}.status"
                                                wire:change="updatedInvoiceControl('{{ $control['name'] }}')" />
                                            <x-label for="invoice_control_{{ $index }}" :value="__(str_replace('_', ' ', ucfirst($control['name'])))" class="text-sm" />
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <x-button type="submit" wire:click="update" primary class="w-full">
                                        {{ __('Save Changes') }}
                                    </x-button>
                                </div>
                            </div>
                            <div x-show="subtab === 'invoice'">
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-linear-to-r from-green-600 to-emerald-600 px-6 py-4 border-b border-gray-200">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-file-invoice text-white text-lg"></i>
                                            <h2 class="text-lg font-semibold text-white">{{ __('Invoice Configuration') }}</h2>
                                        </div>
                                        <p class="text-sm text-green-100 mt-2">{{ __('Customize your invoice templates, document prefixes, and default footer text.') }}</p>
                                    </div>
                                    <div class="p-6">
                                        <div class="mb-8">
                                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('Select invoice template') }}
                                            </h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <x-label for="invoice_template" :value="__('Invoice Template')" />
                                                    <x-select wire:model.live="form.invoice_template" id="invoice_template" @focus="infoTitle = 'Invoice Template'; infoDesc = 'Select the visual design template for all generated invoices and quotations.'; infoPrivacy = 'Public'"
                                                        name="invoice_template"
                                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <option value="invoice-{{ $i }}">Invoice
                                                            {{ $i }}
                                                            </option>
                                                            @endfor
                                                    </x-select>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('Preview') }}</label>
                                                    <img src="{{ asset('assets/screens/' . $form->invoice_template . '.png') }}"
                                                        alt="Invoice Preview"
                                                        class="w-full h-auto rounded-lg border border-gray-200">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-8">
                                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('Invoice Header & Footer Images') }}</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <x-label for="invoice_header" :value="__('Invoice Header Image')" />
                                                    <input type="file" wire:model="invoice_header" id="invoice_header" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                                    <div wire:loading wire:target="invoice_header" class="mt-2 text-sm text-gray-500">{{ __('Uploading...') }}</div>
                                                    @if ($invoice_header)
                                                        <div class="mt-2">
                                                            <img src="{{ $invoice_header->temporaryUrl() }}" class="w-full h-auto rounded border" alt="Header Preview">
                                                        </div>
                                                    @elseif (settings('invoice_header'))
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/settings/'.settings('invoice_header')) }}" class="w-full h-auto rounded border" alt="Header Preview">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <x-label for="invoice_footer" :value="__('Invoice Footer Image')" />
                                                    <input type="file" wire:model="invoice_footer" id="invoice_footer" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                                    <div wire:loading wire:target="invoice_footer" class="mt-2 text-sm text-gray-500">{{ __('Uploading...') }}</div>
                                                    @if ($invoice_footer)
                                                        <div class="mt-2">
                                                            <img src="{{ $invoice_footer->temporaryUrl() }}" class="w-full h-auto rounded border" alt="Footer Preview">
                                                        </div>
                                                    @elseif (settings('invoice_footer'))
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/settings/'.settings('invoice_footer')) }}" class="w-full h-auto rounded border" alt="Footer Preview">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-8">
                                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('Invoice Footer') }}</h3>
                                            <div>
                                                <x-label for="invoice_footer_text" :value="__('Invoice footer text')" />
                                                <x-input type="text" wire:model="form.invoice_footer_text" id="invoice_footer_text"
                                                    name="invoice_footer_text" />
                                                <x-input-error :messages="$errors->get('invoice_footer_text')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="mb-8">
                                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('Document Prefixes') }}</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <div>
                                                    <x-label for="sale_prefix" :value="__('Sale Prefix')" />
                                                    <x-input wire:model="form.sale_prefix" type="text" id="sale_prefix"
                                                        name="sale_prefix" class="mt-1" />
                                                </div>
                                                <div>
                                                    <x-label for="saleReturn_prefix" :value="__('Sale Return Prefix')" />
                                                    <x-input wire:model="form.saleReturn_prefix" type="text" id="saleReturn_prefix"
                                                        name="saleReturn_prefix" class="mt-1" />
                                                </div>
                                                <div>
                                                    <x-label for="salePayment_prefix" :value="__('Sale Payment Prefix')" />
                                                    <x-input wire:model="form.salePayment_prefix" type="text"
                                                        id="salePayment_prefix" name="salePayment_prefix" class="mt-1" />
                                                </div>
                                                <div>
                                                    <x-label for="purchase_prefix" :value="__('Purchase Prefix')" />
                                                    <x-input wire:model="form.purchase_prefix" type="text" id="purchase_prefix"
                                                        name="purchase_prefix" class="mt-1" />
                                                </div>
                                                <div>
                                                    <x-label for="purchaseReturn_prefix" :value="__('Purchase Return Prefix')" />
                                                    <x-input wire:model="form.purchaseReturn_prefix" type="text"
                                                        id="purchaseReturn_prefix" name="purchaseReturn_prefix" class="mt-1" />
                                                </div>
                                                <div>
                                                    <x-label for="purchasePayment_prefix" :value="__('Purchase Payment Prefix')" />
                                                    <x-input wire:model="form.purchasePayment_prefix" type="text"
                                                        id="purchasePayment_prefix" name="purchasePayment_prefix"
                                                        class="mt-1" />
                                                </div>
                                                <div>
                                                    <x-label for="quotation_prefix" :value="__('Quotation Prefix')" />
                                                    <x-input wire:model="form.quotation_prefix" type="text" id="quotation_prefix"
                                                        name="quotation_prefix" class="mt-1" />
                                                </div>
                                                <div>
                                                    <x-label for="expense_prefix" :value="__('Expense Prefix')" />
                                                    <x-input wire:model="form.expense_prefix" type="text" id="expense_prefix"
                                                        name="expense_prefix" class="mt-1" />
                                                </div>
                                                <div>
                                                    <x-label for="delivery_prefix" :value="__('Delivery Prefix')" />
                                                    <x-input wire:model="form.delivery_prefix" type="text" id="delivery_prefix"
                                                        name="delivery_prefix" class="mt-1" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6 pt-6 border-t border-gray-200">
                                            <x-button type="submit" wire:click="update" primary class="w-full">
                                                {{ __('Save Changes') }}
                                            </x-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="subtab === 'siteConfig'">
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-linear-to-r from-blue-600 to-indigo-600 px-6 py-4 border-b border-gray-200">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-globe text-white text-lg"></i>
                                            <h2 class="text-lg font-semibold text-white">{{ __('Site Configuration') }}</h2>
                                        </div>
                                        <p class="text-sm text-blue-100 mt-2">{{ __('Manage your website title, SEO settings, social media links, and custom scripts.') }}</p>
                                    </div>
                                    <div class="p-6">
                                        <div class="mb-8">
                                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('Basic Information') }}</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <x-label for="site_title" :value="__('Website title')" />
                                                    <x-input type="text" wire:model="form.site_title" id="site_title" @focus="infoTitle = 'Website Title'; infoDesc = 'The main title of your application displayed in the browser tab and search engines.'; infoPrivacy = 'Public'" />
                                                    <x-input-error for="site_title" :messages="$errors->first('site_title')" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-8">
                                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('SEO Settings') }}</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <x-label for="seo_meta_title" :value="__('Seo Meta Title')" />
                                                    <x-input wire:model="form.seo_meta_title" type="text" id="seo_meta_title"
                                                        name="seo_meta_title" />
                                                    <x-input-error for="seo_meta_title" :messages="$errors->first('seo_meta_title')" />
                                                </div>
                                                <div>
                                                    <x-label for="seo_meta_description" :value="__('Seo Meta Description')" />
                                                    <x-input wire:model="form.seo_meta_description" type="text"
                                                        id="seo_meta_description" name="seo_meta_description" />
                                                    <x-input-error for="seo_meta_description" :messages="$errors->first('seo_meta_description')" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-8">
                                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('Social Media Links') }}</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <x-label for="social_facebook" :value="__('Facebook Link')" />
                                                    <x-input wire:model="form.social_facebook" type="text" id="social_facebook"
                                                        name="social_facebook" />
                                                    <x-input-error for="social_facebook" :messages="$errors->first('social_facebook')" />
                                                </div>
                                                <div>
                                                    <x-label for="social_twitter" :value="__('Twitter Link')" />
                                                    <x-input wire:model="form.social_twitter" type="text" id="social_twitter"
                                                        name="social_twitter" />
                                                    <x-input-error for="social_twitter" :messages="$errors->first('social_twitter')" />
                                                </div>
                                                <div>
                                                    <x-label for="social_instagram" :value="__('Instagram Link')" />
                                                    <x-input wire:model="form.social_instagram" type="text" id="social_instagram"
                                                        name="social_instagram" />
                                                    <x-input-error for="social_instagram" :messages="$errors->first('social_instagram')" />
                                                </div>
                                                <div>
                                                    <x-label for="social_linkedin" :value="__('Linkedin Link')" />
                                                    <x-input wire:model="form.social_linkedin" type="text" id="social_linkedin"
                                                        name="social_linkedin" />
                                                    <x-input-error for="social_linkedin" :messages="$errors->first('social_linkedin')" />
                                                </div>
                                                <div>
                                                    <x-label for="social_whatsapp" :value="__('Whatsapp number')" />
                                                    <x-input wire:model="form.social_whatsapp" type="text" id="social_whatsapp"
                                                        name="social_whatsapp" />
                                                    <x-input-error for="social_whatsapp" :messages="$errors->first('social_whatsapp')" />
                                                    <small
                                                        class="text-red-500">{{ __("Use this number format 1XXXXXXXXXX Don't use this +001-(XXX)XXXXXXX") }}</small>
                                                </div>
                                                <div>
                                                    <x-label for="social_tiktok" :value="__('Tiktok Link')" />
                                                    <x-input wire:model="form.social_tiktok" type="text" id="social_tiktok"
                                                        name="social_tiktok" />
                                                    <x-input-error for="social_tiktok" :messages="$errors->first('social_tiktok')" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-8">
                                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('WhatsApp Configuration') }}
                                            </h3>
                                            <div>
                                                <x-label for="whatsapp_custom_message" :value="__('Whatsapp Custom Message')" />
                                                <x-input.textarea rows="4" id="whatsapp_custom_message"
                                                    name="whatsapp_custom_message" wire:model="form.whatsapp_custom_message" />
                                            </div>
                                        </div>

                                        <div class="mb-8">
                                            <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('Custom Code') }}</h3>
                                            <div class="space-y-6">
                                                <div>
                                                    <x-label for="head_tags" :value="__('Custom Head Code')" />
                                                    <x-input.textarea rows="4" id="head_tags" name="head_tags"
                                                        wire:model="form.head_tags" />
                                                    <small
                                                        class="text-red-500">{{ __('Facebook, Google Analytics or other script.') }}</small>
                                                </div>
                                                <div>
                                                    <x-label for="body_tags" :value="__('Custom Body Code')" />
                                                    <x-input.textarea rows="4" id="body_tags" name="body_tags"
                                                        wire:model="form.body_tags" />
                                                    <small
                                                        class="text-red-500">{{ __('Facebook, Google Analytics or other script.') }}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6 pt-6 border-t border-gray-200">
                                            <x-button type="submit" wire:click="update" primary class="w-full">
                                                {{ __('Save Changes') }}
                                            </x-button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="subtab === 'analyticsConfig'">
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-linear-to-r from-green-600 to-teal-600 px-6 py-4 border-b border-gray-200">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-chart-bar text-white text-lg"></i>
                                            <h2 class="text-lg font-semibold text-white">{{ __('Analytics Configuration') }}</h2>
                                        </div>
                                        <p class="text-sm text-teal-100 mt-2">{{ __('Manage analytics dashboard cards and tracking visibility.') }}</p>
                                    </div>
                                    <div class="p-6">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            {{ __('Name') }}
                                                        </th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            {{ __('Status') }}
                                                        </th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            {{ __('Color') }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @php
                                                    $groupedControls = collect($analyticsControl)->groupBy(function ($item) {
                                                    return $item['location'] ?? 'Dashboard';
                                                    }, true);
                                                    @endphp
                                                    @foreach ($groupedControls as $location => $controls)
                                                    <tr>
                                                        <td colspan="3" class="px-6 py-3 bg-gray-100 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                            {{ $location }}
                                                        </td>
                                                    </tr>
                                                    @foreach ($controls as $index => $control)
                                                    <tr>
                                                        <td
                                                            class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900">
                                                            <div>{{ $control['name'] }}</div>
                                                            @if(!empty($control['description']))
                                                            <div class="text-xs text-gray-500 mt-1 font-normal">{{ $control['description'] }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <button type="button" wire:click="toggleStatus('{{ $index }}')"
                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $control['status'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ $control['status'] ? __('Active') : __('Inactive') }}
                                                            </button>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <x-select wire:model="analyticsControl.{{ $index }}.color"
                                                                wire:change="changeColor('{{ $index }}', $event.target.value)"
                                                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                                                                @foreach ($colors as $color)
                                                                <option value="{{ $color }}"
                                                                    @if ($control['color']===$color) selected @endif>
                                                                    {{ ucfirst($color) }}
                                                                </option>
                                                                @endforeach
                                                            </x-select>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="subtab === 'appearance'">
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="bg-linear-to-r from-teal-500 to-cyan-600 px-6 py-4 border-b border-gray-200">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-paint-brush text-white text-lg"></i>
                                            <h2 class="text-lg font-semibold text-white">{{ __('Appearance') }}</h2>
                                        </div>
                                        <p class="text-sm text-teal-100 mt-2">{{ __('Customize the visual style and layout of your application.') }}</p>
                                    </div>
                                    <div class="p-6">
                                        <livewire:settings.app-customizer />
                                    </div>
                                </div>
                            </div>
                            <!-- Unified Notification Hub -->
                            <div x-show="subtab === 'channels'" class="space-y-8 p-6">
                                <div>
                                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('SMTP Configuration') }}</h2>
                                    <livewire:settings.smtp />
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Messaging (WhatsApp/Telegram)') }}</h2>
                                    <livewire:settings.messaging />
                                </div>
                            </div>
                            
                            <div x-show="subtab === 'triggers'" class="p-6">
                                <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Automated Event Triggers') }}</h2>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ __('Sale Created') }}</h4>
                                            <p class="text-sm text-gray-500">{{ __('Send a notification when a new sale is completed.') }}</p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" wire:model.live="form.notification_triggers.sale_created" value="mail" class="sr-only peer">
                                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Email') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ __('Payment Received') }}</h4>
                                            <p class="text-sm text-gray-500">{{ __('Send a notification when a payment is received.') }}</p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" wire:model.live="form.notification_triggers.payment_received" value="mail" class="sr-only peer">
                                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Email') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right Info Panel -->
                    <div class="lg:col-span-3">
                        <div class="sticky top-24 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg shadow-sm border border-indigo-100 dark:border-indigo-800 p-6">
                            <div class="flex items-center space-x-2 mb-4 text-indigo-700 dark:text-indigo-300">
                                <i class="fas fa-info-circle text-lg"></i>
                                <h3 class="text-lg font-bold" x-text="infoTitle"></h3>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4" x-text="infoDesc"></p>
                            <div x-show="infoPrivacy" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">
                                <i class="fas fa-shield-alt mr-1.5"></i>
                                <span x-text="infoPrivacy"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-page-container>
</div>