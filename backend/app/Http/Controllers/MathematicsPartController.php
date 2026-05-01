<?php

namespace App\Http\Controllers;

use App\Models\MathematicsPart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MathematicsPartController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|size:3|unique:mathematics_parts,code',
            'name' => 'required|string|max:40',
        ]);

        $part = MathematicsPart::create([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Daļa veiksmīgi pievienota.',
            'part' => $part,
        ], 201);
    }

    public function update(Request $request, MathematicsPart $mathematicsPart): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|size:3|unique:mathematics_parts,code,' . $mathematicsPart->id,
            'name' => 'required|string|max:40',
        ]);

        $mathematicsPart->update([
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Daļa atjaunināta.',
            'part' => $mathematicsPart->fresh(),
        ]);
    }

    public function destroy(MathematicsPart $mathematicsPart): JsonResponse
    {
        $mathematicsPart->delete();

        return response()->json([
            'message' => 'Daļa dzēsta.',
        ]);
    }
}
