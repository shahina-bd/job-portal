<?php

namespace App\Jobs;

use App\Mail\JobAlertNotification;
use App\Models\JobPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendJobAlertMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public JobPost $job)
    {
    }

    public function handle(): void
    {
        // TODO: Find users matching job criteria and send alerts
        // For now, just log the job creation
    }
}
