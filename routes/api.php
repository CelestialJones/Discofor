<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleApiController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\DebateApiController;
use App\Http\Controllers\Api\MessageApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Articles API
    Route::get('/articles', [ArticleApiController::class, 'index']);
    Route::get('/articles/{article:slug}', [ArticleApiController::class, 'show']);
    Route::post('/articles/search', [ArticleApiController::class, 'search']);
    
    // Users API
    Route::get('/users', [UserApiController::class, 'index']);
    Route::get('/users/{user}', [UserApiController::class, 'show']);
    Route::get('/users/{user}/articles', [UserApiController::class, 'articles']);
    
    // Debates API (public access for viewing)
    Route::get('/debates', [DebateApiController::class, 'index']);
    Route::get('/debates/{debate}', [DebateApiController::class, 'show']);
    Route::get('/debates/{debate}/messages', [MessageApiController::class, 'getDebateMessages']);
    
    // Auth endpoint
    Route::post('/auth/register', [\App\Http\Controllers\Api\AuthApiController::class, 'register']);
    Route::post('/auth/login', [\App\Http\Controllers\Api\AuthApiController::class, 'login']);
    
    // Protected routes - require authentication
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [\App\Http\Controllers\Api\AuthApiController::class, 'logout']);
        
        // Articles - Create, Update, Delete
        Route::post('/articles', [ArticleApiController::class, 'store']);
        Route::put('/articles/{article:slug}', [ArticleApiController::class, 'update']);
        Route::delete('/articles/{article:slug}', [ArticleApiController::class, 'destroy']);
        
        // Comments API
        Route::post('/articles/{article}/comments', [CommentApiController::class, 'store']);
        Route::put('/comments/{comment}', [CommentApiController::class, 'update']);
        Route::delete('/comments/{comment}', [CommentApiController::class, 'destroy']);
        Route::post('/comments/{comment}/approve', [CommentApiController::class, 'approve']);
        
        // Likes API
        Route::post('/articles/{article}/like', [ArticleApiController::class, 'like']);
        Route::delete('/articles/{article}/like', [ArticleApiController::class, 'unlike']);
        
        // Debates API
        Route::post('/debates', [DebateApiController::class, 'store']);
        Route::post('/debates/{debate}/close', [DebateApiController::class, 'close']);
        Route::delete('/debates/{debate}', [DebateApiController::class, 'destroy']);
        
        // Messages API
        Route::post('/debates/{debate}/messages', [MessageApiController::class, 'store']);
        Route::delete('/messages/{message}', [MessageApiController::class, 'destroy']);
        
        // User Profile
        Route::get('/user', [UserApiController::class, 'profile']);
        Route::put('/user', [UserApiController::class, 'updateProfile']);
        Route::post('/user/avatar', [UserApiController::class, 'uploadAvatar']);
        
        // Notifications API
        Route::get('/notifications', [\App\Http\Controllers\Api\NotificationApiController::class, 'index']);
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\Api\NotificationApiController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [\App\Http\Controllers\Api\NotificationApiController::class, 'markAllAsRead']);
    });
});
