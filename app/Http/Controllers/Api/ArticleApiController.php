<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleApiController extends Controller
{
    /**
     * Get all published articles
     */
    public function index(Request $request)
    {
        $query = Article::where('status', 'published');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) {
                $q->where('slug', request('tag'));
            });
        }

        if ($request->has('author')) {
            $query->where('user_id', request('author'));
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'most_liked' => $query->withCount('likes')->orderByDesc('likes_count'),
            'most_commented' => $query->withCount('comments')->orderByDesc('comments_count'),
            default => $query->latest(),
        };

        $articles = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $articles->items(),
            'pagination' => [
                'total' => $articles->total(),
                'per_page' => $articles->perPage(),
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
            ],
        ]);
    }

    /**
     * Get single article
     */
    public function show(Article $article)
    {
        if ($article->status !== 'published' && auth()->guard('sanctum')->guest()) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $article->load('author', 'tags', 'comments'),
        ]);
    }

    /**
     * Search articles
     */
    public function search(Request $request)
    {
        $search = $request->input('q');

        $articles = Article::where('status', 'published')
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $articles,
        ]);
    }

    /**
     * Create new article
     */
    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string|min:100',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',
        ]);

        $article = Article::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'status' => 'pending',
        ]);

        if (!empty($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Article created successfully',
            'data' => $article,
        ], 201);
    }

    /**
     * Update article
     */
    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'excerpt' => 'string|max:500',
            'content' => 'string|min:100',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',
        ]);

        $article->update($validated);

        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully',
            'data' => $article,
        ]);
    }

    /**
     * Delete article
     */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully',
        ]);
    }

    /**
     * Like an article
     */
    public function like(Article $article)
    {
        $like = Like::firstOrCreate([
            'article_id' => $article->id,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Article liked',
            'liked' => true,
            'likes_count' => $article->likes()->count(),
        ]);
    }

    /**
     * Unlike an article
     */
    public function unlike(Article $article)
    {
        $article->likes()
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article unliked',
            'liked' => false,
            'likes_count' => $article->likes()->count(),
        ]);
    }
}
