<?php

namespace App\Http\Controllers;

use App\Models\Theory;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

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
            ->select(['id', 'subtopic_name', 'topic_id'])
            ->findOrFail($theoryId);

        $content = $this->resolveTheoryContent($theory->subtopic_name);

        return response()->json([
            'id' => $theory->id,
            'subtopic_name' => $theory->subtopic_name,
            'content' => $content,
            'topic_id' => $theory->topic_id,
        ]);
    }

    private function resolveTheoryContent(string $subtopicName): string
    {
        $templatePath = match ($subtopicName) {
            'Eksponentfunkcija' => resource_path('theories/Eksponentfunkcija.txt'),
            default => null,
        };

        if ($templatePath && File::exists($templatePath)) {
            return File::get($templatePath);
        }

        return 'Teorijas saturs šai apakštēmai vēl nav pievienots.';
    }
}
