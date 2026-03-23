<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Attachment;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $query = Article::where('status', 'published')
            ->with(['user', 'tags', 'likes', 'comments', 'attachment'])
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

        $validated = $this->validateArticle($request);

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

        if ($request->hasFile('pdf')) {
            $this->storeAttachment($article, $request->file('pdf'));
        }

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
        $article->load(['attachment', 'tags', 'user']);
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

        $validated = $this->validateArticle($request, true);

        $article->title = $validated['title'];
        $article->content = $validated['content'];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->image = $path;
        }

        $article->save();

        if ($request->boolean('remove_attachment') && $article->attachment) {
            $article->attachment->delete();
        }

        if ($request->hasFile('pdf')) {
            $this->storeAttachment($article, $request->file('pdf'));
        }

        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('articles.show', $article->slug)
            ->with('success', 'Artigo atualizado com sucesso!');
    }

    /**
     * Download the attached PDF for the specified article.
     */
    public function download(Article $article)
    {
        $this->authorize('downloadAttachment', $article);

        $attachment = $article->attachment;

        abort_unless($attachment, 404);
        abort_unless(Storage::disk($attachment->disk)->exists($attachment->file_path), 404);

        return Storage::disk($attachment->disk)->download(
            $attachment->file_path,
            $attachment->original_name
        );
    }

    /**
     * Stream the attached PDF inline for reading on the platform.
     */
    public function viewAttachment(Article $article)
    {
        abort_unless($this->canAccessAttachment($article), 403);

        $attachment = $article->attachment;

        abort_unless($attachment, 404);
        abort_unless(Storage::disk($attachment->disk)->exists($attachment->file_path), 404);

        return response()->file(
            Storage::disk($attachment->disk)->path($attachment->file_path),
            [
                'Content-Type' => $attachment->mime_type ?: 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $attachment->original_name . '"',
            ]
        );
    }

    /**
     * Generate and download the published article as a PDF document.
     */
    public function downloadPdf(Article $article)
    {
        abort_unless($this->canDownloadGeneratedPdf($article), 403);

        $article->loadMissing(['user', 'tags', 'attachment']);

        $pdf = Pdf::loadView('articles.pdf', [
            'article' => $article,
        ])->setPaper('a4');

        $filename = Str::slug($article->title) ?: 'artigo';

        return $pdf->download($filename . '.pdf');
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

    /**
     * Centralized article validation for create and update flows.
     */
    private function validateArticle(Request $request, bool $updating = false): array
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'publish_mode' => 'required|in:text,pdf',
            'content' => 'nullable|string|max:50000',
            'image' => 'nullable|image|max:2048',
            'pdf' => 'nullable|file|mimetypes:application/pdf|mimes:pdf|max:10240',
            'remove_attachment' => $updating ? 'nullable|boolean' : 'nullable',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ], [
            'content.max' => 'O conteúdo não deve ter mais de 50000 caracteres.',
            'pdf.mimetypes' => 'O anexo deve ser um arquivo PDF válido.',
            'pdf.mimes' => 'O anexo deve estar no formato PDF.',
            'pdf.max' => 'O PDF não deve ser maior que 10MB.',
        ]);

        $validator->after(function ($validator) use ($request, $updating) {
            $publishMode = $request->input('publish_mode', 'text');
            $content = trim((string) $request->input('content', ''));
            $hasUploadedPdf = $request->hasFile('pdf');
            $keepsExistingPdf = $updating && !$request->boolean('remove_attachment') && $request->route('article')?->attachment;

            if ($publishMode === 'text') {
                if ($content === '') {
                    $validator->errors()->add('content', 'O conteúdo é obrigatório para artigos do tipo texto.');
                }

                if ($content !== '' && mb_strlen($content) < 100) {
                    $validator->errors()->add('content', 'O conteúdo deve ter no mínimo 100 caracteres quando preenchido.');
                }
            }

            if ($publishMode === 'pdf') {
                if (!$hasUploadedPdf && !$keepsExistingPdf) {
                    $validator->errors()->add('pdf', 'O ficheiro PDF é obrigatório para artigos do tipo PDF.');
                }
            }
        });

        return $validator->validate();
    }

    /**
     * Persist or replace the article attachment.
     */
    private function storeAttachment(Article $article, $uploadedFile): void
    {
        if ($article->attachment) {
            $article->attachment->delete();
        }

        $path = $uploadedFile->store('article-pdfs', 'public');

        $article->attachment()->create([
            'disk' => 'public',
            'file_path' => $path,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getClientMimeType() ?: 'application/pdf',
            'size' => $uploadedFile->getSize(),
        ]);
    }

    /**
     * Determine access for generated article PDFs.
     */
    private function canDownloadGeneratedPdf(Article $article): bool
    {
        if ($article->status === 'published') {
            return true;
        }

        if (!auth()->check()) {
            return false;
        }

        return auth()->id() === $article->user_id || auth()->user()->isAdmin();
    }

    /**
     * Determine access for viewing attached PDFs inline.
     */
    private function canAccessAttachment(Article $article): bool
    {
        if ($article->status === 'published') {
            return true;
        }

        if (!auth()->check()) {
            return false;
        }

        return auth()->id() === $article->user_id || auth()->user()->isAdmin();
    }
}
