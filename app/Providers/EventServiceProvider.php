<?php

namespace App\Providers;

use App\Events\ArticleLiked;
use App\Events\CommentCreated;
use App\Listeners\SendCommentNotification;
use App\Listeners\SendLikeNotification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        CommentCreated::class => [
            SendCommentNotification::class,
        ],

        ArticleLiked::class => [
            SendLikeNotification::class,
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Allow tuning password reset retry interval without publishing full auth config.
        Config::set('auth.passwords.users.throttle', (int) env('PASSWORD_RESET_THROTTLE_SECONDS', 30));
    }
}
