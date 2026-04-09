<?php

namespace App\Mail;

use App\Models\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobAlertNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public JobPost $job)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Alert: ' . $this->job->title,
        );
    }

    public function content()
    {
        return $this->view('emails.job-alert')
            ->with([
                'job' => $this->job,
                'employer' => $this->job->employer,
            ]);
    }
}
