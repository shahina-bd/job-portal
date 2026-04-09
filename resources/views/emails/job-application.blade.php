@component('mail::message')
# New Job Application

Hello {{ $employer->username }},

You have received a new job application for:

**{{ $job->title }}**

Applied by: {{ $employee->username }} ({{ $employee->email }})

Application Date: {{ $application->apply_date->format('F d, Y H:i') }}

@component('mail::button', ['url' => url('/applications/' . $application->id)])
View Application
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
