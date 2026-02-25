<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Debate;
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

        $debate->delete();

        return redirect()->back()->with('success', 'Debate deletado com sucesso!');
    }
}
