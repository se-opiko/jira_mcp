<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get all unique categories from articles.
     */
    public function index(): JsonResponse
    {
        $categories = Article::select('category')
            ->whereNotNull('category')
            ->groupBy('category')
            ->selectRaw('category, COUNT(*) as count')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category,
                    'count' => $item->count,
                ];
            });

        return response()->json($categories);
    }
}
