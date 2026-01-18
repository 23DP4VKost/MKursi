<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($topicId)
    {
        return response()->json(
            Task::where('topic_id', $topicId)->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'question' => 'required|string',
            'correct_answer' => 'required|string'
        ]);

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Uzdevums pievienots',
            'task' => $task
        ], 201);
    }

    public function checkAnswer(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $isCorrect = $request->answer === $task->correct_answer;

        return response()->json([
            'pareizi' => $isCorrect,
            'ziņa' => $isCorrect ? 'Pareiza atbilde' : 'Nepareiza atbilde'
        ]);
    }
}
