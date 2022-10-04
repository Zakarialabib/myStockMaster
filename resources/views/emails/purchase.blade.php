@component('mail::message')

<h2 class="text-center">Purchase order </h2>

<h3>Hello {{ $data['supplier_name'] }}</h3>
<h3>Purchase Number: {{ $data['Ref'] }}</h3>
<h3>Review receipt in the attachment </h3>

<span>Regards,<span><br>
{{ config('app.name') }}
@endcomponent
