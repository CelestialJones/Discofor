<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Debate;
use App\Models\Comment;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $stats = [
            'articles' => Article::where('status', 'published')->count(),
            'users' => User::count(),
            'comments' => Comment::count(),
            'debates' => Debate::count(),
        ];

        $featured_articles = Article::where('status', 'published')
            ->latest('created_at')
            ->take(6)
            ->with(['user', 'tags', 'likes'])
            ->get()
            ->map(function ($article) {
                $article->likes_count = $article->likes()->count();
                return $article;
            });

        return view('index', compact('stats', 'featured_articles'));
    }
}
