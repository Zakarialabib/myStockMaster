@component('mail::message')

<span>You are receiving this email because we received a password reset request for your account.</span>

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

<span>If you did not request a password reset, no further action is required.</span>

<span>Regards,<span><br>
{{ config('app.name') }}
@endcomponent