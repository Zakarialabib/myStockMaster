<div>
    @section('title', __('Logs'))

    <x-theme.breadcrumb :title="__('Logs')" :parent="route('dashboard')" :parentName="__('Dashboard')" :childrenName="__('Logs')">
    </x-theme.breadcrumb>

    <x-card>
        <table class="table-auto w-full mt-4" x-data="{ open: false }">
            <thead>
                <tr>
                    <th class="px-4 py-2">
                        Log Info
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    @php($logJson = json_encode(File::get($log)))

                    <tr>
                        <td class="border px-4 py-2">
                            {{ $log }}
                            <button @click="open = !open"
                                class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                                +
                            </button>
                        </td>
                    </tr>
                    <tr x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="bg-gray-100">
                        <td class="p-4">
                            <code>
                                {{ json_decode($logJson) }}
                            </code>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
</div>
