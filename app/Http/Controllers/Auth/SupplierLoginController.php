<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SupplierLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.supplier-login');
    }

    public function login(Request $request)
    {
        // First logout from all sessions
        try {
            if (array_key_exists('store-web', config('auth.guards'))) {
                Auth::guard('store-web')->logout();
            }
        } catch (\Exception $e) {
            Log::warning('Store web guard logout attempt failed', ['error' => $e->getMessage()]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $request->validate([
            'phone' => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::guard('supplier-web')->attempt(
            ['phone' => $request->phone, 'password' => $request->password, 'is_active' => 1],
            $request->filled('remember')
        )) {
            $supplier = Auth::guard('supplier-web')->user();
            if (!$supplier->role || $supplier->role->slug !== 'supplier') {
                Auth::guard('supplier-web')->logout();
                throw ValidationException::withMessages([
                    'phone' => __('auth.unauthorized'),
                ]);
            }

            // Explicitly set guard for session handling
            Auth::shouldUse('supplier-web');
            $request->session()->regenerate();

            // Debug session, auth state and redirect
            $redirect = redirect()->route('supplier.dashboard');
            Log::debug('Supplier login session and redirect', [
                'session_id' => $request->session()->getId(),
                'guard' => Auth::getDefaultDriver(),
                'user_id' => Auth::id(),
                'session_data' => $request->session()->all(),
                'redirect_target' => $redirect->getTargetUrl(),
                'route_name' => 'supplier.dashboard'
            ]);

            // Force redirect to dashboard with cookie
            return redirect()->route('supplier.dashboard');
        }

        throw ValidationException::withMessages([
            'phone' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {

        $guard = Auth::guard('supplier-web');
        $guard->logout();
        Auth::logout();
        // Clear all session data
        $request->session()->flush();
        // Invalidate the session
        $request->session()->invalidate();
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        // Clear the session cookie using facade
        $cookie = Cookie::forget(config('session.cookie'));

        return redirect()->route('supplier.login')->withCookie($cookie);
    }
}
