<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Get authenticated user's profile with all relations
     */
    public function show()
    {
        $user = auth()->user()->load([
            'educations',
            'experiences',
            'skills',
            'trainings',
            'documents',
            'address.country',
            'company',
            'applications.job.category',
        ]);

        return response()->json(['profile' => $user]);
    }

    /**
     * Update user's basic profile info
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'email' => 'nullable|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => auth()->user(),
        ]);
    }

    /**
     * Get all education records for auth user
     */
    public function educations()
    {
        $educations = auth()->user()->educations()->get();
        return response()->json(['educations' => $educations]);
    }

    /**
     * Get all experience records for auth user
     */
    public function experiences()
    {
        $experiences = auth()->user()->experiences()->get();
        return response()->json(['experiences' => $experiences]);
    }

    /**
     * Get all skills for auth user
     */
    public function skills()
    {
        $skills = auth()->user()->skills()->where('is_active', true)->get();
        return response()->json(['skills' => $skills]);
    }

    /**
     * Get all trainings for auth user
     */
    public function trainings()
    {
        $trainings = auth()->user()->trainings()->get();
        return response()->json(['trainings' => $trainings]);
    }

    /**
     * Get all documents for auth user
     */
    public function documents()
    {
        $documents = auth()->user()->documents()->get();
        return response()->json(['documents' => $documents]);
    }

    /**
     * Get user's address
     */
    public function address()
    {
        $address = auth()->user()->address;

        if (!$address) {
            return response()->json(['address' => null], 404);
        }

        $address->load('country');
        return response()->json(['address' => $address]);
    }

    /**
     * Update or create user's address
     */
    public function updateAddress(Request $request)
    {
        $data = $request->validate([
            'address_type' => 'nullable|string',
            'address_line' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
        ]);

        $address = auth()->user()->address ?? new \App\Models\Address(['user_id' => auth()->id()]);
        $address->fill($data)->save();

        return response()->json(['address' => $address->load('country')]);
    }

    /**
     * Get user's company (for employers)
     */
    public function company()
    {
        $company = auth()->user()->company;

        if (!$company) {
            return response()->json(['company' => null], 404);
        }

        return response()->json(['company' => $company->load('country')]);
    }

    /**
     * Get user's job applications (for employees)
     */
    public function applications()
    {
        $applications = auth()->user()->applications()
            ->with([
                'job:id,title,salary,job_type,status',
                'job.category',
                'job.employer:id,username,email',
            ])
            ->latest()
            ->get();

        return response()->json(['applications' => $applications]);
    }

    /**
     * Get user's posted jobs (for employers)
     */
    public function jobs()
    {
        $jobs = auth()->user()->jobs()
            ->with(['category', 'applications'])
            ->latest()
            ->get();

        return response()->json(['jobs' => $jobs]);
    }
}
