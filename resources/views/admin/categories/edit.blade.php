@extends('layouts.app')

@section('title', 'Edit Product Category')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{__('Products')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('product-categories.index') }}">Categories</a></li>
        <li class="breadcrumb-item active">{{__('Edit')}}</li>
    </ol>
@endsection

@section('content')
    <div class="container px-4 mx-auto">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @include('utils.alerts')
                <x-card>
                    <div class="p-4">
                        <form action="{{ route('product-categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="mb-4">
                                <label class="font-weight-bold" for="category_code">{{__('Category Code')}} <span class="text-red-500">*</span></label>
                                <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" type="text" name="category_code" required value="{{ $category->category_code }}">
                            </div>
                            <div class="mb-4">
                                <label class="font-weight-bold" for="category_name">{{__('Category Name')}} <span class="text-red-500">*</span></label>
                                <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" type="text" name="category_name" required value="{{ $category->category_name }}">
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">{{__('Update')}} <i class="bi bi-check"></i></button>
                            </div>
                        </form>
                    </div>
                <x-card>
            </div>
        </div>
    </div>
@endsection

