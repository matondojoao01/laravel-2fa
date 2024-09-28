@component('mail::message')
# {{ __('mail.greeting') }}

{{ __('mail.auth_code') }}

@component('mail::panel')
**{{ $token }}**
@endcomponent

{{ __('mail.code_validity') }}

{{ __('mail.ignore_email') }}

{{ __('mail.thank_you') }}<br>
{{ config('app.name') }}
@endcomponent
