<?php

namespace App\Http\Controllers;

use App\Models\MathematicsPart;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $parts = MathematicsPart::query()
            ->with(['topics' => function ($query) {
                $query->orderBy('name')->with(['tasks' => function ($taskQuery) {
                    $taskQuery->orderBy('id')->select([
                        'id',
                        'name',
                        'question',
                        'correct_answer',
                        'difficulty_level',
                        'topic_id',
                    ]);
                }])->select(['id', 'name', 'math_part_id']);
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
            'name' => 'required|string|max:40',
            'question' => 'required|string|max:10000',
            'correct_answer' => 'nullable|string|max:10',
            'difficulty_level' => 'nullable|in:viegls,videjs,sarezgit',
            'topic_id' => 'required|integer|exists:topics,id',
        ]);

        $task = Task::query()->create($validated);

        return response()->json([
            'message' => 'Uzdevums veiksmīgi pievienots.',
            'task' => $task,
        ], 201);
    }
}
