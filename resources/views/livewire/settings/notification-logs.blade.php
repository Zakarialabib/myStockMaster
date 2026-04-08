@php
    $mailStyles = \App\Models\Setting::first()?->mail_styles ?? ['primary_color' => '#4f46e5'];
    $primaryColor = $mailStyles['primary_color'] ?? '#4f46e5';
@endphp
<div style="--theme-primary: {{ $primaryColor }}; --theme-primary-light: {{ $primaryColor }}33;">
    <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Notification History') }}</h2>
    
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3">{{ __('Date') }}</th>
                    <th class="px-4 py-3">{{ __('Event Type') }}</th>
                    <th class="px-4 py-3">{{ __('Channel') }}</th>
                    <th class="px-4 py-3">{{ __('Subject / Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    @php
                        $data = json_decode($log->data, true);
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ class_basename($log->type) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                  style="background-color: var(--theme-primary-light); color: var(--theme-primary);">
                                {{ $data['channel'] ?? 'database' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $data['subject'] ?? 'System Notification' }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Status: {{ $data['status'] ?? 'completed' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            {{ __('No notifications found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
