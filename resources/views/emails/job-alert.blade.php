@component('mail::message')
# New Job Alert

An exciting new job has been posted!

**{{ $job->title }}**

**Company:** {{ $employer->username }}

**Job Type:** {{ ucfirst($job->job_type) }}

**Salary:** {{ $job->salary ? $job->currency . ' ' . number_format($job->salary, 2) : 'Negotiable' }}

**Description:**
{{ Str::limit($job->job_description, 200) }}

@component('mail::button', ['url' => url('/jobs/' . $job->id)])
View Full Job Posting
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
