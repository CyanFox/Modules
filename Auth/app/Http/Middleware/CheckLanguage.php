<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class CheckLanguage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (isset(Auth::user()->language)) {
            App::setLocale(Auth::user()->language);

            return $next($request);
        }

        $language = $request->cookie('language');

        if ($language) {
            App::setLocale($language);

            return $next($request);
        }
        App::setLocale(settings('internal.app.lang', config('app.locale')));
        $response = $next($request);

        if ($response instanceof Response) {
            $response->withCookie(cookie()->forever('language', settings('internal.app.lang', config('app.locale'))));
        }

        return $response;

    }
}
