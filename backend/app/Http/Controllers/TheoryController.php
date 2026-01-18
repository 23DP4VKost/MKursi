<?php

namespace App\Http\Controllers;

use App\Models\Theory;
use Illuminate\Http\Request;

class TheoryController extends Controller
{
    public function index($topicId)
    {
        return response()->json(
            Theory::where('topic_id', $topicId)->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'title' => 'required|string',
            'content' => 'required|string'
        ]);

        $theory = Theory::create($validated);

        return response()->json([
            'message' => 'Teorija pievienota',
            'theory' => $theory
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $theory = Theory::findOrFail($id);
        $theory->update($request->all());

        return response()->json([
            'message' => 'Teorija atjaunināta'
        ]);
    }

    public function destroy($id)
    {
        Theory::destroy($id);

        return response()->json([
            'message' => 'Teorija dzēsta'
        ]);
    }
}
