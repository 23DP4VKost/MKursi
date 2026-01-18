<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        return response()->json(
            Topic::all()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'class_level' => 'required|integer'
        ]);

        $topic = Topic::create($validated);

        return response()->json([
            'message' => 'Tēma izveidota',
            'topic' => $topic
        ], 201);
    }

    public function show($id)
    {
        return response()->json(
            Topic::with(['theories', 'tasks'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->update($request->all());

        return response()->json([
            'message' => 'Tēma atjaunināta'
        ]);
    }

    public function destroy($id)
    {
        Topic::destroy($id);

        return response()->json([
            'message' => 'Tēma dzēsta'
        ]);
    }
}
