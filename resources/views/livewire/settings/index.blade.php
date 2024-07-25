<div>
    @section('title', __('Settings'))
    <x-theme.breadcrumb :title="__('Settings')" :parent="route('settings.index')" :parentName="__('Settings ')">

    </x-theme.breadcrumb>
    <div x-data="{ tab: 'company' }">
        <div class="flex mt-5">
            <div class="w-1/4">
                <div class="flex flex-col gap-y-2 border">
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
                    <button @click="tab = 'analyticsConfig'"
                        :class="{ 'bg-indigo-500 text-white': tab === 'messaging' }"
                        class="px-4 py-2 w-full text-left hover:bg-indigo-500 hover:text-white transition-colors">
                        {{ __('Analytics Configuration') }}
                    </button>
                    <button @click="tab = 'siteConfig'" :class="{ 'bg-indigo-500 text-white': tab === 'siteConfig' }"
                        class="px-4 py-2 w-full text-left hover:bg-indigo-500 hover:text-white transition-colors">
                        {{ __('Site configuration') }}
                    </button>
                </div>
            </div>
            <div class="w-3/4 px-4">
                <x-validation-errors class="mb-4" :errors="$errors" />
                <form wire:submit="update">
                    <div x-show="tab === 'company'">
                        <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light text-white">
                            <h2 class="text-white">{{ __('Company Info') }}</h2>
                        </div>
                        <div class="flex flex-wrap mb-3">
                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="company_name" :value="__('Company Name')" required />
                                <x-input type="text" wire:model="company_name" id="company_name" name="company_name"
                                    required />
                                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                            </div>
                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="company_email" :value="__('Company Email')" required />
                                <x-input type="email" wire:model="company_email" id="company_email"
                                    name="company_email" required />
                                <x-input-error :messages="$errors->get('company_email')" class="mt-2" />
                            </div>
                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="company_phone" :value="__('Company Phone')" required />
                                <x-input type="text" wire:model="company_phone" id="company_phone"
                                    name="company_phone" required />
                                <x-input-error :messages="$errors->get('company_phone')" class="mt-2" />
                            </div>

                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="company_address" :value="__('Company Address')" required />
                                <x-input type="text" wire:model="company_address" id="company_address"
                                    name="company_address" />
                                <x-input-error :messages="$errors->get('company_address')" class="mt-2" />
                            </div>

                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="company_tax" :value="__('Company Tax')" />
                                <x-input type="text" wire:model="company_tax" id="company_tax" name="company_tax" />
                                <x-input-error :messages="$errors->get('company_tax')" class="mt-2" />
                            </div>

                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="telegram_channel" :value="__('Telegram Channel')" />
                                <x-input type="text" wire:model="telegram_channel" id="telegram_channel"
                                    name="telegram_channel" />
                                <x-input-error :messages="$errors->get('telegram_channel')" class="mt-2" />
                            </div>

                            <div class="w-full px-2">
                                <x-label for="site_logo" :value="__('Logo')" />
                                <x-media-upload title="{{ __('Logo') }}" name="site_logo" :file="$site_logo"
                                    path="images/" single types="PNG / JPEG / WEBP" fileTypes="image/*" />

                                <x-input-error :messages="$errors->get('site_logo')" for="site_logo" class="mt-2" />
                            </div>
                            <div class="w-full px-2">
                                <x-label for="site_favicon" :value="__('Favicon')" />

                                <x-media-upload title="{{ __('Logo') }}" name="site_favicon" :file="$site_favicon"
                                    path="images/" single types="PNG / JPEG / WEBP" fileTypes="image/*" />

                                <x-input-error :messages="$errors->get('site_favicon')" for="site_favicon" class="mt-2" />
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
                            <h2 class="text-white">{{ __('System Configuration') }}</h2>
                        </div>
                        <div class="flex flex-wrap mb-3">
                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="default_currency_id" :value="__('Default currency')" required />
                                <x-select-list
                                    class="block bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                    id="default_currency_id" name="default_currency_id"
                                    wire:model="default_currency_id" :options="$this->currencies" required />
                            </div>

                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="default_currency_position" :value="__('Default currency position')" required />
                                <select name="default_currency_position" id="default_currency_position"
                                    wire:model="default_currency_position"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                    required>
                                    <option {{ settings('default_currency_position') == 'prefix' ? 'selected' : '' }}
                                        value="prefix">
                                        {{ __('Left') }}</option>
                                    <option {{ settings('default_currency_position') == 'suffix' ? 'selected' : '' }}
                                        value="suffix">
                                        {{ __('Right') }}</option>
                                </select>
                            </div>

                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="default_date_format" :value="__('Default date format')" required />
                                <select name="default_date_format" wire:model="default_date_format"
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
                                <x-select-list wire:model="default_client_id" id="default_client_id"
                                    name="default_client_id" :options="$this->customers" />
                            </div>

                            <div class="w-full md:w-1/3 px-3 mb-4">
                                <x-label for="default_warehouse_id" :value="__('Default Warehouse')" />
                                <x-select-list wire:model.live="default_warehouse_id" id="default_warehouse_id"
                                    name="default_warehouse_id" :options="$this->warehouses" />
                            </div>
                            <div class="w-full flex justify-center p-4 space-x-4">
                                @foreach ($invoice_control as $index => $control)
                                    <div>
                                        <x-label :for="$control['name']" :value="__($control['name'])" required />
                                        <input type="checkbox" id="{{ $control['name'] }}"
                                            wire:model="invoice_control.{{ $index }}.status">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-4 w-full">
                            <x-button type="submit" wire:click="update" primary class="w-full text-center">
                                {{ __('Save Changes') }}
                            </x-button>
                        </div>
                    </div>
                    <div x-show="tab === 'invoice'">
                        <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light ">
                            <h2 class="text-white">{{ __('Invoice Configuration') }}</h2>
                        </div>

                        <x-theme.accordion :title="__('Select invoice template')">
                            <div class="flex items-center">
                                <div class="w-full md:w-1/2 px-3 mb-4">
                                    <x-label for="invoice_template" :value="__('Invoice Template')" />
                                    <select wire:model.live="invoice_template" id="invoice_template"
                                        name="invoice_template"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="invoice-{{ $i }}">Invoice {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="w-full md:w-1/2 px-3 mb-4">
                                    <img src="{{ asset('assets/screens/' . $invoice_template . '.png') }}"
                                        alt="Invoice Preview" class="w-full h-auto">
                                </div>
                            </div>
                        </x-theme.accordion>
                        <div class="grid md:grid-cols-2 sm:grid-cols-2 mb-3 gap-6">
                            {{-- <div class=mb-4">
                                <x-label for="invoice_header" :value="__('Invoice Header')" />
                                <x-fileupload wire:model="invoice_header" accept="image/jpg,image/jpeg,image/png" />
                            </div>
                            <div class=mb-4">
                                <x-label for="invoice_footer" :value="__('Invoice Footer')" />
                                <x-fileupload wire:model="invoice_footer" accept="image/jpg,image/jpeg,image/png" />
                            </div> --}}
                            <div class="col-span-full mb-4">
                                <x-label for="invoice_footer_text" :value="__('Invoice footer text')" />
                                <x-input type="text" wire:model="invoice_footer_text" id="invoice_footer_text"
                                    name="invoice_footer_text" />
                                <x-input-error :messages="$errors->get('invoice_footer_text')" class="mt-2" />
                            </div>
                        </div>
                        <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6">
                            <div>
                                <x-label for="sale_prefix" :value="__('Sale Prefix')" />
                                <input wire:model="sale_prefix" type="text" id="sale_prefix" name="sale_prefix"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                            </div>
                            <div>
                                <x-label for="saleReturn_prefix" :value="__('Sale Prefix')" />
                                <input wire:model="saleReturn_prefix" type="text" id="saleReturn_prefix"
                                    name="saleReturn_prefix"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                            </div>
                            <div>
                                <x-label for="salePayment_prefix" :value="__('Sale Payment Prefix')" />
                                <input wire:model="salePayment_prefix" type="text" id="salePayment_prefix"
                                    name="salePayment_prefix"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                            </div>
                            <div>
                                <x-label for="purchase_prefix" :value="__('Purchase Prefix')" />
                                <input wire:model="purchase_prefix" type="text" id="purchase_prefix"
                                    name="purchase_prefix"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                            </div>
                            <div>
                                <x-label for="purchaseReturn_prefix" :value="__('Purchase Prefix')" />
                                <input wire:model="purchaseReturn_prefix" type="text" id="purchaseReturn_prefix"
                                    name="purchaseReturn_prefix"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                            </div>
                            <div>
                                <x-label for="purchasePayment_prefix" :value="__('Purchase Payment Prefix')" />
                                <input wire:model="purchasePayment_prefix" type="text" id="purchasePayment_prefix"
                                    name="purchasePayment_prefix"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                            </div>
                            <div>
                                <x-label for="quotation_prefix" :value="__('Quotation Prefix')" />
                                <input wire:model="quotation_prefix" type="text" id="quotation_prefix"
                                    name="quotation_prefix"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                            </div>
                            <div>
                                <x-label for="expense_prefix" :value="__('Expense Prefix')" />
                                <input wire:model="expense_prefix" type="text" id="expense_prefix"
                                    name="expense_prefix"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                            </div>
                            <div>
                                <x-label for="delivery_prefix" :value="__('Delivery Prefix')" />
                                <input wire:model="delivery_prefix" type="text" id="delivery_prefix"
                                    name="delivery_prefix"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" />
                            </div>
                        </div>
                        <div class="my-4 w-full">
                            <x-button type="submit" wire:click="update" primary class="w-full text-center">
                                {{ __('Save Changes') }}
                            </x-button>
                        </div>
                    </div>
                    <div x-show="tab === 'siteConfig'">
                        <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light text-white">
                            <h2 class="text-white">{{ __('Site Configuration') }}</h2>
                        </div>
                        <div class="w-full flex flex-wrap">
                            <div class="lg:w-1/2 sm:w-full px-2">
                                <x-label for="site_title" :value="__('Website title')" />
                                <x-input type="text" wire:model="site_title" id="site_title" />
                                <x-input-error for="site_title" :messages="$errors->first('site_title')" />
                            </div>
                            <div class="lg:w-1/2 sm:w-full px-2">
                                <x-label for="seo_meta_title" :value="__('Seo Meta Title')" />
                                <x-input wire:model="seo_meta_title" type="text" id="seo_meta_title"
                                    name="seo_meta_title" />
                                <x-input-error for="seo_meta_title" :messages="$errors->first('seo_meta_title')" />
                            </div>
                            <div class="w-full mb-4 px-2">
                                <x-label for="seo_meta_description" :value="__('Seo Meta Description')" />
                                <x-input wire:model="seo_meta_description" type="text" id="seo_meta_description"
                                    name="seo_meta_description" />
                                <x-input-error for="seo_meta_description" :messages="$errors->first('seo_meta_description')" />
                            </div>
                        </div>
                        <div class="w-full flex flex-wrap">
                            <div class="lg:w-1/2 sm:w-full px-2">
                                <x-label for="social_facebook" :value="__('Facebook Link')" />
                                <x-input wire:model="social_facebook" type="text" id="social_facebook"
                                    name="social_facebook" />
                                <x-input-error for="social_facebook" :messages="$errors->first('social_facebook')" />
                            </div>
                            <div class="lg:w-1/2 sm:w-full px-2">
                                <x-label for="social_twitter" :value="__('Twitter Link')" />
                                <x-input wire:model="social_twitter" type="text" id="social_twitter"
                                    name="social_twitter" />
                                <x-input-error for="social_twitter" :messages="$errors->first('social_twitter')" />
                            </div>
                            <div class="lg:w-1/2 sm:w-full px-2">
                                <x-label for="social_instagram" :value="__('Instagram Link')" />
                                <x-input wire:model="social_instagram" type="text" id="social_instagram"
                                    name="social_instagram" />
                                <x-input-error for="social_instagram" :messages="$errors->first('social_instagram')" />
                            </div>
                            <div class="lg:w-1/2 sm:w-full px-2">
                                <x-label for="social_linkedin" :value="__('Linkedin Link')" />
                                <x-input wire:model="social_linkedin" type="text" id="social_linkedin"
                                    name="social_linkedin" />
                                <x-input-error for="social_linkedin" :messages="$errors->first('social_linkedin')" />
                            </div>
                            <div class="lg:w-1/2 sm:w-full px-2">
                                <x-label for="social_whatsapp" :value="__('Whatsapp number')" />
                                <x-input wire:model="social_whatsapp" type="text" id="social_whatsapp"
                                    name="social_whatsapp" />
                                <x-input-error for="social_whatsapp" :messages="$errors->first('social_whatsapp')" />
                                <small
                                    class="text-red-500">{{ __("Use this number format 1XXXXXXXXXX Don't use this +001-(XXX)XXXXXXX") }}</small>
                            </div>
                            <div class="lg:w-1/2 sm:w-full px-2">
                                <x-label for="social_tiktok" :value="__('Tiktok Link')" />
                                <x-input wire:model="social_tiktok" type="text" id="social_tiktok"
                                    name="social_tiktok" />
                                <x-input-error for="social_tiktok" :messages="$errors->first('social_tiktok')" />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="mb-4 px-2">
                                <x-label for="whatsapp_custom_message" :value="__('Whatsapp Custom Message')" />
                                <textarea
                                    class="p-3 leading-5 bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                    rows="4" id="whatsapp_custom_message" name="whatsapp_custom_message" wire:model="whatsapp_custom_message"></textarea>
                            </div>

                            <div class="mb-4 px-2">
                                <x-label for="head_tags" :value="__('Custom Head Code')" />
                                <textarea
                                    class="p-3 leading-5 bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                    rows="4" id="head_tags" name="head_tags" wire:model="head_tags"></textarea>
                                <small
                                    class="text-red-500">{{ __('Facebook, Google Analytics or other script.') }}</small>
                            </div>
                            <div class="mb-4 px-2">
                                <x-label for="body_tags" :value="__('Custom Body Code')" />
                                <textarea
                                    class="p-3 leading-5 bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                                    rows="4" id="body_tags" name="body_tags" wire:model="body_tags"></textarea>
                                <small
                                    class="text-red-500">{{ __('Facebook, Google Analytics or other script.') }}</small>
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
                        <h2 class="text-white">{{ __('Mail Configuration') }}</h2>
                    </div>
                    <div class="w-full px-2">
                        <livewire:settings.smtp lazy />
                    </div>
                </div>
                <div x-show="tab === 'analyticsConfig'">
                    <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light text-white">
                        <h2 class="text-white">{{ __('Analytics Configuration') }}</h2>
                    </div>
                    <div class="w-full px-2">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Color</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (settings()->analytics_control as $index => $control)
                                    <tr>
                                        <td>{{ $control['name'] }}</td>
                                        <td>
                                            <button wire:click="toggleStatus({{ $index }})">
                                                {{ $control['status'] ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td>
                                            <select wire:model="analyticsControl.{{ $index }}.color"
                                                wire:change="changeColor({{ $index }}, $event.target.value)">
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color }}"
                                                        @if ($control['color'] === $color) selected @endif>
                                                        {{ ucfirst($color) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div x-show="tab === 'messaging'">
                    <div class="py-3 px-6 mb-2 bg-indigo-500 border-b-1 border-gray-light text-white">
                        <h2 class="text-white">{{ __('Messaging') }}</h2>
                    </div>
                    <div class="w-full px-2">
                        <livewire:settings.messaging lazy />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
