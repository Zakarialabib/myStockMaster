@extends('layouts.app')

@section('title', 'Edit Expense Category')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
        <li class="breadcrumb-item"><a href="{{ route('expense-categories.index') }}">Categories</a></li>
        <li class="breadcrumb-item active">{{__('Edit')}}</li>
    </ol>
@endsection

@section('content')
    <div class="container px-4 mx-auto">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @include('utils.alerts')
                <div class="card">
                    <div class="p-4">
                        <form action="{{ route('expense-categories.update', $expenseCategory) }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="mb-4">
                                <label for="category_name">{{__('Category')}} Name <span class="text-red-500">*</span></label>
                                <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" type="text" name="category_name" required value="{{ $expenseCategory->category_name }}">
                            </div>
                            <div class="mb-4">
                                <label for="category_description">Description</label>
                                <textarea class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="category_description" id="category_description" rows="5">{{ $expenseCategory->category_description }}</textarea>
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">Update <i class="bi bi-check"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

