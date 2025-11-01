<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    /**
     * Get all unique tags from articles.
     */
    public function index(): JsonResponse
    {
        $articles = Article::whereNotNull('tags')->get();

        $tagsCount = [];

        foreach ($articles as $article) {
            if (is_array($article->tags)) {
                foreach ($article->tags as $tag) {
                    if (!isset($tagsCount[$tag])) {
                        $tagsCount[$tag] = 0;
                    }
                    $tagsCount[$tag]++;
                }
            }
        }

        // Sort by count descending
        arsort($tagsCount);

        $tags = array_map(function ($tag, $count) {
            return [
                'name' => $tag,
                'count' => $count,
            ];
        }, array_keys($tagsCount), $tagsCount);

        return response()->json(array_values($tags));
    }
}
