<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    /**
     * Process a user message and return a matched knowledge-base response.
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = mb_strtolower(trim($request->input('message')));
        $knowledgeBase = config('assistant.knowledge_base', []);

        foreach ($knowledgeBase as $entry) {
            foreach ($entry['keywords'] as $keyword) {
                if (str_contains($message, $keyword)) {
                    return response()->json([
                        'matched' => true,
                        'topic' => $entry['topic'],
                        'message' => $entry['response'],
                    ]);
                }
            }
        }

        return response()->json([
            'matched' => false,
            'topic' => null,
            'message' => config('assistant.fallback'),
        ]);
    }
}
