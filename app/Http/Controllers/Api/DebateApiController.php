<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Debate;
use Illuminate\Http\Request;

class DebateApiController extends Controller
{
    /**
     * Get all debates
     */
    public function index(Request $request)
    {
        $query = Debate::query();

        if ($request->has('article')) {
            $query->where('article_id', $request->input('article'));
        }

        if ($request->has('active')) {
            $active = $request->input('active');
            if ($active) {
                $query->where('is_active', true);
            }
        }

        $debates = $query->latest()->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $debates->items(),
            'pagination' => [
                'total' => $debates->total(),
                'per_page' => $debates->perPage(),
                'current_page' => $debates->currentPage(),
                'last_page' => $debates->lastPage(),
            ],
        ]);
    }

    /**
     * Get single debate
     */
    public function show(Debate $debate)
    {
        return response()->json([
            'success' => true,
            'data' => $debate->load('creator', 'article', 'messages'),
        ]);
    }

    /**
     * Create a debate
     */
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $debate = $article->debates()->create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Debate created successfully',
            'data' => $debate,
        ], 201);
    }

    /**
     * Close a debate
     */
    public function close(Debate $debate)
    {
        $this->authorize('update', $debate);

        $debate->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Debate closed successfully',
            'data' => $debate,
        ]);
    }

    /**
     * Delete a debate
     */
    public function destroy(Debate $debate)
    {
        $this->authorize('delete', $debate);

        $debate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Debate deleted successfully',
        ]);
    }
}
