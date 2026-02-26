<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $query = Article::where('status', 'published')
            ->with(['user', 'tags', 'likes', 'comments'])
            ->latest();

        // Filtro por tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) {
                $q->where('slug', request('tag'));
            });
        }

        // Filtro por busca
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%$search%")
                ->orWhere('content', 'like', "%$search%");
        }

        // Ordenação
        if ($request->input('sort') === 'likes') {
            $query->withCount('likes')
                ->orderBy('likes_count', 'desc');
        }

        $articles = $query->paginate(12);
        $tags = Tag::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->limit(20)
            ->get();

        return view('articles.index', compact('articles', 'tags'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        $this->authorize('create', Article::class);

        $tags = Tag::all();

        return view('articles.create', compact('tags'));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:100',
            'image' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $article = new Article();
        $article->user_id = auth()->id();
        $article->title = $validated['title'];
        $article->slug = Str::slug($validated['title']) . '-' . Str::random(8);
        $article->content = $validated['content'];
        $article->status = auth()->user()->isAdmin() ? 'published' : 'pending';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->image = $path;
        }

        $article->save();

        if (!empty($validated['tags'])) {
            $article->tags()->attach($validated['tags']);
        }

        // if published immediately, notify other users about new article
        if ($article->status === 'published') {
            // send to everyone except the author
            User::where('id', '!=', $article->user_id)
                ->where('is_suspended', false)
                ->chunkById(100, function ($users) use ($article) {
                    foreach ($users as $user) {
                        Notification::create([
                            'user_id' => $user->id,
                            'type' => 'new_article',
                            'title' => 'Novo artigo publicado',
                            'content' => "O artigo '{$article->title}' foi publicado.",
                            'article_id' => $article->id,
                            'related_user_id' => $article->user_id,
                        ]);
                    }
                });
        } elseif ($article->status === 'pending') {
            // if pending, notify all admins to review
            User::where('role', 'admin')
                ->where('is_suspended', false)
                ->chunkById(100, function ($admins) use ($article) {
                    foreach ($admins as $admin) {
                        Notification::create([
                            'user_id' => $admin->id,
                            'type' => 'article_pending_review',
                            'title' => 'Artigo aguardando revisão',
                            'content' => "O artigo '{$article->title}' do utilizador '{$article->user->name}' aguarda sua aprovação.",
                            'article_id' => $article->id,
                            'related_user_id' => $article->user_id,
                        ]);
                    }
                });
        }

        return redirect()->route('articles.show', $article->slug)
            ->with('success', $article->status === 'published' ? 'Artigo criado e publicado com sucesso!' : 'Artigo criado com sucesso! Aguarde aprovação do administrador.');
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        $article->incrementViews();

        $comments = $article->comments()
            ->with('user')
            ->latest()
            ->paginate(10);

        $isLiked = false;
        if (auth()->check()) {
            $isLiked = $article->likes()
                ->where('user_id', auth()->id())
                ->exists();
        }

        $debates = $article->debates()->with('creator')->paginate(5);

        return view('articles.show', compact('article', 'comments', 'isLiked', 'debates'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article)
    {
        $this->authorize('update', $article);

        $tags = Tag::all();

        return view('articles.edit', compact('article', 'tags'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:100',
            'image' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $article->title = $validated['title'];
        $article->content = $validated['content'];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->image = $path;
        }

        $article->save();

        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('articles.show', $article->slug)
            ->with('success', 'Artigo atualizado com sucesso!');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Artigo deletado com sucesso!');
    }
}
