@component('mail::message')
# {{ __('auth.reset_password') }}

{{ __('auth.reset_email_message') }}

@component('mail::button', ['url' => url('password/reset', $token)])
{{ __('auth.reset_button') }}
@endcomponent

{{ __('auth.no_action_required') }}

{{ __('auth.regards') }}<br>
{{ config('app.name') }}
@endcomponent
