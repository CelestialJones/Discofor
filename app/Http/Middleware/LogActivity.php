<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && $request->isMethod('post', 'put', 'delete')) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $request->method(),
                'description' => $request->path(),
            ]);
        }

        return $response;
    }
}
