@component('mail::message')

<h2 class="text-center">PAYMENT RECEIPT</h2>

<h3>Hello {{$data['client_name']}}</h3>
<h3>Transaction number: {{$data['Ref']}}</h3>
<h3>Review your receipt in the attachment </h3>

<span>Regards,<span><br>
{{ config('app.name') }}
@endcomponent


