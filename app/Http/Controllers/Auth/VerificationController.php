<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the phone verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedPhone()
            ? redirect()->intended($this->redirectTo)
            : view('auth.verify');
    }

    /**
     * Mark the authenticated user's phone number as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {

        $user = $request->user();

        $store = Store::where('owner_id', $user->id)->first();

        if (!$store) {
            return back()->with('error', 'Store not found.');
        }

        $code = $request->input('code');
        if ($user->phone_verification_code == $code) {
            $user->is_verified = 1;
            $user->phone_verified_at = now();
        }
        return redirect($this->redirectTo)->with('success', __('Phone number verified successfully.'));
    }

    /**
     * Resend the phone verification code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $user = $request->user();
        $store = $user->store;

        if (!$store) {
            return back()->with('error', 'Store not found.');
        }

        if ($store->hasVerifiedPhone()) {
            return redirect($this->redirectTo);
        }

        // Generate a random 6-digit code
        $code = (string) random_int(100000, 999999);
        $cacheKey = "verification_code_{$store->phone}";

        // Store the code in cache for 10 minutes
        Cache::put($cacheKey, $code, now()->addMinutes(10));

        // Use the sendSms method from BaseService to send the code
        $this->sendSms($store->phone, "Your verification code is: $code");

        return back()->with('resent', true);
    }
}
