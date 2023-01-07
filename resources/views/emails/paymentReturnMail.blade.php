@component('mail::message')

<h2 class="text-center">{{__('PAYMENT RECEIPT')}}</h2>

<h3>{{__('Hello')}} {{$data['client_name']}}</h3>
<h3>{{__('Transaction number')}}: {{$data['reference']}}</h3>
<h3>{{__('Review your receipt in the attachment')}} </h3>

<span>{{__('Regards')}},<span><br>
{{ settings()->company_name }}
@endcomponent


