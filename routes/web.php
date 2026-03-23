<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Search Routes
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search.search');
Route::get('/api/search/autocomplete', [\App\Http\Controllers\SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Articles Routes
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create')->middleware('auth');
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store')->middleware('auth');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/articles/{article:slug}/pdf', [ArticleController::class, 'downloadPdf'])->name('articles.pdf');
Route::get('/articles/{article:slug}/attachment/view', [ArticleController::class, 'viewAttachment'])->name('articles.attachment.view');
Route::get('/articles/{article:slug}/download', [ArticleController::class, 'download'])->name('articles.download')->middleware('auth');
Route::get('/articles/{article:slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit')->middleware('auth');
Route::put('/articles/{article:slug}', [ArticleController::class, 'update'])->name('articles.update')->middleware('auth');
Route::delete('/articles/{article:slug}', [ArticleController::class, 'destroy'])->name('articles.destroy')->middleware('auth');

// Comments Routes
Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update')->middleware('auth');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware('auth');

// Likes Routes
Route::post('/articles/{article}/like', [\App\Http\Controllers\LikeController::class, 'toggle'])->name('likes.toggle')->middleware('auth');

// Debates Routes
Route::post('/articles/{article}/debates', [\App\Http\Controllers\DebateController::class, 'store'])->name('debates.store')->middleware('auth');
Route::get('/debates/{debate}', [\App\Http\Controllers\DebateController::class, 'show'])->name('debates.show');
Route::post('/debates/{debate}/close', [\App\Http\Controllers\DebateController::class, 'close'])->name('debates.close')->middleware('auth');
Route::delete('/debates/{debate}', [\App\Http\Controllers\DebateController::class, 'destroy'])->name('debates.destroy')->middleware('auth');

// Messages Routes
Route::post('/debates/{debate}/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store')->middleware('auth');
Route::delete('/messages/{message}', [\App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy')->middleware('auth');

// User Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\UserController::class, 'dashboard'])->name('users.dashboard');
    Route::get('/profile/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    Route::get('/my-articles', [\App\Http\Controllers\UserController::class, 'myArticles'])->name('users.my-articles');
    Route::get('/edit-profile', [\App\Http\Controllers\UserController::class, 'editProfile'])->name('users.edit-profile');
    Route::post('/edit-profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('users.update-profile');
});

// Notifications Routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-all', [\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear-all');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}/role', [\App\Http\Controllers\Admin\AdminController::class, 'updateUserRole'])->name('users.update-role');
    Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\AdminController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\AdminController::class, 'unbanUser'])->name('users.unban');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Articles Management
    Route::get('/articles', [\App\Http\Controllers\Admin\AdminController::class, 'articles'])->name('articles');
    Route::post('/articles/{article}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveArticle'])->name('articles.approve');
    Route::post('/articles/{article}/reject', [\App\Http\Controllers\Admin\AdminController::class, 'rejectArticle'])->name('articles.reject');
    Route::delete('/articles/{article}/attachment', [\App\Http\Controllers\Admin\AdminController::class, 'deleteAttachment'])->name('articles.attachment.delete');
    Route::delete('/articles/{article}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteArticle'])->name('articles.delete');
    
    // Comments Moderation
    Route::get('/comments', [\App\Http\Controllers\Admin\AdminController::class, 'moderateComments'])->name('comments');
    Route::post('/comments/bulk', [\App\Http\Controllers\Admin\AdminController::class, 'moderateCommentsBulk'])->name('comments.bulk');
    Route::post('/comments/{comment}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveComment'])->name('comments.approve');
    Route::post('/comments/{comment}/reject', [\App\Http\Controllers\Admin\AdminController::class, 'rejectComment'])->name('comments.reject');
    
    // Activity Logs
    Route::get('/logs', [\App\Http\Controllers\Admin\AdminController::class, 'activityLogs'])->name('logs');
    Route::get('/debates/{debate}/messages', [\App\Http\Controllers\MessageController::class, 'getNew'])->name('messages.getNew');
});

// Auth Routes (from Laravel Breeze)
require __DIR__.'/auth.php';
