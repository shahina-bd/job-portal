<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Country;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard with system metrics
     */
    public function dashboard()
    {
        return response()->json([
            'metrics' => [
                'total_users' => User::count(),
                'employees' => User::where('user_type', 'employee')->count(),
                'employers' => User::where('user_type', 'employer')->count(),
                'active_users' => User::where('is_active', true)->count(),
                'total_jobs' => JobPost::count(),
                'active_jobs' => JobPost::where('status', true)->count(),
                'total_applications' => JobApplication::count(),
                'pending_applications' => JobApplication::where('status', 'pending')->count(),
                'users_today' => User::whereDate('created_at', today())->count(),
                'jobs_today' => JobPost::whereDate('created_at', today())->count(),
            ],
        ]);
    }

    /**
     * Get all users with optional filters
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->user_type) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->is_active !== null) {
            $query->where('is_active', $request->is_active == 'true');
        }

        if ($request->search) {
            $query->where('username', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        return response()->json([
            'users' => $query->with('company')
                ->paginate($request->per_page ?? 50),
        ]);
    }

    /**
     * Get single user with all relations
     */
    public function showUser(User $user)
    {
        $user->load([
            'educations',
            'experiences',
            'skills',
            'trainings',
            'documents',
            'address.country',
            'company.country',
            'jobs.applications',
            'applications.job',
        ]);

        return response()->json(['user' => $user]);
    }

    /**
     * Update user (admin override)
     */
    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'is_active' => 'nullable|boolean',
            'user_type' => 'nullable|in:admin,employee,employer',
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Delete user and cascade delete related data
     */
    public function deleteUser(User $user)
    {
        // Soft delete: preserve history
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Get all jobs with filters
     */
    public function jobs(Request $request)
    {
        $query = JobPost::with(['employer:id,username,email', 'category:id,name']);

        if ($request->status !== null) {
            $query->where('status', $request->status == 'true');
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->employer_id) {
            $query->where('user_id', $request->employer_id);
        }

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('job_description', 'like', "%{$request->search}%");
        }

        return response()->json([
            'jobs' => $query->withCount('applications')
                ->paginate($request->per_page ?? 50),
        ]);
    }

    /**
     * Get single job with all applications
     */
    public function showJob(JobPost $job)
    {
        $job->load([
            'employer:id,username,email',
            'category:id,name',
            'applications.employee:id,username,email',
        ]);

        return response()->json(['job' => $job]);
    }

    /**
     * Update job status (admin override)
     */
    public function updateJob(Request $request, JobPost $job)
    {
        $data = $request->validate([
            'status' => 'nullable|boolean',
            'title' => 'nullable|string',
            'job_description' => 'nullable|string',
        ]);

        $job->update($data);

        return response()->json([
            'message' => 'Job updated successfully',
            'job' => $job,
        ]);
    }

    /**
     * Delete job and cascade related applications
     */
    public function deleteJob(JobPost $job)
    {
        $job->delete(); // Cascade: applications will be deleted

        return response()->json(['message' => 'Job deleted successfully']);
    }

    /**
     * Get all job applications with filters
     */
    public function applications(Request $request)
    {
        $query = JobApplication::with([
            'job:id,title',
            'employee:id,username,email',
            'employer:id,username,email',
        ]);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->job_id) {
            $query->where('job_post_id', $request->job_id);
        }

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->employer_id) {
            $query->where('employer_id', $request->employer_id);
        }

        return response()->json([
            'applications' => $query->latest()
                ->paginate($request->per_page ?? 50),
        ]);
    }

    /**
     * Get single application
     */
    public function showApplication(JobApplication $application)
    {
        $application->load([
            'job:id,title,description',
            'employee.educations',
            'employee.experiences',
            'employee.skills',
            'employer:id,username,email',
        ]);

        return response()->json(['application' => $application]);
    }

    /**
     * Update application status
     */
    public function updateApplication(Request $request, JobApplication $application)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,reviewed,selected,rejected,hired',
        ]);

        $application->update($data);

        // TODO: Send email to employee about status change

        return response()->json([
            'message' => 'Application status updated',
            'application' => $application,
        ]);
    }

    /**
     * Get all categories
     */
    public function categories(Request $request)
    {
        $query = Category::withCount('jobs');

        if ($request->status !== null) {
            $query->where('status', $request->status == 'true');
        }

        return response()->json([
            'categories' => $query->paginate($request->per_page ?? 50),
        ]);
    }

    /**
     * Create category
     */
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:categories,name',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $category = Category::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? true,
        ]);

        return response()->json(['category' => $category], 201);
    }

    /**
     * Update category
     */
    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'nullable|string|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $category->update($data);

        return response()->json(['category' => $category]);
    }

    /**
     * Delete category
     */
    public function deleteCategory(Category $category)
    {
        // Check if category has jobs
        if ($category->jobs()->exists()) {
            return response()->json(['error' => 'Cannot delete category with active jobs'], 409);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

    /**
     * Get all countries
     */
    public function countries(Request $request)
    {
        $query = Country::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
        }

        return response()->json([
            'countries' => $query->paginate($request->per_page ?? 100),
        ]);
    }

    /**
     * Create country
     */
    public function storeCountry(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:countries,name',
            'code' => 'required|string|unique:countries,code|size:2',
            'is_active' => 'nullable|boolean',
        ]);

        $country = Country::create([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json(['country' => $country], 201);
    }

    /**
     * Update country
     */
    public function updateCountry(Request $request, Country $country)
    {
        $data = $request->validate([
            'name' => 'nullable|string|unique:countries,name,' . $country->id,
            'code' => 'nullable|string|size:2|unique:countries,code,' . $country->id,
            'is_active' => 'nullable|boolean',
        ]);

        $country->update($data);

        return response()->json(['country' => $country]);
    }

    /**
     * Delete country
     */
    public function deleteCountry(Country $country)
    {
        $country->delete();

        return response()->json(['message' => 'Country deleted successfully']);
    }

    /**
     * System analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->period ?? 'month'; // week, month, quarter, year
        $days = match ($period) {
            'week' => 7,
            'quarter' => 90,
            'year' => 365,
            default => 30,
        };

        return response()->json([
            'registrations' => User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereDate('created_at', '>=', now()->subDays($days))
                ->groupBy('date')
                ->get(),

            'applications' => JobApplication::selectRaw('DATE(apply_date) as date, COUNT(*) as count')
                ->whereDate('apply_date', '>=', now()->subDays($days))
                ->groupBy('date')
                ->get(),

            'jobs_by_category' => Category::withCount('jobs')->get(),

            'most_applied_jobs' => JobPost::withCount('applications')
                ->orderByDesc('applications_count')
                ->take(10)
                ->get(),

            'top_employers' => User::where('user_type', 'employer')
                ->withCount('jobs')
                ->orderByDesc('jobs_count')
                ->take(10)
                ->get(),
        ]);
    }
}
