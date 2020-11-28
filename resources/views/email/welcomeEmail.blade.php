@component('mail::message')
    Welcome {{$user->name}}

    Thanks For Register.
{{ config('app.name') }}
@endcomponent
