<?php

namespace App\Http\Controllers;

use App\Models\MathematicsPart;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(): JsonResponse
    {
        $parts = MathematicsPart::with(['topics' => function ($query) {
                $query->with(['theories' => function ($theoryQuery) {
                        $theoryQuery->orderBy('subtopic_name')->select(['id', 'subtopic_name', 'topic_id']);
                    }])
                    ->orderBy('name')
                    ->select(['id', 'name', 'math_part_id']);
            }])
            ->orderBy('name')
            ->get(['id', 'code', 'name']);

        return response()->json($parts);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'math_part_id' => 'required|integer|exists:mathematics_parts,id',
        ]);

        $topic = Topic::create($validated);

        return response()->json([
            'message' => 'Tēma veiksmīgi pievienota.',
            'topic' => $topic,
        ], 201);
    }

    public function update(Request $request, Topic $topic): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'math_part_id' => 'required|integer|exists:mathematics_parts,id',
        ]);

        $topic->update($validated);

        return response()->json([
            'message' => 'Tēma atjaunināta.',
            'topic' => $topic->fresh(),
        ]);
    }

    public function destroy(Topic $topic): JsonResponse
    {
        $topic->delete();

        return response()->json([
            'message' => 'Tēma dzēsta.',
        ]);
    }
}
