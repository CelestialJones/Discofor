<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use App\Models\Debate;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageApiController extends Controller
{
    /**
     * Get messages for a debate
     */
    public function getDebateMessages(Request $request, Debate $debate)
    {
        $messages = $debate->messages()
            ->with('user')
            ->latest()
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $messages->items(),
            'pagination' => [
                'total' => $messages->total(),
                'per_page' => $messages->perPage(),
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
            ],
        ]);
    }

    /**
     * Send a message to a debate
     */
    public function store(Request $request, Debate $debate)
    {
        if (!$debate->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'This debate is closed',
            ], 422);
        }

        $validated = $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        $message = $debate->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        // Dispatch event for real-time updates
        MessageSent::dispatch($message);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message->load('user'),
        ], 201);
    }

    /**
     * Delete a message
     */
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully',
        ]);
    }
}
