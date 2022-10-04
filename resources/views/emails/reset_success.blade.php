@component('mail::message')

<h4>You are changed your password successful.</h4>
<span>If you did change password, no further action is required.</span>
<span>If you did not change password, protect your account.</span>

<span>Regards,<span><br>
{{ config('app.name') }}
@endcomponent