<?php

namespace App\Http\Controllers;

use App\Jobs\SendJobApplicationMail;
use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function store(Request $request, JobPost $job)
    {
        $request->validate([]);

        $existingApplication = JobApplication::where('job_post_id', $job->id)
            ->where('employee_id', auth()->id())
            ->first();

        if ($existingApplication) {
            return response()->json(['error' => 'Already applied for this job'], 409);
        }

        $application = JobApplication::create([
            'job_post_id' => $job->id,
            'employee_id' => auth()->id(),
            'employer_id' => $job->user_id,
            'apply_date' => now(),
        ]);

        // Dispatch email notification via queue
        dispatch(new SendJobApplicationMail($application));

        return response()->json(['application' => $application], 201);
    }

    public function show(JobApplication $application)
    {
        if ($application->employee_id !== auth()->id() && $application->employer_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $application->load(['job:id,title,salary,job_type', 'job.category', 'employee:id,username,email', 'employer:id,username,email']);

        return response()->json(['application' => $application]);
    }

    public function destroy(JobApplication $application)
    {
        if ($application->employee_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $application->delete();

        return response()->noContent();
    }
}
