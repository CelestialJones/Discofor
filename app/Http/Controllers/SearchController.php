<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search articles, users and tags
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type', 'articles');
        $sort = $request->input('sort', 'latest');
        $tag = $request->input('tag');
        $author = $request->input('author');

        if (!$query && !$tag && !$author) {
            return redirect()->route('articles.index');
        }

        $results = collect();

        if ($type === 'articles' || $type === 'all') {
            $articlesQuery = Article::where('status', 'published');

            if ($query) {
                $articlesQuery->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('excerpt', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                });
            }

            if ($tag) {
                $articlesQuery->whereHas('tags', function ($q) use ($tag) {
                    $q->where('slug', $tag);
                });
            }

            if ($author) {
                $articlesQuery->where('user_id', $author);
            }

            // Sorting
            switch ($sort) {
                case 'oldest':
                    $articlesQuery->oldest();
                    break;
                case 'most_liked':
                    $articlesQuery->withCount('likes')
                                  ->orderByDesc('likes_count');
                    break;
                case 'most_commented':
                    $articlesQuery->withCount('comments')
                                  ->orderByDesc('comments_count');
                    break;
                default:
                    $articlesQuery->latest();
            }

            $articles = $articlesQuery->with('user')->paginate(12);
        } else {
            $articles = null;
        }

        if ($type === 'users' || $type === 'all') {
            $usersQuery = User::where('is_banned', false);

            if ($query) {
                $usersQuery->where('name', 'like', "%{$query}%")
                          ->orWhere('email', 'like', "%{$query}%");
            }

            $users = $usersQuery->paginate(12);
        } else {
            $users = null;
        }

        if ($type === 'tags' || $type === 'all') {
            $tagsQuery = Tag::query();

            if ($query) {
                $tagsQuery->where('name', 'like', "%{$query}%");
            }

            $tags = $tagsQuery->paginate(12);
        } else {
            $tags = null;
        }

        // Get available tags and authors for filters
        $availableTags = Tag::all();
        $availableAuthors = User::whereHas('articles', function ($q) {
            $q->where('status', 'published');
        })->get();

        return view('search.results', compact(
            'query',
            'type',
            'sort',
            'articles',
            'users',
            'tags',
            'availableTags',
            'availableAuthors'
        ));
    }

    /**
     * Get autocomplete suggestions
     */
    public function autocomplete(Request $request)
    {
        $query = $request->input('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $articles = Article::where('status', 'published')
            ->where('title', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'title', 'slug']);

        $users = User::where('name', 'like', "%{$query}%")
            ->where('is_banned', false)
            ->limit(5)
            ->get(['id', 'name']);

        $tags = Tag::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'articles' => $articles,
            'users' => $users,
            'tags' => $tags,
        ]);
    }
}
