<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Supports both session-based auth (web) and token-based auth (sanctum)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Try to authenticate with Sanctum (Bearer token) first
        $user = Auth::guard('sanctum')->user();
        
        // If not authenticated with Sanctum, try default web auth
        if (!$user) {
            $user = $request->user();
        }
        
        if (!$user || !$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
