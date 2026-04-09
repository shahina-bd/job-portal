<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public JobApplication $application)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Application Received',
        );
    }

    public function content()
    {
        return $this->view('emails.job-application')
            ->with([
                'application' => $this->application,
                'job' => $this->application->job,
                'employee' => $this->application->employee,
                'employer' => $this->application->employer,
            ]);
    }
}
