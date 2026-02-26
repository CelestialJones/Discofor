<?php

namespace App\Events;

use App\Models\Like;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticleLiked implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Like $like)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('article.' . $this->like->article_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'article.liked';
    }

    public function broadcastWith(): array
    {
        return [
            'like' => [
                'id' => $this->like->id,
                'user_id' => $this->like->user_id,
                'article_id' => $this->like->article_id,
            ],
        ];
    }
}
