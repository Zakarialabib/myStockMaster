{{-- @props('url'!= null) --}}
<tr>
<td class="header" style="background-color: {{ \App\Models\Setting::first()?->mail_styles['primary_color'] ?? '#2d3748' }};">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
