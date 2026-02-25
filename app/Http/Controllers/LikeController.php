<?php

namespace App\Http\Controllers;

use App\Events\ArticleLiked;
use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like on an article.
     */
    public function toggle(Request $request, Article $article)
    {
        $this->authorize('create', Like::class);

        $like = Like::where('article_id', $article->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $like = Like::create([
                'article_id' => $article->id,
                'user_id' => auth()->id(),
            ]);
            
            // Dispatch event to send notification
            ArticleLiked::dispatch($like);
            
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $article->likes()->count(),
        ]);
    }
}
