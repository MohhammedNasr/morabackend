<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('SetLocaleMiddleware called', [
            'headers' => $request->headers->all(),
            'path' => $request->path()
        ]);

        if ($request->hasHeader('Accept-Language')) {
            $locale = $request->header('Accept-Language');
            Log::info('Detected Accept-Language header', ['locale' => $locale]);
            
            if (in_array($locale, ['ar', 'en'])) {
                app()->setLocale($locale);
                Log::info('App locale set to', ['locale' => $locale]);
            }
        }

        return $next($request);
    }
}
