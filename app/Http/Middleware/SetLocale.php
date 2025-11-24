<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Priority: cookie -> Accept-Language -> default
     */
    public function handle(Request $request, Closure $next)
    {
        $supported = Config::get('locales.supported', ['en']);
        $default   = Config::get('locales.default', config('app.locale'));

        // 1) Cookie (persisted choice)
        $cookieLocale = $request->cookie('locale');
        if ($cookieLocale && in_array($cookieLocale, $supported)) {
            App::setLocale($cookieLocale);
            return $next($request);
        }

        // 2) Accept-Language negotiation
        $preferred = $request->getPreferredLanguage($supported);
        if ($preferred && in_array($preferred, $supported)) {
            App::setLocale($preferred);
            return $next($request);
        }

        // 3) fallback to default
        App::setLocale($default);
        return $next($request);
    }
}
