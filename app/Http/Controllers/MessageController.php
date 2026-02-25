<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Debate;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Store a newly created message.
     */
    public function store(Request $request, Debate $debate)
    {
        $this->authorize('createMessage', $debate);

        $validated = $request->validate([
            'message' => 'required|string|min:1|max:500',
        ]);

        $message = $debate->messages()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        // Dispatch event for real-time chat
        MessageSent::dispatch($message);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                    'avatar' => $message->user->avatar,
                ],
                'message' => $message->message,
                'created_at' => $message->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Delete a message.
     */
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mensagem deletada com sucesso!',
        ]);
    }

    /**
     * Get messages after a certain ID for polling.
     */
    public function getNew(Request $request, Debate $debate)
    {
        $after = $request->query('after', 0);

        $messages = $debate->messages()
            ->with('user')
            ->where('id', '>', $after)
            ->orderBy('id')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'avatar' => $message->user->avatar,
                    ],
                    'message' => $message->message,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            });

        return response()->json(['messages' => $messages]);
    }
}
