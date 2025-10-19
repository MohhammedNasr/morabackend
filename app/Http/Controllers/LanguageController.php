<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Switch the application's locale.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {

        $supportedLanguages = ['en', 'ar'];

        Log::info('Language switch attempt', [
            'requested_locale' => $locale,
            'current_locale' => App::getLocale(),
            'session_locale' => Session::get('locale')
        ]);

        if (!in_array($locale, $supportedLanguages)) {
            Log::warning('Unsupported language requested', ['locale' => $locale]);
            return redirect()->back();
        }

        // Set the application locale
        App::setLocale($locale);

        // Store the locale in the session
        Session::put('locale', $locale);

        // Set the locale for the current request
        request()->setLocale($locale);



        Log::info('Language switch successful', [
            'new_locale' => $locale,
            'app_locale' => App::getLocale(),
            'session_locale' => Session::get('locale'),
            'request_locale' => request()->getLocale()
        ]);


        return redirect()->back();
    }
}
