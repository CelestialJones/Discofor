<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Models\Notification;

class SendCommentNotification
{
    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        $comment = $event->comment;
        $article = $comment->article;

        // Notify article author
        if ($article->user_id !== $comment->user_id) {
            Notification::create([
                'user_id' => $article->user_id,
                'type' => 'new_comment',
                'title' => 'Novo comentário em seu artigo',
                'content' => "{$comment->user->name} comentou em '{$article->title}'",
                'related_user_id' => $comment->user_id,
                'article_id' => $article->id,
            ]);
        }

        // Notify other commenters
        $otherCommenters = $article->comments()
            ->where('user_id', '!=', $comment->user_id)
            ->where('user_id', '!=', $article->user_id)
            ->distinct('user_id')
            ->pluck('user_id');

        foreach ($otherCommenters as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => 'new_comment',
                'title' => 'Novo comentário em um artigo que você comentou',
                'content' => "{$comment->user->name} comentou em '{$article->title}'",
                'related_user_id' => $comment->user_id,
                'article_id' => $article->id,
            ]);
        }
    }
}
