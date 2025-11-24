<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class LocaleController extends Controller
{
    /**
     * Switch locale and queue a cookie for 1 year (525600 minutes).
     * GET /lang/{locale}
     */
    public function switch(Request $request, $locale)
    {
        $supported = Config::get('locales.supported', ['en']);
        $default   = Config::get('locales.default', config('app.locale'));

        if (! in_array($locale, $supported)) {
            $locale = $default;
        }

        // minutes: 60 * 24 * 365 = 525600
        $minutes = 60 * 24 * 365;
        Cookie::queue('locale', $locale, $minutes);

        // redirect back (or home if no referrer)
        $back = url()->previous() ?: route('home');
        return Redirect::to($back);
    }
}
