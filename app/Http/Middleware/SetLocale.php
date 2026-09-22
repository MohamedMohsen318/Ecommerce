<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = array_keys(config('app.supported_locales', []));
        $fallbackLocale = config('app.fallback_locale', config('app.locale'));
        $sessionKey = $request->is('admin/*') ? 'admin_locale' : 'locale';
        $locale = $request->session()->get($sessionKey, config('app.locale'));

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = $fallbackLocale;
            $request->session()->put($sessionKey, $locale);        }

        App::setLocale($locale);

        return $next($request);
    }
}
