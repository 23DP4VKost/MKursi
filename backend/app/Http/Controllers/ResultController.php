<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'is_correct' => 'required|boolean'
        ]);

        $result = Result::create([
            'user_id' => auth()->id(),
            'task_id' => $validated['task_id'],
            'is_correct' => $validated['is_correct']
        ]);

        return response()->json([
            'message' => 'Rezultāts saglabāts',
            'result' => $result
        ]);
    }

    public function index()
    {
        return response()->json(
            Result::where('user_id', auth()->id())->get()
        );
    }
}
