@extends('layouts.app')

@section('title', 'Create Expense')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
        <li class="breadcrumb-item active">{{__('Edit')}}</li>
    </ol>
@endsection

@section('content')
    <div class="container px-4 mx-auto">
        <form id="expense-form" action="{{ route('expenses.update', $expense) }}" method="POST">
            @csrf
            @method('patch')
            <div class="row">
                <div class="w-full px-4">
                    @include('utils.alerts')
                    <div class="mb-4">
                        <button class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">Update Expense <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="w-full px-4">
                    <div class="card">
                        <div class="p-4">
                            <div class="flex flex-wrap -mx-1">
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="reference">Reference <span class="text-red-500">*</span></label>
                                        <input type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="reference" required value="{{ $expense->reference }}" readonly>
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="date">Date <span class="text-red-500">*</span></label>
                                        <input type="date" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="date" required value="{{ $expense->getAttributes()['date'] }}">
                                    </div>
                                </div>
                            
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="category_id">{{__('Category')}} <span class="text-red-500">*</span></label>
                                        <select name="category_id" id="category_id" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" required>
                                            @foreach(\App\Models\ExpenseCategory::all() as $category)
                                                <option {{ $category->id == $expense->category_id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="amount">Amount <span class="text-red-500">*</span></label>
                                        <input id="amount" type="text" class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="amount" required value="{{ $expense->amount }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="details">{{__('Details')}}</label>
                                <textarea class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" rows="6" name="details">{{ $expense->details }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#amount').maskMoney({
                prefix:'{{ settings()->currency->symbol }}',
                thousands:'{{ settings()->currency->thousand_separator }}',
                decimal:'{{ settings()->currency->decimal_separator }}',
            });

            $('#amount').maskMoney('mask');

            $('#expense-form').submit(function () {
                var amount = $('#amount').maskMoney('unmasked')[0];
                $('#amount').val(amount);
            });
        });
    </script>
@endpush
