<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'degree' => 'nullable|string',
            'institution' => 'nullable|string',
            'field_of_study' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $education = Education::create([
            'user_id' => auth()->id(),
            'degree' => $data['degree'] ?? null,
            'institution' => $data['institution'] ?? null,
            'field_of_study' => $data['field_of_study'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return response()->json(['education' => $education], 201);
    }

    public function show(Education $education)
    {
        if ($education->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['education' => $education]);
    }

    public function update(Request $request, Education $education)
    {
        if ($education->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $education->update($request->validate([
            'degree' => 'nullable|string',
            'institution' => 'nullable|string',
            'field_of_study' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]));

        return response()->json(['education' => $education]);
    }

    public function destroy(Education $education)
    {
        if ($education->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $education->delete();

        return response()->noContent();
    }
}
