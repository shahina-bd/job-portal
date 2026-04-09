<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JobPost;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    public function index()
    {
        $jobs = JobPost::with(['employer:id,username,email', 'category:id,name', 'applications'])->where('status', true)->latest()->get();
        return $jobs;
    }

    public function create()
    {
        $categories = Category::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('jobs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'publish_date' => 'required|date',
            'end_date' => 'required|date|after:publish_date',
            'requirements' => 'required|string',
            'salary' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string',
            'job_type' => 'required|in:full-time,part-time,remote',
        ]);

        $jobPost = JobPost::create([
            'user_id' => auth()->id(),
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'job_description' => $data['job_description'],
            'publish_date' => $data['publish_date'],
            'end_date' => $data['end_date'],
            'requirements' => $data['requirements'],
            'salary' => $data['salary'] ?? null,
            'currency' => $data['currency'] ?? null,
            'job_type' => $data['job_type'],
            'status' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['job' => $jobPost], 201);
        }

        return redirect('/dashboard')->with('success', 'Job post created successfully.');
    }

    public function show(JobPost $job)
    {
        $job->load(['employer:id,username,email', 'category:id,name', 'applications']);
        return $job;
    }

    public function edit(JobPost $job)
    {
        // return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, JobPost $job)
    {
        // validate and update job post
    }

    public function destroy(JobPost $job)
    {
        $job->delete();

        return response()->noContent();
    }
}
