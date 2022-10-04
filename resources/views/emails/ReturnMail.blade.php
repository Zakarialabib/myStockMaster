@component('mail::message')

<h2 class="text-center">Return order</h2>

<h3>Hello {{ $data['client_name'] }}</h3>
<h3>Return Number: {{ $data['Ref'] }}</h3>
<h3>Review receipt in the attachment </h3>

<span>Regards,<span><br>
{{ config('app.name') }}
@endcomponent
