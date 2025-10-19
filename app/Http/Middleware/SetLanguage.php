<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $supportedLanguages = ['en', 'ar'];
        $currentLocale = App::getLocale();
        $sessionLocale = Session::get('locale');

        Log::info('SetLanguage middleware started', [
            'current_locale' => $currentLocale,
            'session_locale' => $sessionLocale,
            'request_locale' => $request->getLocale(),
            'url' => $request->fullUrl()
        ]);

        // Check Accept-Language header first
        $acceptLanguage = $request->header('Accept-Language');
        if ($acceptLanguage && in_array(substr($acceptLanguage, 0, 2), $supportedLanguages)) {
            $language = substr($acceptLanguage, 0, 2);
            Log::info('Language set from Accept-Language header', ['language' => $language]);
        }
        // Then check URL parameter (for direct language switching)
        elseif ($request->has('lang') && in_array($request->lang, $supportedLanguages)) {
            $language = $request->lang;
            Session::put('locale', $language);
            Log::info('Language set from URL parameter', ['language' => $language]);
        }
        // Then check session
        elseif (Session::has('locale') && in_array(Session::get('locale'), $supportedLanguages)) {
            $language = Session::get('locale');
            Log::info('Language set from session', [
                'language' => $language,
                'previous_locale' => $currentLocale
            ]);
        }
        // If no valid language is set, default to Arabic
        else {
            $language = 'ar';
            Session::put('locale', $language);
            Log::info('Defaulting to Arabic locale', ['language' => $language]);
        }

        // Set the application locale
        App::setLocale($language);

        // Set the locale for the current request
        $request->setLocale($language);

        Log::info('Final locale settings', [
            'app_locale' => App::getLocale(),
            'session_locale' => Session::get('locale'),
            'request_locale' => $request->getLocale()
        ]);

        return $next($request);
    }
}
