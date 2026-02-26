<?php

use Illuminate\Support\Facades\Broadcast;

/**
 * Here you may register all of the event broadcasting channels that your
 * application supports. The given channel authorization callbacks are
 * used to check if an authenticated user can listen to the channel.
 */

Broadcast::channel('debate.{debateId}', function ($user, $debateId) {
    // Only authenticated users can join a debate channel.
    // Additional logic (e.g. check if debate is public) can be added here.
    return $user !== null;
});

Broadcast::channel('article.{articleId}', function ($user, $articleId) {
    // Allow any authenticated user to listen for comments on an article.
    return $user !== null;
});
