<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show user profile
     */
    public function show(User $user)
    {
        $articles = $user->articles()
            ->where('status', 'published')
            ->latest()
            ->paginate(10);

        $stats = [
            'articles' => $user->articles()->count(),
            'likes' => $user->likes()->count(),
            'comments' => $user->comments()->count(),
        ];

        return view('users.profile', compact('user', 'articles', 'stats'));
    }

    /**
     * Show current user dashboard
     */
    public function dashboard()
    {
        $this->authorize('viewDashboard', auth()->user());

        $user = auth()->user();

        $articles = $user->articles()->latest()->limit(5)->get();
        $recentComments = $user->comments()->with('article')->latest()->limit(5)->get();
        $recentActivity = $user->activityLogs()->latest()->limit(5)->get();

        $stats = [
            'total_articles' => $user->articles()->count(),
            'total_likes' => $user->likes()->count(),
            'total_comments' => $user->comments()->count(),
            'published_articles' => $user->articles()->where('status', 'published')->count(),
            'pending_articles' => $user->articles()->where('status', 'pending')->count(),
        ];

        return view('users.dashboard', compact('user', 'articles', 'recentComments', 'recentActivity', 'stats'));
    }

    /**
     * Show edit profile form
     */
    public function editProfile()
    {
        $user = auth()->user();

        return view('users.edit-profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('users.dashboard')
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Show my articles
     */
    public function myArticles()
    {
        $articles = auth()->user()->articles()
            ->latest()
            ->paginate(15);

        return view('users.my-articles', compact('articles'));
    }
}
