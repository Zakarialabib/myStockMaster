@extends('layouts.app')

@section('title', 'Create User')

@section('third_party_stylesheets')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
          rel="stylesheet">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
@endsection

@section('content')
    <div class="px-4 mx-auto mb-4">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="w-full px-4">
                    @include('utils.alerts')
                    <div class="mb-4">
                        <button class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded">Create User <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="p-4">
                            <div class="flex flex-wrap -mx-1">
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="name">Name <span class="text-red-500">*</span></label>
                                        <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" type="text" name="name" required>
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="email">{{__('Email')}} <span class="text-red-500">*</span></label>
                                        <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" type="email" name="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-1">
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="password">Password <span class="text-red-500">*</span></label>
                                        <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" type="password" name="password" required>
                                    </div>
                                </div>
                                <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-4">
                                    <div class="mb-4">
                                        <label for="password_confirmation">Confirm Password <span
                                                class="text-danger">*</span></label>
                                        <input class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" type="password" name="password_confirmation"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="role">Role <span class="text-red-500">*</span></label>
                                <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="role" id="role" required>
                                    <option value="" selected disabled>Select Role</option>
                                    @foreach(\Spatie\Permission\Models\Role::where('name', '!=', 'Super Admin')->get() as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="is_active">Status <span class="text-red-500">*</span></label>
                                <select class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded" name="is_active" id="is_active" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Deactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-4 mb-4 md:mb-0">
                    <div class="card">
                        <div class="p-4">
                            <div class="mb-4">
                                <label for="image">Profile Image <span class="text-red-500">*</span></label>
                                <input id="image" type="file" name="image" data-max-file-size="500KB">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('third_party_scripts')
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
@endsection

@push('page_scripts')
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );
        const fileElement = document.querySelector('input[id="image"]');
        const pond = FilePond.create(fileElement, {
            acceptedFileTypes: ['image/png', 'image/jpg', 'image/jpeg'],
        });
        FilePond.setOptions({
            server: {
                url: "{{ route('filepond.upload') }}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            }
        });
    </script>
@endpush


