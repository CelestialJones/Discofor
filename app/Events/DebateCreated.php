<?php

namespace App\Events;

use App\Models\Debate;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DebateCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Debate $debate;

    /**
     * Create a new event instance.
     */
    public function __construct(Debate $debate)
    {
        $this->debate = $debate;
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        // broadcast to the article channel so the author and anyone watching can hear
        return new PrivateChannel('article.' . $this->debate->article_id);
    }

    public function broadcastWith(): array
    {
        // eager load creator so we can include name/avatar
        $this->debate->load('creator');

        return [
            'debate' => [
                'id' => $this->debate->id,
                'title' => $this->debate->title,
                'description' => $this->debate->description,
                'created_by' => $this->debate->created_by,
                'creator_name' => $this->debate->creator?->name,
                'article_id' => $this->debate->article_id,
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'debate.created';
    }
}
