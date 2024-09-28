@component('mail::message')
# {{ __('auth_2fa.reset_password') }}

{{ __('auth_2fa.reset_email_message') }}

@component('mail::button', ['url' => url('password/reset', $token)])
{{ __('auth_2fa.reset_button') }}
@endcomponent

{{ __('auth_2fa.no_action_required') }}

{{ __('auth_2fa.regards') }}<br>
{{ config('app.name') }}
@endcomponent
