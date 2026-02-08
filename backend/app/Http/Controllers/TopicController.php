<?php

namespace App\Http\Controllers;

use App\Models\MathematicsPart;
use Illuminate\Http\JsonResponse;

class TopicController extends Controller
{
    public function index(): JsonResponse
    {
        $parts = MathematicsPart::query()
            ->with(['topics' => function ($query) {
                $query->orderBy('name')->select(['id', 'name', 'math_part_id']);
            }])
            ->orderBy('name')
            ->get(['id', 'code', 'name']);

        return response()->json($parts);
    }
}
