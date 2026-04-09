<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|string',
            'years_experience' => 'nullable|integer|min:0',
        ]);

        $skill = Skill::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'level' => $data['level'] ?? null,
            'years_experience' => $data['years_experience'] ?? null,
        ]);

        return response()->json(['skill' => $skill], 201);
    }

    public function show(Skill $skill)
    {
        if ($skill->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['skill' => $skill]);
    }

    public function update(Request $request, Skill $skill)
    {
        if ($skill->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $skill->update($request->validate([
            'name' => 'nullable|string|max:255',
            'level' => 'nullable|string',
            'years_experience' => 'nullable|integer|min:0',
        ]));

        return response()->json(['skill' => $skill]);
    }

    public function destroy(Skill $skill)
    {
        if ($skill->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $skill->delete();

        return response()->noContent();
    }
}
