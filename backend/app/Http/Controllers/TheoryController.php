<?php

namespace App\Http\Controllers;

use App\Models\Theory;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

    public function store(Request $request, Topic $topic): JsonResponse
    {
        $validated = $request->validate([
            'subtopic_name' => 'required|string|max:40',
            'content' => 'nullable|string',
        ]);

        $theory = $topic->theories()->create([
            'subtopic_name' => $validated['subtopic_name'],
            'content' => $validated['content'] ?? '',
        ]);

        $this->writeTheoryContent($theory->subtopic_name, $validated['content'] ?? '');

        return response()->json([
            'message' => 'Apakštēma veiksmīgi pievienota.',
            'theory' => $theory,
        ], 201);
    }

    public function update(Request $request, Theory $theory): JsonResponse
    {
        $validated = $request->validate([
            'subtopic_name' => 'required|string|max:40',
            'content' => 'nullable|string',
        ]);

        $previousSubtopicName = $theory->subtopic_name;

        $theory->update([
            'subtopic_name' => $validated['subtopic_name'],
            'content' => $validated['content'] ?? '',
        ]);

        $previousPath = $this->resolveTheoryContentPath($previousSubtopicName);
        $updatedPath = $this->resolveTheoryContentPath($theory->subtopic_name);

        if ($previousPath !== $updatedPath && File::exists($previousPath)) {
            File::move($previousPath, $updatedPath);
        }

        if (array_key_exists('content', $validated)) {
            $this->writeTheoryContent($theory->subtopic_name, $validated['content'] ?? '');
        }

        return response()->json([
            'message' => 'Apakštēma atjaunināta.',
            'theory' => $theory->fresh(),
        ]);
    }

    public function destroy(Theory $theory): JsonResponse
    {
        $contentPath = $this->resolveTheoryContentPath($theory->subtopic_name);

        $theory->delete();

        if (File::exists($contentPath)) {
            File::delete($contentPath);
        }

        return response()->json([
            'message' => 'Apakštēma dzēsta.',
        ]);
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
        $templatePath = $this->resolveTheoryContentPath($subtopicName);

        if (File::exists($templatePath)) {
            return File::get($templatePath);
        }

        return 'Teorijas saturs šai apakštēmai vēl nav pievienots.';
    }

    private function resolveTheoryContentPath(string $subtopicName): string
    {
        return resource_path('theories/' . Str::slug($subtopicName) . '.txt');
    }

    private function writeTheoryContent(string $subtopicName, string $content): void
    {
        File::put($this->resolveTheoryContentPath($subtopicName), $content);
    }
}
