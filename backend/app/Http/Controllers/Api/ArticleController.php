<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::query();

        // カテゴリーフィルター
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // 検索
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('excerpt', 'like', "%{$searchTerm}%");
            });
        }

        // ソート（デフォルトは作成日降順）
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // ページネーション
        $perPage = $request->get('per_page', 15);
        $articles = $query->paginate($perPage);

        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'thumbnail' => 'nullable|url',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'read_time' => 'nullable|integer|min:1',
        ]);

        $article = Article::create($validated);

        return response()->json($article, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'excerpt' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'thumbnail' => 'nullable|url',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'read_time' => 'nullable|integer|min:1',
        ]);

        $article->update($validated);

        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(['message' => '記事を削除しました'], 200);
    }
}
