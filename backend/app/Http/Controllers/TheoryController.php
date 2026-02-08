<?php

namespace App\Http\Controllers;

use App\Models\Theory;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;

class TheoryController extends Controller
{
    public function index(int $topicId): JsonResponse
    {
        $topic = Topic::query()
            ->with(['theories' => function ($query) {
                $query->orderBy('subtopic_name')->select(['id', 'subtopic_name', 'topic_id']);
            }])
            ->select(['id', 'name'])
            ->findOrFail($topicId);

        return response()->json($topic);
    }

    public function show(int $theoryId): JsonResponse
    {
        $theory = Theory::query()
            ->select(['id', 'subtopic_name', 'content', 'topic_id'])
            ->findOrFail($theoryId);

        return response()->json($theory);
    }
}
