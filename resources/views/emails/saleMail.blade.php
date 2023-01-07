@component('mail::message')

<h2 class="text-center">{{__('Sale order')}}</h2>

<h3>{{__('Hello')}}</h3>
<h3>{{__('Sale Number')}}: {{ $data['reference'] }}</h3>
<h3>{{__('Review receipt in the attachment')}} </h3>

<span>{{__('Regards')}},<span><br>
{{ settings()->company_name }}
@endcomponent
