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
        $parts = MathematicsPart::query()
            ->with(['topics' => function ($query) {
                $query->orderBy('name')->select(['id', 'name', 'math_part_id']);
            }])
            ->orderBy('name')
            ->get(['id', 'code', 'name']);

        return response()->json($parts);
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Nav tiesību piekļūt šai sadaļai.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'math_part_id' => 'required|integer|exists:mathematics_parts,id',
        ]);

        $topic = Topic::query()->create($validated);

        return response()->json([
            'message' => 'Tēma veiksmīgi pievienota.',
            'topic' => $topic,
        ], 201);
    }

    public function update(Request $request, Topic $topic): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Nav tiesību piekļūt šai sadaļai.'], 403);
        }

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

    public function destroy(Request $request, Topic $topic): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Nav tiesību piekļūt šai sadaļai.'], 403);
        }

        $topic->delete();

        return response()->json([
            'message' => 'Tēma dzēsta.',
        ]);
    }
}
