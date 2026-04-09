<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'company' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $experience = Experience::create([
            'user_id' => auth()->id(),
            'title' => $data['title'] ?? null,
            'company' => $data['company'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'is_current' => $data['is_current'] ?? false,
            'description' => $data['description'] ?? null,
        ]);

        return response()->json(['experience' => $experience], 201);
    }

    public function show(Experience $experience)
    {
        if ($experience->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['experience' => $experience]);
    }

    public function update(Request $request, Experience $experience)
    {
        if ($experience->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $experience->update($request->validate([
            'title' => 'nullable|string',
            'company' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]));

        return response()->json(['experience' => $experience]);
    }

    public function destroy(Experience $experience)
    {
        if ($experience->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $experience->delete();

        return response()->noContent();
    }
}
