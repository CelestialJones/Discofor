<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Article $article)
    {
        $this->authorize('create', Comment::class);

        $validated = $request->validate([
            'content' => 'required|string|min:5|max:1000',
        ]);

        $comment = $article->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        // Dispatch event to send notifications
        CommentCreated::dispatch($comment);

        return response()->json([
            'success' => true,
            'message' => 'Comentário criado com sucesso!',
            'comment' => [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|min:5|max:1000',
        ]);

        $comment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Comentário atualizado com sucesso!',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'updated_at' => $comment->updated_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comentário deletado com sucesso!',
        ]);
    }
}
