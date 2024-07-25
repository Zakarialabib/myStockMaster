@section('title', __('Edit Sale Return'))

@section('breadcrumb')
    <section class="py-3 px-4">
        <div class="flex flex-wrap items-center rtl:justify-start justify-between ">
            <div class="mb-5 lg:mb-0">
                <h2 class="mb-1 text-2xl font-bold">
                    {{ __('Edit Sale Return') }}
                </h2>
                <div class="flex items-center">
                    <a class="flex items-center text-sm text-gray-500" href="{{ route('home') }}">
                        <span class="inline-block mx-2">
                            <svg class="h-4 w-4 text-gray-500" viewBox="0 0 16 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.6666 5.66667L9.66662 1.28333C9.20827 0.873372 8.6149 0.646725 7.99996 0.646725C7.38501 0.646725 6.79164 0.873372 6.33329 1.28333L1.33329 5.66667C1.0686 5.9034 0.857374 6.1938 0.713683 6.51854C0.569993 6.84328 0.497134 7.1949 0.499957 7.55V14.8333C0.499957 15.4964 0.763349 16.1323 1.23219 16.6011C1.70103 17.0699 2.33692 17.3333 2.99996 17.3333H13C13.663 17.3333 14.2989 17.0699 14.7677 16.6011C15.2366 16.1323 15.5 15.4964 15.5 14.8333V7.54167C15.5016 7.18797 15.4282 6.83795 15.2845 6.51474C15.1409 6.19152 14.9303 5.90246 14.6666 5.66667V5.66667ZM9.66662 15.6667H6.33329V11.5C6.33329 11.279 6.42109 11.067 6.57737 10.9107C6.73365 10.7545 6.94561 10.6667 7.16662 10.6667H8.83329C9.0543 10.6667 9.26626 10.7545 9.42255 10.9107C9.57883 11.067 9.66662 11.279 9.66662 11.5V15.6667ZM13.8333 14.8333C13.8333 15.0543 13.7455 15.2663 13.5892 15.4226C13.4329 15.5789 13.221 15.6667 13 15.6667H11.3333V11.5C11.3333 10.837 11.0699 10.2011 10.6011 9.73223C10.1322 9.26339 9.49633 9 8.83329 9H7.16662C6.50358 9 5.8677 9.26339 5.39886 9.73223C4.93002 10.2011 4.66662 10.837 4.66662 11.5V15.6667H2.99996C2.77894 15.6667 2.56698 15.5789 2.4107 15.4226C2.25442 15.2663 2.16662 15.0543 2.16662 14.8333V7.54167C2.16677 7.42335 2.19212 7.30641 2.24097 7.19865C2.28982 7.09089 2.36107 6.99476 2.44996 6.91667L7.44996 2.54167C7.60203 2.40807 7.79753 2.33439 7.99996 2.33439C8.20238 2.33439 8.39788 2.40807 8.54996 2.54167L13.55 6.91667C13.6388 6.99476 13.7101 7.09089 13.7589 7.19865C13.8078 7.30641 13.8331 7.42335 13.8333 7.54167V14.8333Z"
                                    fill="currentColor"></path>
                            </svg></span>
                        <span>{{ __('Home') }}</span>
                    </a>
                    <span class="inline-block mx-4">
                        <svg class="h-2 w-2 text-gray-500" viewBox="0 0 6 10" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1.23242 9.3689C1.06762 9.36887 0.906542 9.31997 0.769534 9.2284C0.632526 9.13683 0.525742 9.0067 0.462684 8.85445C0.399625 8.7022 0.383124 8.53467 0.415263 8.37304C0.447403 8.21141 0.526741 8.06294 0.643249 7.9464L3.58916 5L0.643224 2.05364C0.486959 1.89737 0.399171 1.68543 0.39917 1.46444C0.399169 1.24345 0.486957 1.03151 0.64322 0.875249C0.799483 0.718985 1.01142 0.631196 1.23241 0.631195C1.4534 0.631194 1.66534 0.718982 1.82161 0.875245L5.35676 4.41084C5.43416 4.48819 5.49556 4.58005 5.53745 4.68114C5.57934 4.78224 5.6009 4.8906 5.6009 5.00003C5.6009 5.10946 5.57934 5.21782 5.53745 5.31891C5.49556 5.42001 5.43416 5.51186 5.35676 5.58922L1.82161 9.12478C1.74432 9.20229 1.65249 9.26375 1.55137 9.30564C1.45026 9.34754 1.34186 9.36903 1.23242 9.3689Z"
                                fill="currentColor"></path>
                        </svg></span>
                    <a class="flex items-center text-sm" href="{{ URL::current() }}">
                        <span class="inline-block mx-2">
                            <svg class="h-4 w-4 text-indigo-500" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.99992 10.8333H1.66659C1.44557 10.8333 1.23361 10.9211 1.07733 11.0774C0.921049 11.2337 0.833252 11.4457 0.833252 11.6667V18.3333C0.833252 18.5544 0.921049 18.7663 1.07733 18.9226C1.23361 19.0789 1.44557 19.1667 1.66659 19.1667H4.99992C5.22093 19.1667 5.43289 19.0789 5.58917 18.9226C5.74545 18.7663 5.83325 18.5544 5.83325 18.3333V11.6667C5.83325 11.4457 5.74545 11.2337 5.58917 11.0774C5.43289 10.9211 5.22093 10.8333 4.99992 10.8333ZM4.16658 17.5H2.49992V12.5H4.16658V17.5ZM18.3333 7.50001H14.9999C14.7789 7.50001 14.5669 7.5878 14.4107 7.74408C14.2544 7.90036 14.1666 8.11233 14.1666 8.33334V18.3333C14.1666 18.5544 14.2544 18.7663 14.4107 18.9226C14.5669 19.0789 14.7789 19.1667 14.9999 19.1667H18.3333C18.5543 19.1667 18.7662 19.0789 18.9225 18.9226C19.0788 18.7663 19.1666 18.5544 19.1666 18.3333V8.33334C19.1666 8.11233 19.0788 7.90036 18.9225 7.74408C18.7662 7.5878 18.5543 7.50001 18.3333 7.50001ZM17.4999 17.5H15.8333V9.16667H17.4999V17.5ZM11.6666 0.83334H8.33325C8.11224 0.83334 7.90028 0.921137 7.744 1.07742C7.58772 1.2337 7.49992 1.44566 7.49992 1.66667V18.3333C7.49992 18.5544 7.58772 18.7663 7.744 18.9226C7.90028 19.0789 8.11224 19.1667 8.33325 19.1667H11.6666C11.8876 19.1667 12.0996 19.0789 12.2558 18.9226C12.4121 18.7663 12.4999 18.5544 12.4999 18.3333V1.66667C12.4999 1.44566 12.4121 1.2337 12.2558 1.07742C12.0996 0.921137 11.8876 0.83334 11.6666 0.83334ZM10.8333 17.5H9.16658V2.50001H10.8333V17.5Z"
                                    fill="currentColor"></path>
                            </svg></span>
                        <span>{{ __('Edit Sale Return') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

<x-app-layout>
    <x-card>
        <div class="px-4 mx-auto mb-4">
            <div class="flex flex-row">
                <div class="w-full px-4">

                    <livewire:search-product />
                </div>
            </div>

            <div class="flex flex-row mt-4">
                <div class="w-full px-4">
                    <div class="card">
                        <div class="p-4">
                            @include('utils.alerts')
                            <form id="sale-return-form" action="{{ route('sale-returns.update', $sale_return) }}"
                                method="POST">
                                @csrf
                                @method('patch')
                                <div class="flex flex-wrap -mx-2 mb-3">
                                    <div class="w-full md:w-1/3 px-2 mb-2">
                                        <div class="mb-4">
                                            <label for="reference">{{ __('Reference') }} <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text"
                                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                                name="reference" required value="{{ $sale_return->reference }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/3 px-2 mb-2">
                                        <div class="from-group">
                                            <div class="mb-4">
                                                <label for="customer_id">Customer <span
                                                        class="text-red-500">*</span></label>
                                                <select
                                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                                    name="customer_id" id="customer_id" required>
                                                    @foreach (\App\Models\Customer::all() as $customer)
                                                        <option
                                                            {{ $sale_return->customer_id == $customer->id ? 'selected' : '' }}
                                                            value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/3 px-2 mb-2">
                                        <div class="from-group">
                                            <div class="mb-4">
                                                <label for="date">{{ __('Date') }} <span
                                                        class="text-red-500">*</span></label>
                                                <input type="date"
                                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                                    name="date" required value="{{ $sale_return->date }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <livewire:product-cart :cartInstance="'sale_return'" :data="$sale_return" />

                                <div class="flex flex-wrap -mx-2 mb-3">
                                    <div class="w-full md:w-1/3 px-2 mb-2">
                                        <div class="mb-4">
                                            <label for="status">{{ __('Status') }} <span
                                                    class="text-red-500">*</span></label>
                                            <select
                                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                                name="status" id="status" required>
                                                @foreach (\App\Enums\SaleReturnStatus::cases() as $status)
                                                    <option
                                                        {{ $sale_return->status == $status->value ? 'selected' : '' }}
                                                        value="{{ $status->value }}">
                                                        {{ __($status->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/3 px-2 mb-2">
                                        <div class="from-group">
                                            <div class="mb-4">
                                                <label for="payment_method">{{ __('Payment Method') }} <span
                                                        class="text-red-500">*</span></label>
                                                <input type="text"
                                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                                    name="payment_method" required
                                                    value="{{ $sale_return->payment_method }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/3 px-2 mb-2">
                                        <div class="mb-4">
                                            <label for="paid_amount">Amount Paid <span
                                                    class="text-red-500">*</span></label>
                                            <input id="paid_amount" type="text"
                                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                                name="paid_amount" required value="{{ $sale_return->paid_amount }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="note">Note (If Needed)</label>
                                    <textarea name="note" id="note" rows="5"
                                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">{{ $sale_return->note }}</textarea>
                                </div>

                                <div class="mt-3">
                                    <button type="submit"
                                        class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">
                                        Update Sale Return <i class="bi bi-check"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</x-app-layout>
