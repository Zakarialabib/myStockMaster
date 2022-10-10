@extends('layouts.app')

@section('title', 'Edit Settings')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">Settings</li>
    </ol>
@endsection

@section('content')
    <div class="px-4 mx-auto">
        <div class="row">
            <div class="w-full px-4">
                @include('utils.alerts')
                <x-card>
                    <div class="py-3 px-6 mb-0 bg-gray-200 border-b-1 border-gray-light text-gray-500">
                        <h5 class="mb-0">{{ __('General Settings') }}</h5>
                    </div>
                    <div class="p-4">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="flex flex-wrap -mx-1">
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="company_name">{{ __('Company Name') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="company_name" value="{{ $settings->company_name }}" required>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="company_email">{{ __('Company Email') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="company_email" value="{{ $settings->company_email }}" required>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="company_phone">{{ __('Company Phone') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="company_phone" value="{{ $settings->company_phone }}" required>
                                    </div>
                                </div>

                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="default_currency_id">{{ __('Default Currency') }} <span
                                                class="text-red-500">*</span></label>
                                        <select name="default_currency_id" id="default_currency_id"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            required>
                                            @foreach (\App\Models\Currency::all() as $currency)
                                                <option
                                                    {{ $settings->default_currency_id == $currency->id ? 'selected' : '' }}
                                                    value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="default_currency_position">{{ __('Default Currency Position') }} <span
                                                class="text-red-500">*</span></label>
                                        <select name="default_currency_position" id="default_currency_position"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            required>
                                            <option
                                                {{ $settings->default_currency_position == 'prefix' ? 'selected' : '' }}
                                                value="prefix">Prefix</option>
                                            <option
                                                {{ $settings->default_currency_position == 'suffix' ? 'selected' : '' }}
                                                value="suffix">Suffix</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="notification_email">{{ __('Notification Email') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="notification_email" value="{{ $settings->notification_email }}" required>
                                    </div>
                                </div>

                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="company_address">{{ __('Company Address') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="company_address" value="{{ $settings->company_address }}">
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="footer_text">{{ __('Footer Text') }} <span
                                                class="text-red-500">*</span></label>
                                        <textarea rows="1" name="footer_text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded">{!! $settings->footer_text !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 md:mb-0">
                                <button type="submit"
                                    class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"><i
                                        class="bi bi-check"></i> {{ __('Save Changes') }}</button>
                            </div>
                        </form>
                    </div>
                </x-card>
            </div>

            <div class="w-full px-4">
                @if (session()->has('settings_smtp_message'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="alert-body">
                            <span>{{ session('settings_smtp_message') }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                @endif
                <x-card>
                    <div class="py-3 px-6 mb-0 bg-gray-200 border-b-1 border-gray-light text-gray-500">
                        <h5 class="mb-0">{{ __('Mail Settings') }}</h5>
                    </div>
                    <div class="p-4">
                        <form action="{{ route('settings.smtp.update') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="flex flex-wrap -mx-1">
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="mail_mailer">{{ __('MAIL MAILER') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="mail_mailer" value="{{ env('MAIL_MAILER') }}" required>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="mail_host">{{ __('MAIL HOST') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="mail_host" value="{{ env('MAIL_HOST') }}" required>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="mail_port">{{ __('MAIL PORT') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="number"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="mail_port" value="{{ env('MAIL_PORT') }}" required>
                                    </div>
                                </div>

                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="mail_mailer">{{ __('MAIL MAILER') }}</label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="mail_mailer" value="{{ env('MAIL_MAILER') }}">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="mail_username">{{ __('MAIL USERNAME') }}</label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="mail_username" value="{{ env('MAIL_USERNAME') }}">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="mail_password">{{ __('MAIL PASSWORD') }}</label>
                                        <input type="password"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="mail_password" value="{{ env('MAIL_PASSWORD') }}">
                                    </div>
                                </div>

                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="mail_encryption">{{ __('MAIL ENCRYPTION') }}</label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="mail_encryption" value="{{ env('MAIL_ENCRYPTION') }}">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="mail_from_address">{{ __('MAIL FROM ADDRESS') }}</label>
                                        <input type="email"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="mail_from_address" value="{{ env('MAIL_FROM_ADDRESS') }}">
                                    </div>
                                </div>
                                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                                    <div class="mb-4">
                                        <label for="mail_from_name">{{ __('MAIL FROM NAME') }} <span
                                                class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
                                            name="mail_from_name" value="{{ env('MAIL_FROM_NAME') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 md:mb-0">
                                <button type="submit"
                                    class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"><i
                                        class="bi bi-check"></i> {{__('Save Changes')}}</button>
                            </div>
                        </form>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection
