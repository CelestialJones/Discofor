<?php

namespace App\Listeners;

use App\Events\ArticleLiked;
use App\Models\Notification;

class SendLikeNotification
{
    /**
     * Handle the event.
     */
    public function handle(ArticleLiked $event): void
    {
        $like = $event->like;
        $article = $like->article;

        // Notify article author
        if ($article->user_id !== $like->user_id) {
            Notification::create([
                'user_id' => $article->user_id,
                'type' => 'new_like',
                'title' => 'Seu artigo recebeu uma curtida',
                'content' => "{$like->user->name} curtiu '{$article->title}'",
                'related_user_id' => $like->user_id,
                'article_id' => $article->id,
            ]);
        }
    }
}
