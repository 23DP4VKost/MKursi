<?php

namespace App\Http\Controllers;

use App\Models\UserQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function myQuestions(Request $request): JsonResponse
    {
        $questions = UserQuestion::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get(['id', 'question', 'answer', 'status', 'answered_at', 'created_at']);

        return response()->json($questions);
    }

    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:2000',
        ]);

        $question = UserQuestion::query()->create([
            'user_id' => $request->user()->id,
            'question' => $validated['question'],
            'status' => 'pending',
        ]);

        return response()->json($question, 201);
    }

    public function adminList(Request $request): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Nav tiesību piekļūt šai sadaļai.'], 403);
        }

        $questions = UserQuestion::query()
            ->with(['user:id,username,email'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->get(['id', 'user_id', 'question', 'answer', 'status', 'answered_at', 'created_at']);

        return response()->json($questions);
    }

    public function answer(Request $request, int $questionId): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Nav tiesību piekļūt šai sadaļai.'], 403);
        }

        $validated = $request->validate([
            'answer' => 'required|string|max:5000',
        ]);

        $question = UserQuestion::query()->findOrFail($questionId);
        $question->update([
            'answer' => $validated['answer'],
            'status' => 'answered',
            'answered_by' => $request->user()->id,
            'answered_at' => now(),
        ]);

        return response()->json([
            'message' => 'Atbilde saglabāta.',
            'question' => $question,
        ]);
    }
}
