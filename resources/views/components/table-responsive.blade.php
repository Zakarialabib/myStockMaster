{{-- Todo : Responsive table component --}}

@props(['headers', 'rows'])

<div class="overflow-x-auto">
    <div class="min-w-screen flex items-center justify-center font-sans overflow-hidden">
        <div class="w-full lg:w-5/6">
            <div class="bg-white shadow-md rounded my-6">
                <table class="min-w-max w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            @foreach ($headers as $header)
                                <th class="py-3 px-6 text-left">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($rows as $row)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                @foreach ($row as $cell)
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

Now, we can use this component in our view. Let’s create a view to display all the users.

php artisan make:view users

Open the users.blade.php file and add the following code.

{{-- resources/views/users.blade.php --}}

<x-layout>
    <x-slot name="title">
        Users
    </x-slot>

    <x-slot name="content">
        <x-table-responsive :headers="['Name', 'Email', 'Created At']" :rows="$users">
            @foreach ($users as $user)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $user->name }}</td>
                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $user->email }}</td>
                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $user->created_at }}</td>
                </tr>
            @endforeach
        </x-table-responsive>
    </x-slot>
</x-layout>
