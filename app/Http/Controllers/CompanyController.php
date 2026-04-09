<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'company_type' => 'nullable|string',
            'website_url' => 'nullable|url',
            'company_size' => 'nullable|string',
            'company_description' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
        ]);

        $company = Company::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'company_type' => $data['company_type'] ?? null,
            'website_url' => $data['website_url'] ?? null,
            'company_size' => $data['company_size'] ?? null,
            'company_description' => $data['company_description'] ?? null,
            'country_id' => $data['country_id'] ?? null,
        ]);

        return response()->json(['company' => $company], 201);
    }

    public function show()
    {
        $company = auth()->user()->company;

        if (! $company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        return response()->json(['company' => $company]);
    }

    public function update(Request $request)
    {
        $company = auth()->user()->company;

        if (! $company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        $company->update($request->validate([
            'name' => 'nullable|string|max:255',
            'company_type' => 'nullable|string',
            'website_url' => 'nullable|url',
            'company_size' => 'nullable|string',
            'company_description' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
        ]));

        return response()->json(['company' => $company]);
    }
}
