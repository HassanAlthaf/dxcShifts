@component('mail::message')

@component('mail::panel')
Download the attached PDF to get the updated schedule for {{ $month }}/{{ $year }}.

Thanks,<br>
{{ config('app.name') }}.
@endcomponent

@endcomponent