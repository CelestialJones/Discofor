<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
use App\Models\Debate;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_articles' => Article::count(),
            'articles_with_pdf' => Attachment::count(),
            'total_comments' => Comment::count(),
            'total_debates' => Debate::count(),
            'published_articles' => Article::where('status', 'published')->count(),
            'pending_articles' => Article::where('status', 'pending')->count(),
            'active_users_today' => User::whereDate('last_activity_at', today())->count(),
        ];

        $recent_activities = ActivityLog::latest()->take(10)->get();
        $recent_articles = Article::with('user')->latest()->take(5)->get();
        $recent_users = User::latest()->take(5)->get();
        $comments_pending_moderation = Comment::where('approved', false)->count();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recent_activities' => $recent_activities,
            'recent_articles' => $recent_articles,
            'recent_users' => $recent_users,
            'comments_pending_moderation' => $comments_pending_moderation,
        ]);
    }

    /**
     * Show users management page.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details.
     */
    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update user role.
     */
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user->update($validated);

        return response()->json(['success' => true, 'message' => 'Role atualizado com sucesso!']);
    }

    /**
     * Ban a user.
     */
    public function banUser(User $user)
    {
        $user->update(['is_suspended' => true]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_banned',
            'description' => "User {$user->email} foi banido",
        ]);

        return response()->json(['success' => true, 'message' => 'Usuário banido com sucesso!']);
    }

    /**
     * Unban a user.
     */
    public function unbanUser(User $user)
    {
        $user->update(['is_suspended' => false]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_unbanned',
            'description' => "User {$user->email} foi desbanido",
        ]);

        return response()->json(['success' => true, 'message' => 'Usuário desbanido com sucesso!']);
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user)
    {
        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_deleted',
            'description' => "User {$user->email} foi deletado",
        ]);

        return response()->json(['success' => true, 'message' => 'Usuário deletado com sucesso!']);
    }

    /**
     * Show articles management page.
     */
    public function articles(Request $request)
    {
        $query = Article::query()->with(['user', 'attachment']);

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('has_pdf')) {
            if ($request->has_pdf === 'yes') {
                $query->has('attachment');
            }

            if ($request->has_pdf === 'no') {
                $query->doesntHave('attachment');
            }
        }

        $articles = $query->latest()->paginate(15)->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Approve an article.
     */
    public function approveArticle(Article $article)
    {
        $article->update(['status' => 'published']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'article_approved',
            'description' => "Article '{$article->title}' foi aprovado",
        ]);

        // notify author about approval
        Notification::create([
            'user_id' => $article->user_id,
            'type' => 'article_approved',
            'title' => 'Seu artigo foi aprovado',
            'content' => "Seu artigo '{$article->title}' foi aprovado e está publicado.",
            'article_id' => $article->id,
            'related_user_id' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Artigo aprovado com sucesso!']);
    }

    /**
     * Reject an article.
     */
    public function rejectArticle(Article $article)
    {
        $article->update(['status' => 'removed']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'article_rejected',
            'description' => "Article '{$article->title}' foi rejeitado",
        ]);

        return response()->json(['success' => true, 'message' => 'Artigo rejeitado com sucesso!']);
    }

    /**
     * Delete an article.
     */
    public function deleteArticle(Article $article)
    {
        $article->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'article_deleted',
            'description' => "Article '{$article->title}' foi deletado",
        ]);

        return response()->json(['success' => true, 'message' => 'Artigo deletado com sucesso!']);
    }

    /**
     * Remove the PDF attachment from an article without deleting the article.
     */
    public function deleteAttachment(Article $article)
    {
        if (!$article->attachment) {
            return response()->json(['success' => false, 'message' => 'Este artigo não possui PDF anexado.'], 404);
        }

        $fileName = $article->attachment->original_name;
        $article->attachment->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'article_attachment_deleted',
            'description' => "O PDF '{$fileName}' foi removido do artigo '{$article->title}'",
        ]);

        return response()->json(['success' => true, 'message' => 'PDF removido com sucesso!']);
    }

    /**
     * Show moderation page for comments.
     */
    public function moderateComments(Request $request)
    {
        $query = Comment::where('approved', false);

        if ($request->filled('article')) {
            $query->where('article_id', $request->article);
        }

        $comments = $query->latest()->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Approve a comment.
     */
    public function approveComment(Comment $comment)
    {
        $comment->update(['approved' => true]);

        return response()->json(['success' => true, 'message' => 'Comentário aprovado!']);
    }

    /**
     * Reject a comment.
     */
    public function rejectComment(Comment $comment)
    {
        $comment->delete();

        return response()->json(['success' => true, 'message' => 'Comentário rejeitado!']);
    }

    /**
     * Approve or reject comments in bulk.
     */
    public function moderateCommentsBulk(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'comment_ids' => 'required|array|min:1',
            'comment_ids.*' => 'integer|exists:comments,id',
        ]);

        $pendingComments = Comment::whereIn('id', $validated['comment_ids'])
            ->where('approved', false);

        if ($validated['action'] === 'approve') {
            $affected = $pendingComments->update(['approved' => true]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'comments_bulk_approved',
                'description' => "{$affected} comentários foram aprovados em lote",
            ]);

            return response()->json([
                'success' => true,
                'affected' => $affected,
                'message' => "{$affected} comentário(s) aprovado(s).",
            ]);
        }

        $affected = $pendingComments->count();
        $pendingComments->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'comments_bulk_rejected',
            'description' => "{$affected} comentários foram rejeitados em lote",
        ]);

        return response()->json([
            'success' => true,
            'affected' => $affected,
            'message' => "{$affected} comentário(s) rejeitado(s).",
        ]);
    }

    /**
     * Show activity logs.
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        $logs = $query->latest()->paginate(50);

        return view('admin.logs.index', compact('logs'));
    }
}
