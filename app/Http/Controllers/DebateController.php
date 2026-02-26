<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Debate;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class DebateController extends Controller
{
    /**
     * Store a newly created debate.
     */
    public function store(Request $request, Article $article)
    {
        $this->authorize('create', Debate::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $debate = $article->debates()->create([
            'created_by' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => 'active',
        ]);

        // notify article author if they are not the one starting the debate
        if ($article->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $article->user_id,
                'type' => 'new_debate',
                'title' => 'Novo debate iniciado',
                'content' => "Um debate foi iniciado no artigo '{$article->title}'", 
                'related_user_id' => auth()->id(),
                'article_id' => $article->id,
            ]);
        }

        // notify all other users about the new active debate (except creator)
        User::where('id', '!=', auth()->id())
            ->where('is_suspended', false)
            ->chunkById(100, function ($users) use ($article) {
                foreach ($users as $user) {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'new_debate',
                        'title' => 'Debate iniciado',
                        'content' => "Um novo debate foi iniciado no artigo '{$article->title}'", 
                        'related_user_id' => auth()->id(),
                        'article_id' => $article->id,
                    ]);
                }
            });

        // broadcast new debate event for real-time updates on article page
        event(new \App\Events\DebateCreated($debate));

        return redirect()->route('debates.show', $debate)
            ->with('success', 'Debate criado com sucesso!');
    }

    /**
     * Display the specified debate.
     */
    public function show(Debate $debate)
    {
        $messages = $debate->messages()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('debates.show', compact('debate', 'messages'));
    }

    /**
     * Close a debate.
     */
    public function close(Debate $debate)
    {
        $this->authorize('update', $debate);

        $debate->update(['status' => 'closed']);

        return redirect()->back()->with('success', 'Debate encerrado com sucesso!');
    }

    /**
     * Delete a debate.
     */
    public function destroy(Debate $debate)
    {
        $this->authorize('delete', $debate);

        $article = $debate->article;
        $debate->delete();

        // after deletion, going back to the same page will 404 (model gone)
        // redirect the user to the parent article instead
        return redirect()->route('articles.show', $article->slug)
            ->with('success', 'Debate deletado com sucesso!');
    }
}
