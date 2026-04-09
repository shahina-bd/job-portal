<?php
/**
 * Sample ORM Queries for Job Portal
 * 
 * This file documents common Eloquent queries used throughout the application.
 * All queries use eager loading (->with()) to prevent N+1 problems.
 */

namespace App\Queries;

use App\Models\JobPost;
use App\Models\User;

class JobPortalQueries
{
    /**
     * Get all jobs with employer and company information
     * 
     * Usage:
     *   $jobs = JobPost::with('employer.company')->get();
     *   $jobs = JobPost::with(['employer', 'category'])->paginate(15);
     */
    public static function allJobsWithEmployer()
    {
        return JobPost::with(['employer:id,username,email', 'category:id,name'])->get();
    }

    /**
     * Get all jobs with full relationships
     * 
     * Usage:
     *   $jobs = JobPost::with('employer', 'category', 'applications')->get();
     */
    public static function allJobsWithRelations()
    {
        return JobPost::with([
            'employer:id,username,email,user_type',
            'category:id,name',
            'applications:id,job_post_id,employee_id,status',
        ])->get();
    }

    /**
     * Get employer's own jobs
     * 
     * Usage:
     *   auth()->user()->jobs;
     *   auth()->user()->jobs()->with('applications')->get();
     */
    public static function employerJobs()
    {
        return auth()->user()->jobs()->with(['category', 'applications'])->get();
    }

    /**
     * Get job with all applications and applicants
     * 
     * Usage:
     *   $job = JobPost::with('applications.employee')->find($id);
     */
    public static function jobWithApplications($jobId)
    {
        return JobPost::with([
            'applications:id,job_post_id,employee_id,employer_id,status,apply_date',
            'applications.employee:id,username,email',
            'applications.employee.educations',
            'applications.employee.skills',
        ])->find($jobId);
    }

    /**
     * Get employee's own applications
     * 
     * Usage:
     *   auth()->user()->applications()->with('job')->get();
     *   auth()->user()->applications()->with(['job', 'employer'])->get();
     */
    public static function employeeApplications()
    {
        return auth()->user()->applications()->with([
            'job:id,title,salary,job_type',
            'job.category',
            'job.employer:id,username,email',
            'employer:id,username',
        ])->get();
    }

    /**
     * Check if employee already applied for a job
     * 
     * Usage:
     *   JobPortalQueries::hasApplied($jobId, $employeeId);
     */
    public static function hasApplied($jobId, $employeeId)
    {
        return auth()->user()->applications()
            ->where('job_post_id', $jobId)
            ->exists();
    }

    /**
     * Get user profile with all relations
     * 
     * Usage:
     *   $user = User::with('educations', 'experiences', 'skills', 'company')->find($id);
     */
    public static function userProfile($userId)
    {
        return User::with([
            'educations',
            'experiences',
            'skills',
            'company',
            'address',
        ])->find($userId);
    }

    /**
     * Get employer dashboard data
     * 
     * Usage:
     *   $dashboardData = JobPortalQueries::employerDashboard();
     */
    public static function employerDashboard()
    {
        return [
            'jobs' => auth()->user()->jobs()->with(['category', 'applications'])->latest()->get(),
            'company' => auth()->user()->company,
            'applicationCount' => auth()->user()->jobs()
                ->withCount('applications')
                ->sum('applications_count'),
            'recentApplications' => auth()->user()
                ->jobs()
                ->with(['applications.employee'])
                ->latest()
                ->first()
                ?->applications()
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    /**
     * Search jobs by keyword and filters
     * 
     * Usage:
     *   JobPortalQueries::searchJobs('developer', 'full-time', 1);
     */
    public static function searchJobs($keyword = '', $jobType = '', $categoryId = '')
    {
        $query = JobPost::with(['employer', 'category']);

        if ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                  ->orWhere('job_description', 'like', "%{$keyword}%");
        }

        if ($jobType) {
            $query->where('job_type', $jobType);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->where('status', true)->latest()->get();
    }

    /**
     * Get jobs expiring soon (within next 7 days)
     * 
     * Usage:
     *   $expiringJobs = JobPortalQueries::expiringJobs();
     */
    public static function expiringJobs()
    {
        return JobPost::with('employer')
            ->whereBetween('end_date', [now(), now()->addDays(7)])
            ->where('status', true)
            ->get();
    }

    /**
     * Get top viewed/applied jobs
     * 
     * Usage:
     *   $topJobs = JobPortalQueries::topJobs(10);
     */
    public static function topJobs($limit = 10)
    {
        return JobPost::with(['employer', 'category'])
            ->withCount('applications')
            ->where('status', true)
            ->orderByDesc('applications_count')
            ->take($limit)
            ->get();
    }

    /**
     * Get candidate selection with full details
     * 
     * Usage:
     *   $selections = CandidateSelection::with('job', 'employee', 'employer')->get();
     */
    public static function candidateSelectionsWithDetails()
    {
        return $this->with([
            'job:id,title,user_id',
            'employee:id,username,email',
            'employer:id,username,email',
        ])->get();
    }
}
