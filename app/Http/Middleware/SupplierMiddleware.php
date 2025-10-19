<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupplierMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('supplier-web')->check()) {
            return redirect()->route('supplier.login');
        }

        $user = auth()->guard('supplier-web')->user();

        if (!$user->role || $user->role->slug !== 'supplier') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
