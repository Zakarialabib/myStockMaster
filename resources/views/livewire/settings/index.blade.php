<div>
    @section('title', __('Settings'))

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

            <div x-data="{ tab: 'company' }" class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Tabs Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6">
                        <button type="button" @click="tab = 'company'"
                            :class="{ 'bg-indigo-600 text-white border-indigo-600': tab === 'company', 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700': tab !== 'company' }"
                            class="w-full px-4 py-3 text-left text-sm font-medium border-b border-gray-200 dark:border-gray-700 transition-colors duration-200 flex items-center space-x-2">
                            <i class="fas fa-building w-4 h-4"></i>
                            <span>{{ __('Company Info') }}</span>
                        </button>
                        <button type="button" @click="tab = 'system'"
                            :class="{ 'bg-indigo-600 text-white border-indigo-600': tab === 'system', 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700': tab !== 'system' }"
                            class="w-full px-4 py-3 text-left text-sm font-medium transition-colors duration-200 flex items-center space-x-2">
                            <i class="fas fa-cogs w-4 h-4"></i>
                            <span>{{ __('System Configuration') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Tabs Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <x-validation-errors class="mb-4" :errors="$errors" />
                        
                        <!-- Company Info Tab -->
                        <div x-show="tab === 'company'" class="p-6">
                            <div class="mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                                    <i class="fas fa-building text-indigo-600"></i>
                                    <span>{{ __('Company Info') }}</span>
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ __('Configure your company information') }}
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-label for="company_name" :value="__('Company Name')" required />
                                    <x-input type="text" wire:model="form.company_name" id="company_name" name="company_name" required class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('form.company_name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-label for="company_email" :value="__('Company Email')" required />
                                    <x-input type="email" wire:model="form.company_email" id="company_email" name="company_email" required class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('form.company_email')" class="mt-2" />
                                </div>
                                <div>
                                    <x-label for="company_phone" :value="__('Company Phone')" required />
                                    <x-input type="text" wire:model="form.company_phone" id="company_phone" name="company_phone" required class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('form.company_phone')" class="mt-2" />
                                </div>
                                <div>
                                    <x-label for="company_tax" :value="__('Company Tax')" />
                                    <x-input type="text" wire:model="form.company_tax" id="company_tax" name="company_tax" class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('form.company_tax')" class="mt-2" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-label for="company_address" :value="__('Company Address')" required />
                                    <x-input.textarea wire:model="form.company_address" id="company_address" name="company_address" rows="3" required class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('form.company_address')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- System Configuration Tab -->
                        <div x-show="tab === 'system'" class="p-6" style="display: none;">
                            <div class="mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                                    <i class="fas fa-cogs text-indigo-600"></i>
                                    <span>{{ __('System Configuration') }}</span>
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ __('Configure system defaults and preferences') }}
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-label for="default_currency_id" :value="__('Default Currency')" required />
                                    <x-select-list wire:model="form.default_currency_id" id="default_currency_id" name="default_currency_id" :options="$this->currencies" required class="mt-1 w-full block bg-white text-gray-700 rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    <x-input-error :messages="$errors->get('form.default_currency_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="default_currency_position" :value="__('Default Currency Position')" required />
                                    <select name="default_currency_position" id="default_currency_position" wire:model="form.default_currency_position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        <option value="prefix">{{ __('Left') }}</option>
                                        <option value="suffix">{{ __('Right') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('form.default_currency_position')" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="notification_email" :value="__('Notification Email')" />
                                    <x-input type="email" wire:model="form.notification_email" id="notification_email" name="notification_email" class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('form.notification_email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="is_ecommerce_active" :value="__('eCommerce Active')" />
                                    <div class="mt-2 flex items-center">
                                        <input type="checkbox" wire:model="form.is_ecommerce_active" id="is_ecommerce_active" name="is_ecommerce_active" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="is_ecommerce_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                            {{ __('Enable eCommerce features') }}
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('form.is_ecommerce_active')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-label for="footer_text" :value="__('Footer Text')" />
                                    <x-input type="text" wire:model="form.footer_text" id="footer_text" name="footer_text" class="mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('form.footer_text')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </x-page-container>
</div>