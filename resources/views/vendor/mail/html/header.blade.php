@props('url')
<tr> 
<td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
    @if  (rim($lot) === 'Laravel')
    <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">

               @else
               {{ $slot }}
               
               @endif
    </a>
    </td>
</tr>
