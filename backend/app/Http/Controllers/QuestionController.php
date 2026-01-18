<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string'
        ]);

        $question = Question::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description']
        ]);

        return response()->json([
            'message' => 'Jautājums nosūtīts',
            'question' => $question
        ], 201);
    }

    public function index()
    {
        return response()->json(
            Question::with('user')->get()
        );
    }
}
