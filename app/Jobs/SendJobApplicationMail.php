<?php

namespace App\Jobs;

use App\Mail\JobApplicationNotification;
use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendJobApplicationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public JobApplication $application)
    {
    }

    public function handle(): void
    {
        $employer = $this->application->employer;

        if ($employer && $employer->email) {
            Mail::to($employer->email)->send(new JobApplicationNotification($this->application));
        }
    }
}
